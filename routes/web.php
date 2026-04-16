<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuchitietController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\BeerController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\BreadController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OtpPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProfileController;
// --- TRANG CHỦ & THÔNG TIN CHUNG ---
Route::get('/', function () { return view('layout'); });
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/beer', [BeerController::class, 'index']);
Route::get('/featured', [FeaturedController::class, 'index']);
Route::get('/bread', [BreadController::class, 'index']);
Route::get('/pricing', [PricingController::class, 'index']);
Route::get('/tables', [TableController::class, 'index']);

// --- DEBUG ROUTES (DELETE AFTER TESTING) ---
Route::middleware('auth')->get('/debug-menu-json', function() {
    $menus = \App\Models\Menu::all();
    return response()->json([
        'count' => $menus->count(),
        'data' => $menus->toArray(),
        'first' => $menus->first()?->toArray()
    ]);
});

Route::middleware('auth')->get('/debug-menu-count', function() {
    return response()->json([
        'total' => \App\Models\Menu::count(),
        'seafood' => \App\Models\Menu::where('category', 'seafood')->count(),
        'vietnamese' => \App\Models\Menu::where('category', 'vietnamese')->count(),
        'special' => \App\Models\Menu::where('category', 'special')->count(),
        'salad' => \App\Models\Menu::where('category', 'salad')->count(),
        'dessert' => \App\Models\Menu::where('category', 'dessert')->count(),
        'drink' => \App\Models\Menu::where('category', 'drink')->count(),
    ]);
});

Route::middleware('auth')->get('/debug-reservation', function() {
    $menus = \App\Models\Menu::all();
    return view('debug-reservation', compact('menus'));
});

// --- QUẢN LÝ MENU (Công khai) ---
Route::get('/menu', [MenuchitietController::class, 'index'])->name('menu.index');
Route::get('/seafood', [MenuchitietController::class, 'seafood'])->name('menu.seafood');
Route::get('/special', [MenuchitietController::class, 'special'])->name('menu.special');
Route::get('/salad', [MenuchitietController::class, 'salad'])->name('menu.salad');
Route::get('/desserts', [MenuchitietController::class, 'desserts'])->name('menu.desserts');
Route::get('/drinks', [MenuchitietController::class, 'drinks'])->name('menu.drinks');
Route::get('/vietnamese', [MenuchitietController::class, 'vietnamese'])->name('menu.vietnamese');
Route::get('/chi-tiet-mon-an/{id}', [MenuchitietController::class, 'showDetail'])->name('menu.detail');

// Thêm món vào giỏ hàng (công khai, kiểm tra auth trong controller)
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// --- AUTHENTICATION (Khách chưa đăng nhập) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [OtpPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [OtpPasswordController::class, 'sendOtp'])->name('password.email');
    Route::get('/verify-otp', [OtpPasswordController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/verify-otp', [OtpPasswordController::class, 'verifyOtp'])->name('password.update');
});

// Đăng xuất (Yêu cầu đã đăng nhập)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- CÁC ROUTE YÊU CẦU ĐĂNG NHẬP (AUTH) ---
Route::middleware(['auth'])->group(function () {

    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

    // Hệ thống Giỏ hàng
    Route::get('/cart', [ReservationController::class, 'showCart'])->name('cart.index');
    
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/clear-cart', [CartController::class, 'clear'])->name('cart.clear');

    // Đặt bàn
    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');

    // Quản lý Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/menu/{menu}/stock', [AdminController::class, 'updateStock'])->name('admin.updateStock');
    Route::post('/admin/order/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.updateOrderStatus');
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::post('/reservation/auto-save', [ReservationController::class, 'autoSave'])->name('reservation.autoSave');
    // Route::post('/add-to-cart-ajax', [App\Http\Controllers\ReservationController::class, 'addToCartAjax'])->name('cart.addAjax');
    Route::get('/test-mail', function () {
    Mail::raw('Test gửi mail Laravel', function ($msg) {
        $msg->to('emailcuaban@gmail.com')
            ->subject('Test Mail');
    });

    return 'Đã gửi mail!';
});
// gọi đến profile
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->middleware('auth');
});

Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::post('/orders/store', [OrderController::class, 'store'])->name('order.store');
Route::post('/reservation/add-to-cart', [ReservationController::class, 'addToCartAjax'])->name('reservation.addToCartAjax');

// Route::post('/cart/add-ajax', [CartController::class, 'addToCart'])->name('cart.addAjax');
Route::get('/cart/get', [CartController::class, 'getCart'])->name('cart.get');
Route::post('/cart/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
// Xóa từng món một
Route::post('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove');
// hiện thị lịch sử đơn hàng
Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
// hiển thị chi tiết đơn hàng
Route::get('/order-history', [OrderController::class, 'history'])
    ->name('orders.history')
    ->middleware('auth');
Route::middleware('auth')->group(function () {
    // Trang hiển thị profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // THÊM DÒNG NÀY: Để xử lý nút "Lưu thay đổi"
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
// Route cho nút Lưu thay đổi
Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update'); 
// Route::get('/reservation', [ReservationController::class, 'reservation']);   
Route::post('/reservation/add-to-cart', [ReservationController::class, 'addToCartAjax'])->name('reservation.addToCart');
Route::post('/reservation/save-note-ajax', [ReservationController::class, 'saveNoteAjax'])->name('reservation.saveNoteAjax');

Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
Route::post('/add-to-cart', [ReservationController::class, 'addToCartAjax'])->name('reservation.addToCart');