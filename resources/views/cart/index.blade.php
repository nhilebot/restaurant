<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán Bàn Của Bạn</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 50px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .order-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .table-order thead {
            background-color: #f1f1f1;
        }

        .table-order th {
            border: none;
            color: #555;
            text-transform: uppercase;
            font-size: 13px;
        }

        .item-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-img-mini {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 12px;
            border: 1px solid #ddd;
        }

        .status-text {
            color: #d9534f;
            font-weight: bold;
            text-transform: lowercase;
        }

        .btn-checkout {
            background-color: #d9534f;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            border: none;
            transition: 0.3s;
        }

        .btn-checkout:hover {
            background-color: #c9302c;
            box-shadow: 0 5px 15px rgba(217, 83, 79, 0.3);
        }

        .btn-cancel {
            color: #d9534f;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="order-container">

        <h3 class="mb-4" style="color: #333; font-weight: bold;">
            Đơn Hàng Của Bạn
        </h3>

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

                        <!-- MÓN ĂN -->
                        <td>
                            @foreach($cart as $item)
                                <div class="item-row">
                                    <img src="{{ asset($item['image']) }}"
                                         class="item-img-mini"
                                         onerror="this.src='https://via.placeholder.com/50'">

                                    <div>
                                        <div style="font-weight: 600;">
                                            {{ $item['name'] }} ({{ $item['quantity'] }})
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </td>

                        <!-- NGÀY -->
                        <td style="vertical-align: middle;">
                            {{ $reservation['date'] }}
                        </td>

                        <!-- TRẠNG THÁI -->
                        <td style="vertical-align: middle;" class="status-text">
                            {{ $reservation['status'] }}
                        </td>

                        <!-- BÀN -->
                        <td style="vertical-align: middle;" class="text-center">
                            <span class="badge badge-danger"
                                  style="background-color: #d9534f; padding: 8px 12px;">
                                Bàn {{ $reservation['table'] }}
                            </span>
                        </td>

                        <!-- TỔNG TIỀN -->
                        <td style="vertical-align: middle; font-weight: bold;">
                            {{ number_format($total, 0, ',', '.') }} VNĐ
                        </td>

                        <!-- HỦY -->
                        <td style="vertical-align: middle;">
                            <a href="{{ route('cart.clear') }}" class="btn-cancel">
                                Hủy đơn
                            </a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6" class="text-center">
                            Giỏ hàng trống!
                            <a href="{{ url('/reservation') }}">Đặt món ngay</a>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        @if(count($cart) > 0)

        <!-- THANH TOÁN -->
        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf

            @if(session('error'))
                <div style="background: #ffe6e6; color: #d9534f; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="margin-top: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">

                <label style="font-weight: bold; font-size: 16px;">
                    💳 Chọn phương thức thanh toán
                </label>

                <!-- COD -->
                <div style="margin-top: 10px;">
                    <input type="radio" name="payment_method" value="COD" checked
                        onclick="document.getElementById('qr-box').style.display='none'">
                    Thanh toán tại quầy
                </div>

                <!-- BANKING -->
                <div>
                    <input type="radio" name="payment_method" value="Banking"
                        onclick="document.getElementById('qr-box').style.display='block'">
                    Chuyển khoản / QR
                </div>

                <!-- QR -->
                <div id="qr-box" style="display:none; margin-top:15px; text-align:center;">
                    <p style="color:red; font-weight:bold;">
                        Quét mã QR để thanh toán
                    </p>

                    <img src="{{ asset('images/qr-bank.jpg') }}"
                         style="width:200px; border-radius:10px;">
                </div>
            </div>

            <div style="text-align:center; margin-top:20px;">
                <button type="submit" class="btn-checkout">
                    XÁC NHẬN THANH TOÁN
                </button>
            </div>

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