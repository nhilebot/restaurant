<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index()
{
    $user = Auth::user(); // lấy thông tin user đang đăng nhập

    return view('profile', compact('user'));
}
}
