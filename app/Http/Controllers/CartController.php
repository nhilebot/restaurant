<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 1. Hiển thị trang Giỏ hàng & Bill Thanh Toán
    public function index()
    {
        $cart = session()->get('cart', []);
        
        $reservation = session()->get('reservation_info', [
            'date' => date('d/m/Y H:i'), 
            'table' => 'Chưa chọn',
            'status' => 'Chờ xác nhận',
            'name' => 'Khách vãng lai',
            'phone' => 'Chưa có',
            'notes' => ''
        ]);

        $total = 0;
        foreach($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        return view('cart.index', compact('cart', 'total', 'reservation'));
    }

    // 2. Thêm món vào giỏ hàng
    public function addToCartAjax(Request $request)
    {
        $id = $request->input('menu_id');
        $quantity = $request->input('quantity', 1);
        
        $resInfo = session()->get('reservation_info', []);
        $resInfo['date'] = $request->input('reservation_date', $resInfo['date'] ?? date('d/m/Y H:i'));
        $resInfo['table'] = $request->input('table_id', $resInfo['table'] ?? 'Chưa chọn');
        $resInfo['status'] = 'Đang xử lý';
        session()->put('reservation_info', $resInfo);

        $menu = Menu::findOrFail($id);

        if ($menu->stock < $quantity) {
            return response()->json(['error' => 'Món ' . $menu->name . ' không đủ số lượng.'], 400);
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "id" => $id,
                "name" => $menu->name,
                "quantity" => $quantity,
                "price" => $menu->price,
                "image" => $menu->image
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm món vào giỏ hàng!');
    }
    

    // 3. Hủy đơn / Xóa giỏ hàng
    public function clear()
    {
        session()->forget(['cart', 'reservation_info']);
        return redirect()->route('reservation.index')->with('success', 'Đã hủy giỏ hàng!');
    }

    // 4. Thanh toán & Lưu Database
    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        $resInfo = session('reservation_info', []);

        if (empty($cart) || empty($resInfo)) {
            return redirect('/reservation')->withErrors(['error' => 'Giỏ hàng đang trống!']);
        }

        try {
            DB::transaction(function () use ($cart, $resInfo, $request) {
                
                $totalAmount = 0;
                foreach($cart as $item) {
                    $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                }

                $order = Order::create([
                    'user_id'        => auth()->id(), 
                    'table_number'   => $resInfo['table'] ?? 'Mang về',
                    'total_price'    => $totalAmount,
                    'status'         => 'Chờ xác nhận',
                    'name'           => $request->name ?? $resInfo['name'] ?? 'Khách',
                    'phone'          => $request->phone ?? $resInfo['phone'] ?? '',
                    'address'        => $request->address ?? $resInfo['notes'] ?? '', 
                ]);

                foreach ($cart as $id => $item) {
                    $order->orderItems()->create([
                        'menu_id'  => $item['id'],
                        'quantity' => $item['quantity'],
                        'price'    => $item['price'],
                    ]);
                }
            });

            session()->forget(['cart', 'reservation_info', 'table_number']);

            return redirect('/')->with('success', '🎉 Chốt đơn thành công! Nhà hàng đã nhận thông tin đặt bàn của bạn.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi hệ thống khi lưu đơn: ' . $e->getMessage()]);
        }
    }
}