<?php

namespace App\Http\Controllers;

use App\Models\ProductSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductSaleImport;
use App\Exports\ProductSaleTemplateExport;

class ProductSaleController extends Controller
{
    // Danh sách khuyến mãi (tìm kiếm + lọc + phân trang)
    public function index(Request $request)
    {
        $query = ProductSale::with(['product:id,name,thumbnail,price_buy']);

        // Tìm kiếm theo tên sản phẩm
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            });
        }

        // Lọc trạng thái (1: hoạt động, 0: ngừng)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->orderBy('id', 'desc')->paginate(5);
        return response()->json($sales);
    }

    // Tạo khuyến mãi mới (thủ công)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:product,id',
            'price_sale' => 'required|numeric|min:0',
            'date_begin' => 'required|date',
            'date_end' => 'required|date|after:date_begin',
            'status' => 'required|in:0,1',
        ], [
            'date_end.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        //  Kiểm tra trùng khoảng thời gian sale cho cùng sản phẩm
        $productId = $request->product_id;
        $begin = $request->date_begin;
        $end = $request->date_end;

        $overlap = ProductSale::where('product_id', $productId)
            ->where(function ($q) use ($begin, $end) {
                $q->whereBetween('date_begin', [$begin, $end])
                ->orWhereBetween('date_end', [$begin, $end])
                ->orWhere(function ($q2) use ($begin, $end) {
                    $q2->where('date_begin', '<=', $begin)
                        ->where('date_end', '>=', $end);
                });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'date_begin' => [
                        'Khoảng thời gian khuyến mãi của sản phẩm này bị trùng với đợt khuyến mãi trước đó.'
                    ]
                ]
            ], 422);
        }

        //  Nếu hợp lệ → lưu lại
        $sale = ProductSale::create([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'price_sale' => $request->price_sale,
            'date_begin' => $begin,
            'date_end' => $end,
            'status' => $request->status,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        return response()->json([
            'message' => 'Thêm khuyến mãi thành công!',
            'data' => $sale
        ], 201);
    }


    // Xóa khuyến mãi
    public function destroy($id)
    {
        $sale = ProductSale::find($id);

        if (!$sale) {
            return response()->json([
                'message' => 'Không tìm thấy khuyến mãi cần xóa.'
            ], 404);
        }

        try {
            $sale->forceDelete();
            return response()->json([
                'message' => 'Xóa khuyến mãi thành công!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi xóa khuyến mãi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Import file Excel
    public function import(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'Không có file tải lên'], 400);
        }

        try {
            Excel::import(new ProductSaleImport, $request->file('file'));
            return response()->json(['message' => 'Import dữ liệu thành công!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi import: ' . $e->getMessage()], 500);
        }
    }
    public function exportTemplate()
    {
        return Excel::download(new ProductSaleTemplateExport, 'product_sale_template.xlsx');
    }

    // Cập nhật khuyến mãi
    public function update(Request $request, $id)
    {
        $sale = ProductSale::find($id);

        if (!$sale) {
            return response()->json([
                'message' => 'Không tìm thấy khuyến mãi cần sửa.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'required|exists:product,id',
            'price_sale' => 'required|numeric|min:0',
            'date_begin' => 'required|date',
            'date_end' => 'required|date|after:date_begin',
            'status' => 'required|in:0,1',
        ], [
            'date_end.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);

        $productId = $request->product_id;
        $begin = $request->date_begin;
        $end = $request->date_end;

        // Kiểm tra trùng thời gian khuyến mãi cho cùng sản phẩm (bỏ qua chính nó)
        $overlap = ProductSale::where('product_id', $productId)
            ->where('id', '!=', $id)
            ->where(function ($q) use ($begin, $end) {
                $q->whereBetween('date_begin', [$begin, $end])
                    ->orWhereBetween('date_end', [$begin, $end])
                    ->orWhere(function ($q2) use ($begin, $end) {
                        $q2->where('date_begin', '<=', $begin)
                            ->where('date_end', '>=', $end);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'date_begin' => [
                        'Khoảng thời gian khuyến mãi của sản phẩm này bị trùng với đợt khuyến mãi khác.'
                    ]
                ]
            ], 422);
        }

        // ✅ Cập nhật khuyến mãi
        $sale->update([
            'name' => $request->name,
            'product_id' => $request->product_id,
            'price_sale' => $request->price_sale,
            'date_begin' => $begin,
            'date_end' => $end,
            'status' => $request->status,
            'updated_by' => 1,
        ]);

        return response()->json([
            'message' => 'Cập nhật khuyến mãi thành công!',
            'data' => $sale
        ], 200);
    }
    public function show($id)
    {
        $sale = ProductSale::with('product:id,name,thumbnail,price_buy')->find($id);

        if (!$sale) {
            return response()->json(['message' => 'Không tìm thấy khuyến mãi.'], 404);
        }

        return response()->json($sale);
    }
}
