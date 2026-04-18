<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    // Kiểm tra nếu đã đăng nhập và role_id đúng bằng 1
    if (auth()->check() && auth()->user()->role_id == 1) {
        return $next($request);
    }

    // Nếu không phải admin (ví dụ khách có role_id khác 1) thì về trang chủ
    return redirect('/')->with('error', 'Khu vực này chỉ dành cho Admin của quán thôi!');
}   
}
