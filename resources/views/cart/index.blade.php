<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán Bàn Của Bạn</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 40px 0; font-family: Arial, sans-serif; }
        .box { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto; }
        .item { display: flex; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .item img { width: 60px; height: 60px; margin-right: 15px; border-radius: 8px; object-fit: cover; }
        .btn-main { background: #d9534f; color: #fff; border: none; padding: 12px 25px; border-radius: 30px; font-weight: bold; width: 100%; font-size: 16px; transition: 0.3s; }
        .btn-main:hover { background: #c9302c; }
        .qr-section { background: #fff5f5; border: 2px dashed #d9534f; border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 20px; }
        .customer-info { background: #fdfdfd; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .info-box { background: #f4fdf8; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin-bottom: 20px; color: #155724; }
    </style>
</head>

<body>
<div class="container">
    <div class="box">
        <h3 class="text-center mb-4 fw-bold">XÁC NHẬN ĐƠN HÀNG</h3>

        @if(session('cart') && count(session('cart')) > 0)
            
            @php
                // 1. Tính tổng tiền
                $cart = session('cart');
                $totalAmount = 0;
                foreach($cart as $item) {
                    $totalAmount += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                }

                // 2. Mở hộp 'reservation_info' từ code Controller mới ra
                $resInfo = session('reservation_info', []);
                $tableNum = $resInfo['table'] ?? 'Mang về';
                $cusName  = $resInfo['name'] ?? '';
                $cusPhone = $resInfo['phone'] ?? '';
                $dateTime = $resInfo['date'] ?? 'Chưa chọn';
                $notes    = $resInfo['notes'] ?? '';

                // 3. Thông tin QR (Vẫn giữ SĐT chuẩn của ný)
                $bankID = 'MB'; 
                $accountNo = '0704409810'; 
                $memo = 'Thanh toan ban ' . $tableNum;
            @endphp

            <div class="info-box text-start">
                <h6 class="fw-bold mb-3" style="color: #28a745;"><i class="fa fa-calendar-check-o"></i> Thông tin đặt bàn</h6>
                <p class="mb-1">👤 <b>Khách hàng:</b> {{ $cusName }} (SĐT: {{ $cusPhone }})</p>
                <p class="mb-1">⏰ <b>Thời gian:</b> {{ $dateTime }}</p>
                <p class="mb-1">🍽️ <b>Vị trí:</b> Bàn số {{ $tableNum }}</p>
                @if($notes)
                    <p class="mb-0">📝 <b>Ghi chú:</b> {{ $notes }}</p>
                @endif
            </div>

            <h6 class="mb-3 fw-bold text-muted">Chi tiết món ăn:</h6>
            @foreach($cart as $item)
            <div class="item">
                <img src="{{ asset($item['image'] ?? 'images/default.jpg') }}" alt="Food">
                <div style="flex-grow: 1;">
                    <h6 class="mb-0 fw-bold">{{ $item['name'] }}</h6>
                    <small class="text-muted">SL: {{ $item['quantity'] }} x {{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ</small>
                </div>
                <div class="text-danger fw-bold" style="font-size: 16px;">
                    {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}đ
                </div>
            </div>
            @endforeach

            <div class="qr-section">
                <h5 class="mb-3 fw-bold">Quét mã QR để thanh toán</h5>
                <img src="https://img.vietqr.io/image/{{ $bankID }}-{{ $accountNo }}-compact2.png?amount={{ $totalAmount }}&addInfo={{ urlencode($memo) }}" 
                     alt="Mã QR" style="width: 220px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <h4 class="text-danger mt-3 fw-bold">Tổng: {{ number_format($totalAmount, 0, ',', '.') }} VNĐ</h4>
                <p class="text-muted small mb-0">Nội dung: {{ $memo }}</p>
            </div>

            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ $cusName }}">
                <input type="hidden" name="phone" value="{{ $cusPhone }}">
                <input type="hidden" name="address" value="{{ $notes }}">

                <button type="submit" class="btn-main">XÁC NHẬN & HOÀN TẤT ĐẶT BÀN</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('cart.clear') }}" class="text-muted" style="text-decoration: underline;">Huỷ đơn hàng</a>
            </div>

        @else
            <div class="text-center py-5">
                <h5 class="text-muted mb-4">Bạn chưa chọn món nào!</h5>
                <a href="/reservation" class="btn-main" style="text-decoration: none; padding: 10px 30px;">Quay lại Đặt Bàn</a>
            </div>
        @endif

    </div>
</div>
</body>
</html>