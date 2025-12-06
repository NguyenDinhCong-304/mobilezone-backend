<?php

namespace App\Http\Controllers;

use App\Models\ProductStore;
use Illuminate\Http\Request;

class ProductStoreController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductStore::with(['product' => function ($q) {
            $q->select('id', 'name', 'thumbnail', 'price_buy');
        }]);

        // Tìm kiếm theo tên sản phẩm
        if ($request->has('search') && !empty($request->search)) {
            $keyword = $request->search;
            $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $stores = $query->orderBy('id', 'desc')->paginate(8);

        return response()->json($stores);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:product,id',
            'price_root' => 'required|numeric|min:0',
            'qty'        => 'required|integer|min:1',
            'status'     => 'required|in:0,1',
        ]);

        $data['created_by'] = auth()->id() ?? 1; // hoặc lấy từ user login
        $data['updated_by'] = auth()->id() ?? 1;

        $store = ProductStore::create($data);

        return response()->json([
            'message' => 'Nhập kho thành công',
            'data' => $store->load('product:id,name,thumbnail'),
        ]);
    }
    
    public function update(Request $request, $id)
    {
        // Tìm bản ghi nhập kho cần sửa
        $store = ProductStore::find($id);

        if (!$store) {
            return response()->json([
                'error' => 'Không tìm thấy bản ghi nhập kho này.'
            ], 404);
        }

        // Validate dữ liệu đầu vào
        $data = $request->validate([
            'product_id' => 'required|exists:product,id',
            'price_root' => 'required|numeric|min:0',
            'qty'        => 'required|integer|min:1',
            'status'     => 'required|in:0,1',
        ]);

        // Cập nhật thông tin
        $data['updated_by'] = auth()->id() ?? 1;

        $store->update($data);

        return response()->json([
            'message' => 'Cập nhật nhập kho thành công',
            'data' => $store->load('product:id,name,thumbnail'),
        ]);
    }

    public function destroy($id)
    {
        $store = ProductStore::find($id);

        if (!$store) {
            return response()->json([
                'error' => 'Không tìm thấy bản ghi nhập kho này.'
            ], 404);
        }

        // Nếu bạn muốn xóa mềm (soft delete), cần thêm SoftDeletes vào model
        $store->forceDelete();

        return response()->json([
            'message' => 'Xóa nhập kho thành công.'
        ], 200);
    }
    public function show($id)
    {
        $store = ProductStore::with(['product:id,name,thumbnail,price_buy'])->find($id);

        if (!$store) {
            return response()->json(['error' => 'Không tìm thấy bản ghi nhập kho.'], 404);
        }

        return response()->json($store);
    }
}
