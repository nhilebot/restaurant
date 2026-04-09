<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class ReservationController extends Controller
{
    public function __construct()
    {
        // Yêu cầu đăng nhập (nếu đồ án ný bắt buộc), không thì ný có thể rào dòng này lại
        $this->middleware('auth');
    }

    public function index()
    {
        $menus = \App\Models\Menu::all(); 
        $cart = session()->get('cart', []);
        
        $total = 0;
        foreach($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        
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
            'notes'            => 'nullable|string|max:255',
        ]);

        // Gom hết thông tin khách vào 1 hộp lớn
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
                    'quantity' => $item['quantity'] ?? 1,
                    'image'    => $menu->image,
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
                         ->with('success', 'Thông tin đặt bàn đã được lưu. Vui lòng kiểm tra lại đơn hàng.');
    }
}