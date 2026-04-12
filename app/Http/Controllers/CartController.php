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
        $userId = auth()->id();
        $cart = session()->get('cart', []);

        // 1. Tìm đơn đặt bàn mới nhất của User
        $latestReservation = \App\Models\Reservation::where('user_id', $userId)
                                ->orderBy('created_at', 'desc')
                                ->first();

        // 2. Chuẩn bị thông tin đẩy ra View (Bổ sung time và notes để hết lỗi)
        $reservation = [
            'date'   => $latestReservation ? $latestReservation->reservation_date : date('d/m/Y'),
            'table'  => ($latestReservation && $latestReservation->table_id) ? $latestReservation->table_id : 'Chưa chọn',
            'status' => ($latestReservation && $latestReservation->status == 'confirmed') ? 'Đang xử lý' : 'Chờ xác nhận',
            'name'   => $latestReservation ? $latestReservation->full_name : 'Khách',
            'time'   => $latestReservation ? $latestReservation->reservation_time : '--:--', 
            'notes'  => $latestReservation ? $latestReservation->notes : '', 
        ];

        $total = 0;
        foreach($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

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
        return response()->json(['error' => 'Bạn cần đăng nhập để đặt món!'], 401);
    }

    $foodId = $request->input('food_id');
    $quantity = (int)$request->input('quantity', 1);

    $menu = \App\Models\Menu::find($foodId);
    if (!$menu) {
        return response()->json(['error' => 'Món ăn không tồn tại!'], 404);
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$foodId])) {
        $cart[$foodId]['quantity'] += $quantity;
    } else {
        $cart[$foodId] = [
            "id"       => $menu->id,
            "name"     => $menu->name,
            "quantity" => $quantity,
            "price"    => $menu->price,
            "image"    => $menu->image
        ];
    }

    session()->put('cart', $cart);
    session()->save(); // Lưu lại session ngay lập tức

    return response()->json([
        'message' => 'Món ' . $menu->name . ' đã được thêm.',
        'cart_count' => count($cart)
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
   public function checkout()
{
    $userId = auth()->id();

    // 🔥 CHUYỂN pending → confirmed
    Reservation::where('user_id', $userId)
        ->where('status', 'pending')
        ->update([
            'status' => 'confirmed'
        ]);

    session()->forget('cart');

    return redirect()->route('reservation.index')
        ->with('success', 'Thanh toán thành công!');
}
}