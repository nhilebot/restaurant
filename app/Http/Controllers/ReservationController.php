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
    public function index()
{
    $menus = \App\Models\Menu::select('id', 'name', 'price', 'image', 'stock', 'category')
        ->distinct()
        ->get();

    // 🔥 CHỈ khóa bàn đã đặt khi click thanh toán
    $bookedTableIds = \App\Models\Reservation::where('status', 'confirmed')
        ->pluck('table_id')
        ->toArray();

    return view('reservation', compact('menus', 'bookedTableIds'));
}
    /**
     * Lưu thông tin đặt bàn và món ăn
     */
 public function store(Request $request)
{
    $request->validate([
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'table_id' => 'required',
        'full_name' => 'required',
        'phone' => 'required',
    ]);

    $cart = session()->get('cart', []);

    \App\Models\Reservation::create([
        'user_id' => auth()->id(),
        'reservation_date' => $request->reservation_date,
        'reservation_time' => $request->reservation_time,
        'table_id' => $request->table_id,
        'full_name' => $request->full_name,
        'phone' => $request->phone,
        'notes' => $request->notes,
        'cart_data' => $cart,
        'status' => 'pending' // 🔥 QUAN TRỌNG
    ]);

   return redirect()->route('cart.index')
    ->with('success', 'Đặt bàn thành công!'); // 👉 chuyển qua giỏ hàng
}
public function addToCartAjax(Request $request)
{
    $cart = session()->get('cart', []);
    $id = $request->id;

    // Kiểm tra món ăn có tồn tại không
    $menu = \App\Models\Menu::find($id);
    if (!$menu) {
        return response()->json(['success' => false, 'message' => 'Món ăn không tồn tại!']);
    }

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            "id"       => $id,
            "name"     => $menu->name, // Lấy từ DB cho chắc chắn
            "quantity" => 1,
            "price"    => $menu->price,
            "image"    => $menu->image
        ];
    }

    session()->put('cart', $cart);
    session()->save(); // Ép Laravel lưu session ngay lập tức
    
    return response()->json([
        'success' => true, 
        'cartCount' => count($cart),
        'cartData' => array_values($cart) // Trả về mảng để JS cập nhật UI
    ]);
}
}