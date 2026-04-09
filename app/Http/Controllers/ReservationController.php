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
    // 1. Xóa hoặc comment dòng dd này để code có thể chạy tiếp xuống dưới
    // dd($request->all()); 

    // 2. Kiểm tra dữ liệu đầu vào
    $request->validate([
        'reservation_date' => 'required',
        'reservation_time' => 'required',
        'full_name'        => 'required',
        'phone'            => 'required',
        'table_id'         => 'required',
        'notes'            => 'nullable|string|max:255',
    ]);

    // 3. Lưu thông tin đặt bàn vào Session
    $reservationInfo = [
        'date'   => $request->reservation_date . ' ' . $request->reservation_time,
        'table'  => $request->table_id,
        'status' => 'Chờ xác nhận',
        'name'   => $request->full_name,
        'phone'  => $request->phone,
        'notes'  => $request->notes ?? '',
    ];
    session()->put('reservation_info', $reservationInfo);

    // 4. Xử lý món ăn (Lưu cả Session và Database)
    if ($request->has('foods') && is_array($request->foods)) {
        $cart = session()->get('cart', []);

        try {
            foreach ($request->foods as $id => $item) {
                // Tìm món ăn trong bảng menus
                $menu = \App\Models\Menu::find($id);
                if (!$menu) continue;

                // A. Cập nhật vào Session cart (để hiển thị trên giao diện)
                $cart[$id] = [
                    'id'       => $menu->id,
                    'name'     => $menu->name,
                    'price'    => $menu->price,
                    'quantity' => $item['quantity'] ?? 1,
                    'image'    => $menu->image,
                ];

                // B. Lưu trực tiếp vào bảng carts trong Database
                \App\Models\Cart::create([
                    'user_id'  => auth()->id() ?? 7, // Mặc định ID là 7 nếu chưa login
                    'food_id'  => $menu->id,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }

            // Lưu lại mảng cart vào session
            session()->put('cart', $cart);

        } catch (\Exception $e) {
            // Nếu có lỗi database (ví dụ chưa chạy migration), nó sẽ dừng lại ở đây để bạn xem lỗi
            return dd("Lỗi lưu database: " . $e->getMessage());
        }
    }

    return redirect()->route('cart.index')
                     ->with('success', 'Thông tin đặt bàn và món ăn đã được lưu thành công!');
}
}