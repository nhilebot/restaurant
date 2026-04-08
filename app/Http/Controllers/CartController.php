<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 1. Hiển thị trang Giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Lấy thông tin đặt bàn từ session (Ưu tiên format ngày giờ H:i)
        $reservation = session()->get('reservation_info', [
            'date' => date('d/m/Y H:i'), 
            'table' => 'Chưa chọn',
            'status' => 'chờ xác nhận',
            'name' => 'Khách vãng lai',
            'phone' => 'Chưa có',
            'notes' => ''
        ]);

        $total = 0;
        foreach($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        return view('cart.index', compact('cart', 'total', 'reservation'));
    }

    // 2. Thêm món vào giỏ hàng (AJAX) - Giữ nguyên phần Tâm đã vá lỗi ID
    public function addToCartAjax(Request $request)
    {
        $id = $request->input('menu_id');
        $quantity = $request->input('quantity', 1);
        
        // Cập nhật thông tin bàn ngay lúc thêm món
        session()->put('reservation_info', [
            'date' => $request->input('reservation_date', date('d/m/Y H:i')),
            'table' => $request->input('table_id', 'Chưa chọn'),
            'status' => 'Đang xử lý'
        ]);

        $menu = Menu::findOrFail($id);

        if ($menu->stock < $quantity) {
            return response()->json(['error' => 'Món ' . $menu->name . ' không đủ số lượng.'], 400);
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "id" => $id, // ID cực kỳ quan trọng để lưu vào chi tiết đơn hàng
                "name" => $menu->name,
                "quantity" => $quantity,
                "price" => $menu->price,
                "image" => $menu->image
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm món vào giỏ hàng!');
    }

    // 3. Xóa giỏ hàng
    public function clear()
    {
        session()->forget(['cart', 'reservation_info']);
        return redirect()->route('reservation.index')->with('success', 'Đã hủy giỏ hàng!');
    }

    // 4. Thanh toán: Nâng cấp lưu Ghi chú và Phương thức thanh toán
    public function checkout(Request $request)
    {
        // Nếu bạn muốn bắt buộc nhập tên/sdt thì dùng validate ở đây
        // $request->validate(['name' => 'required', 'phone' => 'required']);

        $cart = session()->get('cart', []);
        $reservation = session()->get('reservation_info');

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng trống!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        try {
            DB::transaction(function () use ($cart, $reservation, $total, $request) {
                // Tạo đơn hàng mới với đầy đủ thông tin Ghi chú & Thanh toán
                $order = Order::create([
                    'user_id'      => auth()->check() ? auth()->id() : null,
                    'table_number' => $reservation['table'] ?? 'Chưa chọn',
                    'total_price'  => $total,
                    'status'       => 'đang xử lý',
                    'name'         => $request->name ?? ($reservation['name'] ?? 'Khách vãng lai'),
                    'phone'        => $request->phone ?? ($reservation['phone'] ?? 'Chưa có'),
                    'address'      => $request->address ?? 'Tại nhà hàng',
                    'notes'        => $request->order_notes, // Hứng từ textarea name="order_notes"
                    'payment_method' => $request->payment_method // Hứng từ radio button
                ]);

                // Lưu chi tiết từng món vào bảng order_items
                foreach ($cart as $item) {
                    $order->orderItems()->create([
                        'product_id'   => $item['id'],
                        'product_name' => $item['name'],
                        'quantity'     => $item['quantity'],
                        'price'        => $item['price'],
                    ]);
                }
            });

            // Xóa sạch session sau khi chốt đơn thành công
            session()->forget(['cart', 'reservation_info']);
            return redirect()->route('reservation.index')->with('success', 'Đơn hàng của bạn đã được gửi thành công!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi lưu dữ liệu: ' . $e->getMessage());
        }
    }
}