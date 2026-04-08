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
        .btn-checkout { background-color: #d9534f; color: white; padding: 12px 40px; border-radius: 25px; font-weight: bold; border: none; transition: 0.3s; font-size: 16px; }
        .btn-checkout:hover { background-color: #c9302c; box-shadow: 0 5px 15px rgba(217, 83, 79, 0.3); transform: translateY(-2px); }
        .btn-cancel { color: #d9534f; text-decoration: underline; font-size: 13px; }
        .form-section { background: #fff; padding: 20px; border: 1px solid #eee; border-radius: 10px; margin-top: 20px; }
        .section-title { font-weight: bold; font-size: 16px; color: #333; margin-bottom: 15px; display: block; }
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
                        <th width="40%">Món ăn</th>
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
                                    <img src="{{ asset($item['image'] ?? 'images/default.jpg') }}" class="item-img-mini" onerror="this.src='https://via.placeholder.com/50'">
                                    <div>
                                        <div style="font-weight: 600;">{{ $item['name'] }} ({{ $item['quantity'] }})</div>
                                        <small class="text-muted">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</small>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td style="vertical-align: middle;">{{ $reservation['date'] }}</td>
                        <td style="vertical-align: middle;" class="status-text">{{ $reservation['status'] }}</td>
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="badge" style="background-color: #d9534f; color: white; padding: 8px 12px;">Bàn {{ $reservation['table'] }}</span>
                        </td>
                        <td style="vertical-align: middle; font-weight: bold; color: #333; font-size: 16px;">
                            {{ number_format($total, 0, ',', '.') }} VNĐ
                        </td>
                        <td style="vertical-align: middle;">
                            <a href="{{ route('cart.clear') }}" class="btn-cancel">Hủy đơn</a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6" class="text-center">Giỏ hàng trống! <a href="{{ url('/reservation') }}">Đặt món ngay</a></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        @if(count($cart) > 0)
        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf

            <div class="form-section shadow-sm">
                <label class="section-title">📝 Ghi chú đơn hàng</label>
                <textarea name="order_notes" class="form-control" rows="2">{{ $reservation['notes'] ?? '' }}</textarea>
            </div>

            <div class="form-section shadow-sm">
                <label class="section-title">💳 Phương thức thanh toán</label>
                
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked onclick="document.getElementById('qr-box').style.display='none'">
                    <label class="form-check-label" for="cod">Thanh toán tại quầy</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="payment_method" id="banking" value="Banking" onclick="document.getElementById('qr-box').style.display='block'">
                    <label class="form-check-label" for="banking">Chuyển khoản / QR</label>
                </div>

                <div id="qr-box" style="display:none; margin-top:15px; text-align:center; border-top: 1px dashed #ddd; padding-top: 15px;">
                    <p style="color:#d9534f; font-weight:bold;">Quét mã QR để thanh toán</p>
                    <img src="{{ asset('images/qr-bank.jpg') }}" style="width:180px; border-radius:10px; border: 1px solid #eee;">
                </div>
            </div>

            <div style="text-align:center; margin-top:30px;">
                <button type="submit" class="btn-checkout">XÁC NHẬN THANH TOÁN</button>
            </div>
        </form>
        @endif
    </div>
</div>
</body>
</html>