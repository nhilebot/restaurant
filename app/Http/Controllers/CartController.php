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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để đặt món!');
        }

        $foodId = $request->input('food_id');
        $quantity = $request->input('quantity', 1);

        $menu = Menu::find($foodId);
        if (!$menu) {
            return redirect()->back()->with('error', 'Món ăn không tồn tại!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$foodId])) {
            $cart[$foodId]['quantity'] += $quantity;
        } else {
            $cart[$foodId] = [
                "id"       => $menu->id,
                "name"     => $menu->name,
                "quantity" => (int)$quantity,
                "price"    => $menu->price,
                "image"    => $menu->image
            ];
        }

        session()->put('cart', $cart);

        // LƯU DỰ PHÒNG VÀO DATABASE VÀ BÁO CHO TRANG ĐẶT BÀN
        $latestReservation = Reservation::where('user_id', Auth::id())->latest()->first();
        if ($latestReservation) {
            // Lưu món ăn vào database
            $latestReservation->update(['cart_data' => $cart]);
            
            // BƠM LẠI SESSION ĐỂ TRANG ĐẶT BÀN HIỂN THỊ "ĐÃ ĐẶT TRƯỚC"
            session()->put('reservation_info', [
                'table'  => $latestReservation->table_id,
                'date'   => $latestReservation->reservation_date,
                'time'   => $latestReservation->reservation_time,
                'name'   => $latestReservation->full_name,
                'status' => 'Đang xử lý'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm món ăn vào giỏ hàng!');
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
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống!');
        }

        try {
            DB::transaction(function () use ($cart, $request) {
                // Lấy đơn đặt bàn chuẩn nhất
                $resInfo = Reservation::where('user_id', Auth::id())->latest()->first();
                
                $totalAmount = 0;
                foreach($cart as $item) {
                    $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                }

                // Lưu Order
                $order = Order::create([
                    'user_id'        => Auth::id(), 
                    'table_number'   => $resInfo ? $resInfo->table_id : 'Mang về',
                    'total_price'    => $totalAmount,
                    'status'         => 'Chờ xác nhận',
                ]);

                // Lưu chi tiết từng món
                foreach ($cart as $id => $item) {
                    $order->orderItems()->create([
                        'product_id'  => $item['id'], // Hoặc thay bằng 'menu_id' nếu bảng DB của Nhi đặt tên vậy
                        'product_name'=> $item['name'], // Hoặc bỏ nếu bảng order_items không có cột này
                        'quantity'    => $item['quantity'],
                        'price'       => $item['price'],
                    ]);
                }

                // Chốt đơn xong thì cập nhật Reservation thành hoàn tất
                if ($resInfo) {
                    $resInfo->update(['status' => 'completed']);
                }
            });

            // Thanh toán xong thì dọn sạch giỏ hàng
            session()->forget(['cart']);

            return redirect('/')->with('success', '🎉 Chốt đơn thành công! Nhà hàng đã nhận thông tin đặt bàn của bạn.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi hệ thống khi lưu đơn: ' . $e->getMessage());
        }
    }
}