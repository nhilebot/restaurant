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
    $menus = \App\Models\Menu::select('id','name','price','image','stock','category')->get();

    $bookedTableIds = \App\Models\Reservation::select('table_id')
    ->get()
    ->pluck('table_id')
    ->toArray();

    $dbCart = Cart::with('menu')
    ->where('user_id', Auth::id())
    ->get();

$cart = [];

foreach ($dbCart as $item) {
    if (!$item->menu) continue;

    $cart[] = [
        'id' => $item->menu_id,
        'name' => $item->menu->name,
        'price' => $item->menu->price,
        'quantity' => $item->quantity,
        'image' => $item->menu->image,
    ];
}

    return view('reservation', compact('menus','bookedTableIds','cart'));
}
    /**
     * Lưu thông tin đặt bàn và món ăn
     */
public function store(Request $request)
{
    // 1. Lưu vào Database (Giữ nguyên logic cũ của Nhi)
    $reservation = new \App\Models\Reservation();
    $reservation->user_id = auth()->id();
    $reservation->full_name = $request->full_name;
    $reservation->phone = $request->phone;
    $reservation->reservation_date = $request->reservation_date;
    $reservation->reservation_time = $request->reservation_time;
    $reservation->table_id = $request->table_id;
    $reservation->notes = $request->notes; // Lưu vào DB
   $reservation->status = 'confirmed';
    $reservation->save();

    // 2. QUAN TRỌNG: Lưu vào Session để trang Cart có dữ liệu hiển thị
    session()->put('reservation', [
        'name'   => $request->full_name,
        'table'  => $request->table_id,
        'date'   => $request->reservation_date,
        'time'   => $request->reservation_time,
        'notes'  => $request->notes, // <-- Dòng này giúp hiện ghi chú bên Cart
        'status' => 'Đang chờ thanh toán'
    ]);

    // 3. Chuyển hướng sang trang giỏ hàng
    return redirect()->route('cart.index')->with('success', 'Đặt bàn thành công! Vui lòng kiểm tra lại hóa đơn.');
}
public function addToCartAjax(Request $request)
{
    $id = $request->menu_id ?? $request->food_id;

    $menu = \App\Models\Menu::find($id);
    if (!$menu) {
        return response()->json([
            'success' => false,
            'message' => 'Món ăn không tồn tại!'
        ]);
    }

    // Lưu vào DATABASE (KHÔNG dùng session nữa)
    $dbCartItem = \App\Models\Cart::where('user_id', auth()->id())
        ->where('menu_id', $id)
        ->first();

    if ($dbCartItem) {
        $dbCartItem->quantity += 1;
        $dbCartItem->save();
    } else {
        \App\Models\Cart::create([
            'user_id' => auth()->id(),
            'menu_id' => $id,
            'quantity' => 1,
        ]);
    }

    // Lấy lại toàn bộ cart từ DB
    $dbCart = \App\Models\Cart::with('menu')
        ->where('user_id', auth()->id())
        ->get();

    $cart = [];

    foreach ($dbCart as $item) {
        if (!$item->menu) continue;

        $cart[] = [
            'id' => $item->menu_id,
            'name' => $item->menu->name,
            'price' => $item->menu->price,
            'quantity' => $item->quantity,
            'image' => $item->menu->image,
        ];
    }

    return response()->json([
        'success' => true,
        'cartCount' => count($cart),
        'cartData' => $cart
    ]);
}

public function showCart() // Hoặc tên hàm nào Nhi dùng để mở trang thanh toán
{
    // PHẢI DÙNG ĐÚNG TÊN 'cart' giống như hàm addToCartAjax Nhi vừa gửi
    $cart = session()->get('cart', []); 

    // Tính tổng tiền dựa trên $cart vừa lấy ra
    $total = 0;
    foreach ($cart as $item) {
        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
    }

    // Lấy thông tin bàn/khách hàng (nếu có)
    $reservation = session()->get('reservation', [
        'name' => 'Khách hàng',
        'table' => 'Chưa chọn',
        'date' => date('Y-m-d'),
        'time' => date('H:i'),
        'status' => 'Đang xử lý',
        'notes' => ''
    ]);

    return view('cart.index', compact('cart', 'total', 'reservation'));
}
// lưu ghi chú
public function saveNoteAjax(Request $request)
{
    // Lấy thông tin reservation hiện tại từ session
    $reservation = session()->get('reservation', []);
    
    // Cập nhật chỉ riêng phần ghi chú
    $reservation['notes'] = $request->notes;
    
    // Lưu ngược lại vào session
    session()->put('reservation', $reservation);

    return response()->json(['success' => true]);
}
}