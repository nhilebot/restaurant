<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // HIỂN THỊ TRANG ĐẶT MÓN
    public function index()
    {
        $menus = Menu::all();

        $cart = session()->get('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('reservation', compact('menus', 'cart', 'total'));
    }

    // LƯU ĐẶT BÀN + MÓN
    public function store(Request $request)
{
    $request->validate([
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'table_id'         => 'required',
        'notes'            => 'nullable|string|max:255', // ✅ thêm lại ghi chú
    ]);

    $reservationInfo = [
        'date'   => $request->reservation_date . ' ' . $request->reservation_time,
        'table'  => $request->table_id,
        'status' => 'Chờ xác nhận',
        'notes'  => $request->notes ?? '', // ✅ lưu ghi chú
    ];

    session()->put('reservation_info', $reservationInfo);

    // xử lý cart giữ nguyên
    if ($request->has('foods') && is_array($request->foods)) {

        $cart = [];

        foreach ($request->foods as $id => $item) {

            if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                continue;
            }

            $menu = Menu::find($id);
            if (!$menu) continue;

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
        ->with('success', 'Đã lưu đơn hàng.');
}
}