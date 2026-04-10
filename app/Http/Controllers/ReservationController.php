<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị trang đặt bàn
     */
    public function index(Request $request)
{
    $menus = Menu::all();
    $today = now()->toDateString(); 

    // Lấy danh sách ID bàn đã được đặt trong ngày hôm nay
    $bookedTableIds = Reservation::whereDate('reservation_date', $today)
                        ->whereIn('status', ['confirmed', 'pending']) // Lấy cả đơn đang chờ và đã xác nhận
                        ->pluck('table_id')
                        ->toArray();

    // Đồng bộ giỏ hàng từ Session hoặc DB (giữ nguyên logic cũ của bạn)
    $cart = session()->get('cart', []);
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return view('reservation', compact('menus', 'cart', 'total', 'bookedTableIds'));
}

    /**
     * Lưu thông tin đặt bàn và món ăn
     */
 public function store(Request $request)
{
    // 1. Kiểm tra dữ liệu đầu vào
    $request->validate([
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'table_id'         => 'required',
        'full_name'        => 'required',
        'phone'            => 'required',
    ]);

    $userId = auth()->id();

    // 2. QUAN TRỌNG: Lấy toàn bộ món ăn đang có trong giỏ (bao gồm cả Cua hấp, Mực, Sò)
    $cart = session()->get('cart', []);

    // 3. Lưu tất cả vào Database
    $reservation = \App\Models\Reservation::updateOrCreate(
        [
            'user_id' => $userId,
            'status'  => 'pending' 
        ],
        [
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'table_id'         => $request->table_id,
            'full_name'        => $request->full_name,
            'phone'            => $request->phone,
            'notes'            => $request->notes,
            'cart_data'        => $cart, // Đưa hết món ăn vào "vali" mang sang trang Giỏ hàng
        ]
    );

    // 4. Cập nhật lại Session để trang Giỏ hàng đồng bộ hoàn toàn
    session()->put('cart', $cart);

    return redirect()->route('cart.index')->with('success', 'Đã cập nhật đơn hàng thành công!');
}
public function addToCartAjax(Request $request)
{
    $cart = session()->get('cart', []);
    $id = $request->id;

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "id"       => $id,
            "name"     => $request->name,
            "quantity" => 1,
            "price"    => $request->price,
            "image"    => $request->image
        ];
    }

    session()->put('cart', $cart);
    
    // Trả về số lượng để cập nhật icon giỏ hàng nếu cần
    return response()->json([
        'success' => true, 
        'cartCount' => count($cart)
    ]);
}
}