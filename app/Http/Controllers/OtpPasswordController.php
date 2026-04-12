<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Bạn phải nhập email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email này không tồn tại trong hệ thống.'])->withInput();
        }

        $otp = random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Mail::raw(
            "Mã OTP khôi phục mật khẩu của bạn là: {$otp}\nMã có hiệu lực trong 10 phút.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Mã OTP khôi phục mật khẩu');
            }
        );

        return redirect()->route('password.verify')->with('status', 'Mã OTP đã được gửi đến email của bạn.');
    }

    public function showVerifyForm()
    {
        return view('verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
        ], [
            'email.required' => 'Bạn phải nhập email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'otp.required' => 'Bạn phải nhập mã OTP.',
            'otp.digits' => 'Mã OTP phải gồm 6 chữ số.',
            'password.required' => 'Bạn phải nhập mật khẩu mới.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (! $record || now()->diffInMinutes($record->created_at) > 10) {
            return back()->withErrors(['otp' => 'Mã OTP không hợp lệ hoặc đã hết hạn.'])->withInput();
        }

        if (! Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Mã OTP không đúng.'])->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email này không tồn tại.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Auth::login($user);

        return redirect('/')->with('success', 'Mật khẩu đã được đặt lại. Bạn đã đăng nhập thành công.');
    }
}
