<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của bạn</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding: 50px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .order-container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .table-order thead { background-color: #f1f1f1; }
        .table-order th { border: none; color: #555; text-transform: uppercase; font-size: 13px; }
        .item-row { display: flex; align-items: center; margin-bottom: 10px; border-bottom: 1px dashed #eee; padding-bottom: 5px; }
        .item-row:last-child { border-bottom: none; }
        .item-img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 12px; border: 1px solid #ddd; }
        .status-text { color: #d9534f; font-weight: bold; text-transform: lowercase; }
        .btn-checkout { background-color: #d9534f; color: white; padding: 12px 30px; border-radius: 25px; font-weight: bold; border: none; float: right; margin-top: 20px; transition: 0.3s; }
        .btn-checkout:hover { background-color: #c9302c; box-shadow: 0 5px 15px rgba(217, 83, 79, 0.3); }
        .cancel-link { color: #d9534f; font-size: 13px; cursor: pointer; text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="order-container">
        <h3 class="mb-4" style="color: #333; font-weight: bold;">Đơn Hàng Của Bạn</h3>
        
        <div class="table-responsive">
            <table class="table table-bordered table-order">
                <thead>
                    <tr>
                        <th width="35%">Món ăn</th>
                        <th>Ngày</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Bàn</th>
                        <th>Tổng cộng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($cart) > 0)
                        <tr>
                            <td>
                                @foreach($cart as $item)
                                    <div class="item-row">
                                        <img src="{{ asset($item['image']) }}" class="item-img-mini" onerror="this.src='https://via.placeholder.com/50'">
                                        <div>
                                            <div style="font-weight: 600; font-size: 14px;">{{ $item['name'] }} ({{ $item['quantity'] }})</div>
                                            <small class="text-muted">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</small>
                                        </div>
                                    </div>
                                @endforeach
                            </td>

                            <td style="vertical-align: middle;">{{ $reservation['date'] }}</td>

                            <td style="vertical-align: middle;" class="status-text">{{ $reservation['status'] }}</td>

                            <td style="vertical-align: middle;" class="text-center">
                                <span class="badge badge-danger" style="background-color: #d9534f; padding: 8px 12px;">
                                    Bàn {{ $reservation['table'] }}
                                </span>
                            </td>

                            <td style="vertical-align: middle; font-weight: bold; color: #333;">
                                {{ number_format($total, 0, ',', '.') }} vnđ
                            </td>

                            <td style="vertical-align: middle;">
                                <a href="{{ route('cart.clear') }}" class="btn-cancel" style="color: #d9534f;">Hủy đơn hàng</a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Giỏ hàng đang trống! <a href="{{ url('/reservation') }}">Đặt món ngay</a></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(count($cart) > 0)
            <div class="clearfix">
                <form action="{{ route('cart.checkout') }}" method="POST">
    @csrf
    @if(session('error'))
        <div style="background: #ffe6e6; color: #d9534f; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #d9534f;">
            <strong>⚠️ HỆ THỐNG BÁO LỖI:</strong> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #ffe6e6; color: #d9534f; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #d9534f;">
            <strong>⚠️ BẠN NHẬP THIẾU THÔNG TIN:</strong>
            <ul style="margin-bottom: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('cart.checkout') }}" method="POST">
    
    <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; text-align: left;">
        <h5 style="color: #d9534f; font-weight: bold; margin-bottom: 15px;"><i class="fa fa-user"></i> Thông tin giao hàng</h5>
        
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 15px;">
                <input type="text" name="name" class="form-control" placeholder="Tên người nhận (VD: Tâm)" required>
            </div>
            <div class="col-md-6" style="margin-bottom: 15px;">
                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="0704409810" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <textarea name="address" class="form-control" placeholder="Địa chỉ giao hàng chi tiết..." rows="2" required></textarea>
            </div>
        </div>
    </div>
    
<div style="margin-top: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
    <label style="font-weight: bold; font-size: 16px; margin-bottom: 15px; display: block; color: #2c5c7c;">
        💳 Chọn phương thức thanh toán
    </label>

    <div style="margin-bottom: 12px; display: flex; align-items: center;">
        <input type="radio" id="cod" name="payment_method" value="COD" checked onclick="document.getElementById('qr-code-box').style.display='none'" style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;">
        <label for="cod" style="cursor: pointer; margin: 0; font-size: 15px;">
            💵 Thanh toán tiền mặt khi nhận hàng (COD)
        </label>
    </div>

    <div style="display: flex; align-items: center;">
        <input type="radio" id="banking" name="payment_method" value="Banking" onclick="document.getElementById('qr-code-box').style.display='block'" style="width: 18px; height: 18px; margin-right: 10px; cursor: pointer;">
        <label for="banking" style="cursor: pointer; margin: 0; font-size: 15px;">
            🏦 Chuyển khoản ngân hàng / MoMo
        </label>
    </div>

    <div id="qr-code-box" style="display: none; margin-top: 15px; text-align: center; border-top: 1px dashed #ccc; padding-top: 15px;">
        <p style="color: #e74c3c; font-weight: bold; margin-bottom: 10px;">Quét mã QR dưới đây để thanh toán:</p>
        
        <img src="{{ asset('images/qr-bank.jpg') }}" alt="Mã QR Thanh Toán" style="width: 200px; max-width: 100%; border-radius: 8px; border: 2px solid #2c5c7c; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        
        <p style="font-size: 13px; color: #666; margin-top: 10px; font-style: italic;">
            (Vui lòng ghi chú: Tên + SĐT của bạn khi chuyển khoản)
        </p>
    </div>
</div>
<br>
<div style="display: flex; justify-content: center; width: 100%; margin-top: 20px;">
    <button type="submit" class="btn-checkout" style="padding: 12px 30px; font-size: 16px;"><i class="fa fa-check-circle"></i> XÁC NHẬN THANH TOÁN</button>
</div>            </div>
        @endif
    </div>
</div>

</body>
</html>