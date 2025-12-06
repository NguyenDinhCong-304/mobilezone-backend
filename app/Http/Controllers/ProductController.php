<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\ProductStore;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sale', 'store']);

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:category,id',
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|max:255|unique:product,slug',
            'price_buy'     => 'required|numeric|min:0',
            'status'        => 'required|in:0,1',
            'images'        => 'required|array|min:1',
            'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048', // validate nhiều ảnh
            'attributes'    => 'array', 
        ]);

        $images = $request->file('images');

        // ảnh đầu tiên làm thumbnail
        $thumbnailPath = $images[0]->store('products/thumbnails', 'public');

        // 1. Lưu product
        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $request->slug,
            'content'     => $request->content,
            'description' => $request->description,
            'price_buy'   => $request->price_buy,
            'status'      => $request->status,
            'created_by'  => auth()->id() ?? 1,
            'thumbnail'   => $thumbnailPath,
        ]);

        // 2. Lưu tất cả ảnh
        foreach ($images as $file) {
            $path = $file->store('products/images', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image'       => $path,
            ]);
        }

        // 3. Lưu thuộc tính sản phẩm
        if ($request->filled('attributes')) {
            $attrData = [];
            foreach ($request->input('attributes') as $attr) {
                $attrData[$attr['attribute_id']] = ['value' => $attr['value']];
            }
            $product->attributes()->attach($attrData);
        }


        return response()->json([
            'message' => 'Thêm sản phẩm thành công',
            'data'    => $product->load('images', 'attributes'),
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['images', 'sale', 'store', 'attributes'])
            ->findOrFail($id);
        // Nếu muốn trả ra kèm đường dẫn đầy đủ cho thumbnail
        // $product->thumbnail_url = asset('storage/' . $product->thumbnail);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            // Nếu có file thumbnail mới thì lưu lại
            if ($request->hasFile('thumbnail')) {
                // Xóa ảnh cũ nếu có
                if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                    Storage::disk('public')->delete($product->thumbnail);
                }
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
            } else {
                $thumbnailPath = $product->thumbnail;
            }

            // Cập nhật thông tin sản phẩm
            $product->update([
                'category_id' => $request->category_id ?? $product->category_id,
                'name'        => $request->name ?? $product->name,
                'slug'        => $request->name ? Str::slug($request->name) : $product->slug,
                'thumbnail'   => $thumbnailPath,
                'content'     => $request->content ?? $product->content,
                'description' => $request->description ?? $product->description,
                'price_buy'   => $request->price_buy ?? $product->price_buy,
                'status'      => $request->status ?? $product->status,
                'updated_by'  => auth()->id() ?? 1,
            ]);

            // Cập nhật danh sách ảnh (nếu có)
            if ($request->hasFile('images')) {
                // Xóa ảnh cũ
                foreach ($product->images as $img) {
                    if (Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                    $img->delete();
                }

                // Lưu ảnh mới
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products/images', 'public');
                    $product->images()->create(['image' => $path]);
                }
            }

            // Cập nhật sale (nếu có)
            if ($request->has('sale')) {
                $product->sale()->delete();
                $product->sale()->create($request->sale);
            }

            // Cập nhật store (nếu có)
            if ($request->has('store')) {
                $product->store()->delete();
                $product->store()->create($request->store);
            }

            // Nếu attributes được gửi dạng JSON string thì decode
            if ($request->has('attributes') && is_string($request->attributes)) {
                $request->merge(['attributes' => json_decode($request->attributes, true)]);
            }

            // Cập nhật attributes
            if ($request->has('attributes')) {
                $attributes = json_decode($request->input('attributes'), true);

                // Xóa quan hệ cũ (chỉ trong bảng trung gian, không ảnh hưởng bảng attributes)
                $product->attributes()->detach();

                foreach ($attributes as $attr) {
                    if (!empty($attr['name']) && !empty($attr['value'])) {
                        $attributeModel = \App\Models\Attribute::withTrashed()->where('name', $attr['name'])->first();
                        if ($attributeModel) {
                            $product->attributes()->attach($attributeModel->id, [
                                'value' => $attr['value'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }


            DB::commit();

            return response()->json([
                'message' => 'Cập nhật sản phẩm thành công',
                'data' => $product->load(['images', 'sale', 'store', 'attributes'])
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Xóa mềm sản phẩm
     */
    public function destroy(string $id)
    {
        // $product = Product::findOrFail($id);
        // $product->delete(); //Xóa mềm
        // return response()->json(['message' => 'Xóa sản phẩm thành công']);
        $product = Product::withTrashed()->findOrFail($id);

        // Xóa hết ảnh liên quan
        foreach ($product->images as $img) {
            \Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        // Xóa luôn sản phẩm
        $product->forceDelete();

        return response()->json([
            'message' => 'Xóa sản phẩm thành công',
        ]);
    }
    // public function forceDelete($id)
    // {
    //     $product = Product::withTrashed()->findOrFail($id);

    //     // Xóa hết ảnh liên quan
    //     foreach ($product->images as $img) {
    //         \Storage::disk('public')->delete($img->image);
    //         $img->delete();
    //     }

    //     // Xóa luôn sản phẩm
    //     $product->forceDelete();

    //     return response()->json([
    //         'message' => 'Đã xóa vĩnh viễn sản phẩm',
    //     ]);
    // }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    public function forceDestroy(string $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh viễn sản phẩm thành công']);
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore(string $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore(); // khôi phục lại

        return response()->json(['message' => 'Khôi phục sản phẩm thành công']);
    }

    /**
     * Lấy danh sách sản phẩm mới nhất (có tồn kho + khuyến mãi hợp lệ).
     */
    public function product_new(Request $request)
    {
        try {
            // Validate limit (chỉ cho phép số nguyên, tối đa 1000)
            $limit = (int) $request->get('limit', 100);
            if ($limit <= 0 || $limit > 1000) {
                return response()->json([
                    'message' => 'Giá trị limit không hợp lệ. Chỉ cho phép từ 1 đến 1000'
                ], 400);
            }

            $now = now();

            // Tồn kho
            $productStore = ProductStore::query()
                ->select('product_id', DB::raw('SUM(qty) as total_qty'))
                ->groupBy('product_id');

            // Khuyến mãi
            $productSale = ProductSale::query()
                ->select('product_id', 'price_sale')
                ->where('date_begin', '<=', $now)
                ->where('date_end', '>', $now);

            // Lấy sản phẩm
            $products = Product::query()
                ->joinSub($productStore, 'ps', function ($j) {
                    $j->on('ps.product_id', '=', 'product.id')
                        ->where('ps.total_qty', '>', 0);
                })
                ->leftJoinSub($productSale, 'psale', function ($j) {
                    $j->on('psale.product_id', '=', 'product.id');
                })
                ->select(
                    'product.id',
                    'product.name',
                    'product.thumbnail',
                    'product.price_buy',
                    'psale.price_sale',
                    'ps.total_qty'
                )
                ->orderBy('product.created_at', 'desc')
                ->limit($limit)
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'message' => 'Không có sản phẩm mới nào',
                    'count'   => 0,
                    'data'    => []
                ], 200);
            }

            return response()->json([
                'message' => 'Danh sách sản phẩm mới',
                'count'   => $products->count(),
                'data'    => $products
            ], 200);

        } catch (\Exception $e) {
            // Bẫy mọi lỗi bất ngờ
            return response()->json([
                'message' => 'Lỗi khi lấy danh sách sản phẩm mới',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sản phẩm đang khuyến mãi.
     */
    public function product_sale(Request $request)
    {
        $limit = $request->get('limit', 100);
        $now = now();

        $productStore = ProductStore::query()
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id');

        $products = Product::query()
            ->joinSub($productStore, 'ps', function ($j) {
                $j->on('ps.product_id', '=', 'product.id')
                    ->where('ps.total_qty', '>', 0);
            })
            ->join('product_sale as psale', 'psale.product_id', '=', 'product.id')
            ->where('psale.date_begin', '<=', $now)
            ->where('psale.date_end', '>', $now)
            ->select(
                'product.id',
                'product.name',
                'product.thumbnail',
                'product.price_buy',
                'psale.price_sale',
                'ps.total_qty'
            )
            ->orderBy('product.created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'message' => 'Danh sách sản phẩm đang khuyến mãi',
            'count'   => $products->count(),
            'data'    => $products
        ]);
    }

    /**
     * Sản phẩm theo danh mục.
     */
    public function product_by_category(Request $request, $categoryId)
    {
        $limit = $request->get('limit', 100);
        $now = now();

        $productStore = ProductStore::query()
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id');

        $products = Product::query()
            ->joinSub($productStore, 'ps', function ($j) {
                $j->on('ps.product_id', '=', 'product.id')
                    ->where('ps.total_qty', '>', 0);
            })
            ->leftJoin('product_sale', function ($j) use ($now) {
                $j->on('product_sale.product_id', '=', 'product.id')
                ->where('product_sale.date_begin', '<=', $now)
                ->where('product_sale.date_end', '>=', $now);
            })
            ->where('product.category_id', $categoryId)
            ->select(
                'product.id',
                'product.name',
                'product.thumbnail',
                'product.price_buy',
                'product_sale.price_sale',
                'ps.total_qty'
            )
            ->orderBy('product.created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'message' => 'Danh sách sản phẩm theo danh mục',
            'count'   => $products->count(),
            'data'    => $products
        ]);
    }
        /**
     * Lấy tất cả sản phẩm.
     */
    public function product_all(Request $request)
    {
        $limit = (int) $request->get('limit', 8);

        // Tổng số lượng tồn kho theo product_id
        $productStore = ProductStore::query()
            ->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id');

        // Query sản phẩm
        $query = Product::query()
            ->joinSub($productStore, 'ps', function ($join) {
                $join->on('ps.product_id', '=', 'product.id')
                    ->where('ps.total_qty', '>', 0);
            })
            ->leftJoin('product_sale', function ($join) {
                $join->on('product_sale.product_id', '=', 'product.id');
            })
            ->select(
                'product.id',
                'product.name',
                'product.thumbnail',
                'product.price_buy',
                'product_sale.price_sale',
                'ps.total_qty'
            )
            ->orderBy('product.created_at', 'desc');

        if ($request->has('category_id')) {
            $query->where('product.category_id', $request->category_id);
        }

        $products = $query->paginate($limit);

        return response()->json($products);
    }

    public function getAttributes($id)
    {
        $attributes = ProductAttribute::where('product_id', $id)->get();
        return response()->json($attributes);
    }

    public function updateAttributes(Request $request, $id)
    {
        $attributes = $request->input('attributes', []);

        // Xóa thuộc tính cũ của sản phẩm
        ProductAttribute::where('product_id', $id)->delete();

        // Thêm thuộc tính mới
        foreach ($attributes as $attr) {
            ProductAttribute::create([
                'product_id' => $id,
                'name' => $attr['name'] ?? '',
                'value' => $attr['value'] ?? '',
            ]);
        }

        return response()->json(['message' => 'Cập nhật thuộc tính thành công']);
    }
    
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        $products = \App\Models\Product::select('id', 'name')
            ->where('name', 'like', "%{$keyword}%")
            ->limit(20)
            ->get();

        return response()->json($products);
    }
}
