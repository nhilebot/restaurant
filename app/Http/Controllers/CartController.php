<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * 1. HIỂN THỊ TRANG GIỎ HÀNG & BILL THANH TOÁN
     */
public function index()
{
    // dd(\App\Models\Cart::where('user_id', auth()->id())->get());
    $userId = auth()->id();
    
    // 1. LẤY GIỎ HÀNG TỪ DATABASE
    $dbCart = \App\Models\Cart::with('menu')
                ->where('user_id', $userId)
                ->get();

    // Chuyển đổi format để đồng nhất với giao diện của Nhi
    $cart = $dbCart->map(function($item) {
        return [
            'id'       => $item->menu_id,
            'name'     => $item->menu->name ?? 'Món đã bị xóa',
            'price'    => $item->menu->price ?? 0,
            'quantity' => $item->quantity,
            'image'    => $item->menu->image ?? '',
        ];
    })->toArray();

    // 2. Lấy thông tin đặt bàn mới nhất (để hiện lên thông tin người đặt)
    $latestReservation = \App\Models\Reservation::where('user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->first();

    $reservation = [
        'date'   => $latestReservation ? $latestReservation->reservation_date : date('d/m/Y'),
        'table'  => ($latestReservation && $latestReservation->table_id) ? $latestReservation->table_id : 'Chưa chọn',
        'status' => ($latestReservation && $latestReservation->status == 'confirmed') ? 'Đang xử lý' : 'Chờ xác nhận',
        'name'   => $latestReservation ? $latestReservation->full_name : (auth()->user()->name ?? 'Khách'),
        'time'   => $latestReservation ? $latestReservation->reservation_time : '--:--', 
        'notes'  => $latestReservation ? $latestReservation->notes : '', 
    ];

    // 3. Tính tổng tiền
    $total = 0;
    foreach($cart as $item) {
        $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
    }

    // Trả dữ liệu ra view
    return view('cart.index', compact('cart', 'total', 'reservation', 'latestReservation'));
}

    /**
     * 2. THÊM MÓN VÀO GIỎ HÀNG
     */
    /**
     * 2. THÊM MÓN VÀO GIỎ HÀNG
     */
public function addToCart(Request $request)
{
    if (!auth()->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập!'
        ], 401);
    }

    $userId = auth()->id();
    $foodId = $request->menu_id; // Đảm bảo frontend gửi đúng tên trường này
    $quantity = (int)$request->input('quantity', 1);

    if (!$foodId) {
        return response()->json([
            'success' => false,
            'message' => 'Thiếu menu_id'
        ]);
    }

    $menu = \App\Models\Menu::find($foodId);

    if (!$menu) {
        return response()->json([
            'success' => false,
            'message' => 'Món không tồn tại'
        ]);
    }

    $cartItem = \App\Models\Cart::where('user_id', $userId)
        ->where('menu_id', $foodId)
        ->first();

    if ($cartItem) {
        $cartItem->quantity += $quantity;
        $cartItem->save();
    } else {
        \App\Models\Cart::create([
            'user_id' => $userId,
            'menu_id' => $foodId,
            'quantity' => $quantity
        ]);
    }

    // 🔥 QUAN TRỌNG: trả về tổng số lượng
    $count = \App\Models\Cart::where('user_id', $userId)
        ->sum('quantity');

    return response()->json([
        'success' => true,
        'message' => 'Đã thêm vào giỏ hàng',
        'count' => $count
    ]);
}
public function getCart()
{
    $userId = auth()->id();

    $cart = Cart::with('menu')
        ->where('user_id', $userId)
        ->get();

    return response()->json([
        'success' => true,
        'cart' => $cart->map(function ($item) {
            return [
                'id' => $item->menu->id,
                'name' => $item->menu->name,
                'price' => $item->menu->price,
                'image' => $item->menu->image,
                'quantity' => $item->quantity
            ];
        })
    ]);
}
    /**
     * 3. XÓA SẠCH GIỎ HÀNG
     */
    public function clear()
    {
        if (Auth::check()) {
            // Xóa dự phòng trong DB nếu có
            $latestRes = Reservation::where('user_id', Auth::id())->latest()->first();
            if ($latestRes) {
                $latestRes->update(['cart_data' => null]);
            }
        }
        
        session()->forget(['cart']);
        return redirect()->route('reservation.index')->with('success', 'Đã xóa sạch giỏ hàng!');
    }

    /**
     * 4. THANH TOÁN (Xử lý chốt đơn cuối cùng)
     */
/**
     * 4. THANH TOÁN (Xử lý chốt đơn cuối cùng)
     */public function showCheckout()
{
    // 1. Lấy giỏ hàng từ Session ra (với key mà Nhi đã dùng lúc 'Add to cart')
    $cart = session()->get('cart', []);

    // 2. Tính tổng tiền
    $total = 0;
    foreach($cart as $item) {
        $total += ($item['price'] ?? 0) * $item['quantity'];
    }

    // 3. Truyền biến $cart và $total sang View
    return view('checkout', compact('cart', 'total'));
}
public function removeItem($id)
{
    $userId = auth()->id();

    // Tìm món đó trong giỏ của đúng User này và xóa
    \App\Models\Cart::where('user_id', $userId)
                    ->where('menu_id', $id)
                    ->delete();

    // THÊM DÒNG NÀY: Xóa thêm giỏ hàng trong Session (nếu Nhi có dùng session để hiện badge số lượng)
    session()->forget('cart');
    return redirect()->route('order-history') 
        ->with('success', 'Đơn hàng của bạn đã được gửi đi thành công!');
}
public function checkout(Request $request)
{
    $userId = auth()->id();

    // 1. Lấy giỏ hàng từ Session (giống như cách Nhi hiển thị ở Blade)
    $cart = session()->get('cart', []);

    // Kiểm tra nếu giỏ hàng session rỗng
    if (empty($cart)) {
        return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    // 2. Tính tổng tiền từ session
    $totalAmount = 0;
    foreach ($cart as $item) {
        $totalAmount += ($item['price'] ?? 0) * $item['quantity'];
    }

    $pm = $request->input('payment_method');

    // 3. Tạo đơn hàng (Giữ nguyên logic của Nhi)
    $order = \App\Models\Order::create([
        'user_id'        => $userId,
        'total_price'    => $totalAmount,
        'status'         => ($pm == 'BANK') ? 'paid' : 'pending',
        'payment_method' => $pm,
        'table_number'   => $request->table_number,
        'name'           => auth()->user()->name,
        'phone'          => auth()->user()->phone,
        'notes'          => $request->order_notes ?? null,
    ]);

    // 4. Lưu từng món từ Session vào OrderItem
    foreach ($cart as $item) {
        \App\Models\OrderItem::create([
            'order_id'     => $order->id,
            'menu_id'      => $item['id'], // Dùng ID từ session
            'quantity'     => $item['quantity'],
            'price'        => $item['price'] ?? 0,
            'product_name' => $item['name'] ?? '',
        ]);
    }

    // 5. XÓA GIỎ HÀNG TRONG SESSION (Để giỏ hàng trống sau khi mua)
    session()->forget('cart');

    // Nếu Nhi có lưu cả trong Database thì xóa luôn (dòng này giữ lại nếu cần)
    \App\Models\Cart::where('user_id', $userId)->delete();

    return redirect()->route('orders.history')->with('success', 'Thanh toán thành công!');
}
}
