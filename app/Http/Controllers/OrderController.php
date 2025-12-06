<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmMail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::orderBy('id','asc');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Nếu có lọc trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        // Phân trang (mặc định 10 mục/trang)
        $orders = $query->paginate(10);
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Lưu đơn hàng
            $order = Order::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone,
                'note' => $request->note,
                //'payment_method' => $request->payment_method,
                'status' => '0',// mới đặt
                'created_by' => $request->user_id,
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($request->cart_items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                    'amount' => $item['price'] * $item['quantity'],
                ]);
            }

            // Gửi email xác nhận
            //Mail::to($request->email)->send(new OrderConfirmMail($order));

            return response()->json([
                'status' => true,
                'message' => 'Đặt hàng thành công!',
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi khi lưu đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with(['details.product'])->findOrFail($id);

        return response()->json([
            'message' => 'Chi tiết đơn hàng',
            'data' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2', // 0=Đã đặt, 1=Đang giao, 2=Đã giao
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $order
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function orderHistory($userId)
    {
        $orders = Order::where('user_id', $userId)
            ->with('orderdetail.product') // lấy chi tiết sản phẩm trong đơn
            ->orderByDesc('created_at')
            ->get();

        return response()->json($orders);
    }
    public function getOrdersByUser($id)
    {
        $orders = Order::with('details')->where('user_id', $id)->get();

        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'total' => $order->total, // ✅ tự động tính bằng accessor getTotalAttribute()
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

}
