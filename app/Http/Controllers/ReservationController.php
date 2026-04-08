<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class ReservationController extends Controller
{
    /**
     * Chỉ cho phép người đã đăng nhập truy cập các hàm trong này
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

  public function index()
{
    // 1. Lấy danh sách thực đơn từ Database (Sửa lỗi Undefined variable $menus)
    $menus = \App\Models\Menu::all(); // Thay bằng Model thực tế của bạn

    // 2. Lấy giỏ hàng hiện tại từ session (Sửa lỗi Undefined variable $cart)
    $cart = session()->get('cart', []);

    // 3. Tính tổng tiền cho phần hiển thị "Món ăn đã chọn"
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // 4. Truyền tất cả biến sang View
    return view('reservation', compact('menus', 'cart', 'total'));
}

    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required',
            'reservation_time' => 'required',
            'full_name'        => 'required',
            'phone'            => 'required',
            'table_id'         => 'required',
        ]);

        $reservationInfo = [
            'date'   => $request->reservation_date . ' ' . $request->reservation_time,
            'table'  => $request->table_id,
            'status' => 'Chờ xác nhận',
            'name'   => $request->full_name,
            'phone'  => $request->phone,
            'notes'  => $request->notes ?? '',
        ];

        session()->put('reservation_info', $reservationInfo);

        if ($request->has('foods') && is_array($request->foods)) {
            $cart = session()->get('cart', []);

            foreach ($request->foods as $id => $item) {
                $menu = Menu::find($id);
                if (!$menu) {
                    continue;
                }

                $cart[$id] = [
                    'id'       => $menu->id,
                    'name'     => $menu->name,
                    'price'    => $menu->price,
                    'quantity' => $item['quantity'],
                    'image'    => $menu->image,
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
                         ->with('success', 'Thông tin đặt bàn đã được lưu. Vui lòng kiểm tra lại đơn hàng.');
    }
}