<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký | Tam Nhi Quán</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <style>
        body { background-color: #f3f4f6; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        .breeze-card { background: white; max-width: 450px; margin: 50px auto; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .form-control { border-radius: 0.375rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem; box-shadow: none; }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); outline: 0; }
        .btn-breeze { background-color: #111827; color: white; border-radius: 0.375rem; padding: 0.5rem 1rem; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; border: none; transition: all 0.15s ease-in-out; }
        .btn-breeze:hover { background-color: #374151; color: white; }
        .breeze-label { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="breeze-card">
            <div class="text-center mb-4">
                <h3 style="font-weight: 800; color: #111827; letter-spacing: -1px;">TAM NHI QUÁN</h3>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="font-size: 0.875rem; border-radius: 0.375rem;">
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="breeze-label">Họ và tên</label>
                    <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="phone" class="breeze-label">Số điện thoại</label>
                    <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', '0704409810') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="breeze-label">Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="breeze-label">Mật khẩu</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="breeze-label">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required style="border-color: #d1d5db; border-radius: 0.25rem; cursor: pointer;">
                    <label class="form-check-label" for="terms" style="font-size: 0.875rem; color: #4b5563; cursor: pointer;">Tôi đồng ý với các Điều khoản & Dịch vụ</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('login') }}" style="font-size: 0.875rem; color: #4b5563; text-decoration: underline;">Đã có tài khoản?</a>
                    <button type="submit" class="btn btn-breeze">ĐĂNG KÝ</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>