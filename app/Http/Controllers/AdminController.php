<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
{
    // Dòng 1: Đăng ký middleware auth
    $this->middleware('auth');

    // Dòng 2: Đăng ký middleware kiểm tra quyền admin riêng biệt
    $this->middleware(function ($request, $next) {
        if (!Auth::user() || Auth::user()->role->name !== 'admin') {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập.');
        }
        return $next($request);
    });
}

    public function index()
    {
        $orders = Order::with('user', 'orderItems.menu')->get();
        $menus = Menu::all();
        return view('admin.index', compact('orders', 'menus'));
    }

    public function updateStock(Request $request, Menu $menu)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $menu->update(['stock' => $request->stock]);

        return redirect()->back()->with('success', 'Cập nhật stock thành công.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,serving,paid',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }
}
