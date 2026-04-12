@extends('shared')

@section('title', 'Thanh Toán Bàn Của Bạn')

@section('head')
<style>
        body { background-color: #f8f9fa; padding: 50px 0; font-family: 'Segoe UI', sans-serif; }
        .order-container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .bill-info { background: #fffdf5; padding: 20px; border-radius: 8px; border-left: 5px solid #d9534f; margin-bottom: 25px; }
        .table-order thead { background-color: #f1f1f1; }
        .item-img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 12px; }
        .btn-checkout { background-color: #d9534f; color: white; padding: 12px 30px; border-radius: 25px; font-weight: bold; border: none; transition: 0.3s; width: 100%; max-width: 300px;}
        .btn-checkout:hover { background-color: #c9302c; transform: scale(1.05); }
    </style>
@endsection

@section('content')
@php
    $bankID = 'VBA'; 
    $accountNo = '6320704409810'; 
    $accountName = 'Tran Minh Hai Tam'; 
    $info = "Thanh toan Ban " . ($reservation['table'] ?? '0');
    $qrUrl = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact.png?amount={$total}&addInfo={$info}&accountName={$accountName}";
@endphp
@if(session('success'))
    <div class="alert alert-success" style="margin:20px;">
        {{ session('success') }}
    </div>
@endif
<div class="container">
    <div class="order-container">
        <h3 class="mb-4 text-center text-uppercase" style="color: #d9534f; font-weight: bold;">Hóa Đơn Đặt Bàn</h3>

        <div class="bill-info">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Khách hàng:</strong> {{ $reservation['name'] }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $reservation['date'] }}</p>
                    <p><strong>Giờ đặt:</strong> {{ $reservation['time'] }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Số bàn:</strong> <span class="badge badge-danger">Bàn {{ $reservation['table'] }}</span></p>
                    <p><strong>Trạng thái:</strong> <span class="text-danger font-weight-bold">{{ $reservation['status'] }}</span></p>
                    <p><strong>Ghi chú:</strong> <i class="text-muted">{{ $reservation['notes'] ?: 'Không có ghi chú' }}</i></p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-order">
                <thead>
                    <tr>
                        <th width="40%">Món ăn</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($cart as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($item['image']) }}" class="item-img-mini" onerror="this.src='https://via.placeholder.com/50'">
                                <strong>{{ $item['name'] }}</strong>
                            </div>
                        </td>
                        <td class="text-center">{{ $item['quantity'] }}</td>
                        <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }}đ</td>
                        <td class="text-right" style="font-weight: bold;">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">Giỏ hàng trống!</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">TỔNG CỘNG:</th>
                        <th class="text-right text-danger" style="font-size: 1.2rem;">{{ number_format($total, 0, ',', '.') }} VNĐ</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(count($cart) > 0)
        <form action="{{ route('cart.checkout') }}" method="POST">
            @csrf
            <div class="mt-4 p-3 border rounded">
                <h5 class="mb-3">💳 Phương thức thanh toán</h5>
                <div>
                    <input type="radio" name="payment_method" value="COD" checked onclick="document.getElementById('qr-box').style.display='none'"> 
                    <label>Thanh toán tại quầy</label>
                </div>
                <div>
                    <input type="radio" name="payment_method" value="Banking" onclick="document.getElementById('qr-box').style.display='block'"> 
                    <label>Chuyển khoản QR Ngân hàng</label>
                </div>

                <div id="qr-box" style="display:none; text-align:center;" class="mt-3">
                    <img src="{{ $qrUrl }}" style="width:220px; border: 1px solid #ddd; border-radius:10px; padding: 10px;">
                    <p class="mt-2 text-muted">Chủ TK: {{ $accountName }} - Agribank</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-checkout">XÁC NHẬN THANH TOÁN</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
