<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    $reservationDate = $latestReservation && $latestReservation->reservation_date
        ? Carbon::parse($latestReservation->reservation_date)->format('d/m/Y')
        : date('d/m/Y');

    $reservationTime = '--:--';
    if ($latestReservation && $latestReservation->reservation_time) {
        try {
            $reservationTime = Carbon::parse($latestReservation->reservation_time)->format('H:i');
        } catch (\Exception $e) {
            $reservationTime = $latestReservation->reservation_time;
        }
    }

    $reservation = [
        'date'   => $reservationDate,
        'table'  => ($latestReservation && $latestReservation->table_id) ? $latestReservation->table_id : 'Chưa chọn',
        'status' => ($latestReservation && $latestReservation->status == 'confirmed') ? 'Đang xử lý' : 'Chờ xác nhận',
        'name'   => $latestReservation ? $latestReservation->full_name : (auth()->user()->name ?? 'Khách'),
        'time'   => $reservationTime,
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
public function remove($id)
{
    $userId = auth()->id();

    // Xóa món ăn cụ thể trong Database của User
    \App\Models\Cart::where('user_id', $userId)
                ->where('menu_id', $id)
                ->delete();

    // Quan trọng: Quay lại trang hiện tại (Giỏ hàng) kèm thông báo
    return redirect()->back()->with('success', 'Đã xóa món ăn khỏi giỏ hàng!');
}
public function checkout(Request $request)
{
    $userId = auth()->id();

    // ✅ Lấy giỏ hàng từ DATABASE (KHÔNG dùng session nữa)
    $cartItems = \App\Models\Cart::with('menu')
        ->where('user_id', $userId)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống!');
    }

    // ✅ Tính tổng tiền
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += ($item->menu->price ?? 0) * $item->quantity;
    }

    $pm = $request->input('payment_method');

    // ✅ Tạo Order
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

    // ✅ Lưu OrderItem từ DB cart
    foreach ($cartItems as $item) {
        \App\Models\OrderItem::create([
            'order_id'     => $order->id,
            'menu_id'      => $item->menu_id,
            'quantity'     => $item->quantity,
            'price'        => $item->menu->price ?? 0,
            'product_name' => $item->menu->name ?? '',
        ]);
    }

    // ✅ XÓA GIỎ HÀNG DB
    \App\Models\Cart::where('user_id', $userId)->delete();

    // (optional) xóa session nếu còn
    session()->forget('cart');

    return redirect()->route('orders.history')->with('success', 'Thanh toán thành công!');
}
public function history()
{
    // Lấy danh sách đơn hàng của người dùng đang đăng nhập
    // Sắp xếp theo thời gian mới nhất
    $orders = \App\Models\Order::where('user_id', auth()->id())->get();
    return view('cart.history', compact('orders')); // Dấu chấm đại diện cho thư mục cart/
}
}
