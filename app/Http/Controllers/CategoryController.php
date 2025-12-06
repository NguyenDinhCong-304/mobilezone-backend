<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::orderBy('sort_order', 'asc');

        // Nếu có tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Nếu có lọc trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        // Phân trang (mặc định 10 mục/trang)
        $categories = $query->paginate(10);

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Nếu frontend gửi parent_id = 0 thì chuyển về null
        if ($request->parent_id == 0) {
            $request->merge(['parent_id' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:category,id',
            'sort_order' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['created_by'] = auth()->id() ?? 1;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('category', 'public');
            $validated['image'] = $path;
        }

        $category = Category::create([
            'name'        => $validated['name'],
            'slug'        => $validated['slug'] ?? Str::slug($validated['name']),
            'parent_id'   => $validated['parent_id'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'description' => $validated['description'] ?? null,
            'status'      => $validated['status'] ?? 1,
            'created_by'  => $validated['created_by'],
            'image'       => $validated['image'] ?? null,
        ]);

        return response()->json([
            'message' => 'Tạo danh mục thành công',
            'data'    => $category
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        if ($request->parent_id == 0) {
            $request->merge(['parent_id' => null]);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:category,slug,' . $category->id,
            'status' => 'sometimes|required|integer',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'parent_id' => 'nullable|integer|exists:category,id',
            'image' => 'nullable|image|max:2048',
        ]);

        //Nếu update ảnh mới 
        if ($request->hasFile('image')) {
            // Nếu có ảnh cũ thì xóa trước
            if (!empty($category->image) && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Lưu ảnh mới
            $path = $request->file('image')->store('category', 'public');
            $validated['image'] = $path;
        } else {
            // Ngược lại giữ nguyên ảnh cũ
            unset($validated['image']);
        }

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['updated_by'] = auth()->id() ?? 1;

        $category->update($validated);
        $category->refresh();

        return response()->json([
            'message' => 'Cập nhật danh mục thành công',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $category->forceDelete();

        return response()->json(['message' => 'Xóa danh mục thành công']);
    }

     /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    // public function forceDestroy(string $id)
    // {
    //     $category = Category::withTrashed()->findOrFail($id);
    //     $category->forceDelete(); // xóa hẳn khỏi DB

    //     return response()->json(['message' => 'Xóa vĩnh viễn danh mục thành công']);
    // }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    // public function restore(string $id)
    // {
    //     $category = Category::withTrashed()->findOrFail($id);
    //     $category->restore(); // khôi phục lại

    //     return response()->json(['message' => 'Khôi phục danh mục thành công']);
    // }
}
