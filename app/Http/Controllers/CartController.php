<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 1. Hiển thị trang Giỏ hàng (Sửa lỗi hiển thị Bàn, Ngày, Trạng thái)
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Lấy thông tin đặt bàn từ session
        $reservation = session()->get('reservation_info', [
            'date' => date('d/m/Y'), 
            'table' => 'Chưa chọn',
            'status' => 'Đang chờ thanh toán',
            'name' => 'Khách vãng lai',
            'phone' => 'Chưa có',
            'notes' => ''
        ]);

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total', 'reservation'));
    }

    // 2. Thêm món vào giỏ hàng (AJAX) - Cập nhật thông tin bàn ngay lúc này
    public function addToCartAjax(Request $request)
    {
        $id = $request->input('menu_id');
        $quantity = $request->input('quantity', 1);
        
        // Lưu thông tin bàn/ngày vào session để dùng cho trang giỏ hàng
        session()->put('reservation_info', [
            'date' => $request->input('reservation_date', date('d/m/Y')),
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
                "id" => $id, // ĐÂY LÀ DÒNG TÂM THÊM VÀO ĐỂ VÁ LỖI NÈ
                "name" => $menu->name,
                "quantity" => $quantity,
                "price" => $menu->price,
                "image" => $menu->image
            ];
        }

        session()->put('cart', $cart);

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return redirect()->back()->with('success', 'Đã thêm món vào giỏ hàng!');
    }

    // 3. Xóa giỏ hàng (Sửa lỗi Route cart.clear)
    public function clear()
    {
        session()->forget(['cart', 'reservation_info']);
        return redirect()->route('reservation.index')->with('success', 'Đã hủy giỏ hàng!');
    }

    // 4. Thanh toán: Đẩy dữ liệu từ Session vào Database
    // 4. Thanh toán: Đẩy dữ liệu từ Session vào Database (Tâm đã nâng cấp)
    public function checkout(Request $request)
    {
        // Bắt buộc khách phải nhập đủ thông tin mới cho qua
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required'
        ]);

        $cart = session()->get('cart', []);
        $reservation = session()->get('reservation_info');

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng trống!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            DB::transaction(function () use ($cart, $reservation, $total, $request) {
                // Lưu vào bảng orders kèm thông tin Tâm vừa thêm
                $order = Order::create([
                    'user_id'      => auth()->check() ? auth()->id() : null,
                    'table_number' => $reservation['table'] ?? 'Chưa chọn',
                    'total_price'  => $total,
                    'status'       => 'đã thanh toán',
                    'name'         => $request->name,     // Hứng Tên
                    'phone'        => $request->phone,    // Hứng Số điện thoại
                    'address'      => $request->address   // Hứng Địa chỉ
                ]);

                // Lưu chi tiết từng món
                foreach ($cart as $item) {
                    $order->orderItems()->create([
                        'product_id'   => $item['id'],
                        'product_name' => $item['name'],
                        'quantity'     => $item['quantity'],
                        'price'        => $item['price'],
                    ]);
                }
            });

            session()->forget(['cart', 'reservation_info']);
            return redirect()->route('reservation.index')->with('success', 'Chốt đơn thành công! Cảm ơn bạn.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi lưu dữ liệu: ' . $e->getMessage());
        }
        
    } // <--- Tâm gõ thêm dấu này để đóng hàm checkout() lại

} // <--- Tâm gõ thêm dấu này để đóng toàn bộ file CartController lại