<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Hiển thị trang đăng nhập
    public function showLogin()
    {
        return view('login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác'])->withInput($request->only('email'));
    }

    // Hiển thị trang đăng ký
    public function showRegister()
    {
        return view('register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|regex:/^[0-9]{10,11}$/',
            'password' => 'required|min:8|confirmed',
            'terms' => 'accepted',
        ], [
            'name.required' => 'Họ tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không trùng khớp',
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản dịch vụ',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Tạo user mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Đăng nhập tự động sau khi đăng ký
        Auth::login($user);

        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Đã đăng xuất thành công');
    }
}
