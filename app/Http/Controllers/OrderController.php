<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Tạo đơn hàng trong bảng orders
            $order = Order::create([
                'user_id' => Auth::id(),
                'table_number' => $request->table_number ?? 'Mang về', 
                'total_price' => $request->total_price,
                'status' => 'pending',
                'name' => Auth::user()->name,
                'phone' => Auth::user()->phone,
                // Nếu bảng có cột address thì Nhi có thể thêm vào đây
            ]);

            // 2. Lưu từng món ăn vào bảng order_items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id'  => $item['id'], // Quan trọng: Phải lưu menu_id để sau này truy xuất ảnh
                    'product_name' => $item['name'], 
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã chuyển đơn hàng vào hệ thống!']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Hiển thị lịch sử đơn hàng
    public function history()
    {
        $orders = Order::with(['items.menu']) // Load thêm quan hệ menu để lấy hình ảnh
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.history', compact('orders'));
    }

    // Xem chi tiết một đơn hàng cụ thể (nếu Nhi cần)
    public function show($id)
    {
        $order = Order::with(['items.menu'])->where('user_id', Auth::id())->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}