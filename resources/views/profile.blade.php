@extends('shared')

@section('title', 'Hồ sơ của tôi')

@section('content')
<div class="container" style="margin-top:120px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            
            <div style="background:#fff; padding:30px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1);">
                
                <h2 style="text-align:center; margin-bottom:20px;">Thông tin cá nhân</h2>

                <p><strong>Tên:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>

                {{-- Nếu bạn có thêm cột --}}
                <p><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa cập nhật' }}</p>

                <div style="text-align:center; margin-top:20px;">
                    <a href="#" class="btn btn-primary">Chỉnh sửa</a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection