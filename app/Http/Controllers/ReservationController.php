<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Thêm DB để dùng Transaction

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
        // 1. Validate dữ liệu
        $request->validate([
            'table_number' => 'required|string',
            'menu_items'   => 'required|array|min:1',
        ]);

        // 2. Lọc các món được chọn hợp lệ
        $selectedItems = collect($request->menu_items)->filter(function ($item) {
            return isset($item['selected']) && $item['selected'] == '1' 
                && isset($item['quantity']) && $item['quantity'] > 0;
        });

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['menu_items' => 'Vui lòng chọn ít nhất một món.']);
        }

        // 3. Sử dụng Transaction để đảm bảo an toàn dữ liệu (nếu lỗi thì không lưu gì cả)
        try {
            return DB::transaction(function () use ($selectedItems, $request) {
                $totalPrice = 0;
                $tempOrderItems = [];

                foreach ($selectedItems as $item) {
                    $menu = Menu::find($item['id']);

                    if (!$menu) {
                        throw new \Exception("Món ăn không tồn tại.");
                    }

                    if ($menu->stock < $item['quantity']) {
                        throw new \Exception("Món " . $menu->name . " hiện không đủ số lượng.");
                    }

                    $price = $menu->price;
                    $subTotal = $price * $item['quantity'];
                    $totalPrice += $subTotal;

                    $tempOrderItems[] = [
                        'menu_id'  => $menu->id,
                        'quantity' => $item['quantity'],
                        'price'    => $price,
                    ];

                    // Giảm tồn kho ngay
                    $menu->decrement('stock', $item['quantity']);
                }

                // Tạo đơn hàng
                $order = Order::create([
                    'user_id'      => Auth::id(),
                    'table_number' => $request->table_number,
                    'total_price'  => $totalPrice,
                    'status'       => 'pending',
                    'note'         => $request->note, // Lưu thêm ghi chú nếu có
                ]);

                // Lưu chi tiết đơn hàng
                foreach ($tempOrderItems as $detail) {
                    $order->orderItems()->create($detail);
                }

                return redirect()->route('reservation.index')
                                 ->with('success', 'Đặt bàn và chọn món thành công!');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}