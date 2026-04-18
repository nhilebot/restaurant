@extends('shared')

@section('title', 'Thanh Toán Bàn Của Bạn')

@section('head')
<style>
    body { background-color: #f8f9fa; padding: 50px 0; font-family: 'Segoe UI', sans-serif; }
    .order-container { background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .bill-info { background: #fffdf5; padding: 20px; border-radius: 8px; border-left: 5px solid #d9534f; margin-bottom: 25px; }
    .table-order thead { background-color: #f1f1f1; }
    .item-img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; margin-right: 12px; }
    .btn-checkout { background-color: #d9534f; color: white; padding: 12px 30px; border-radius: 25px; font-weight: bold; border: none; transition: 0.3s; width: 100%; max-width: 350px;}
    .btn-checkout:hover { background-color: #c9302c; transform: scale(1.05); }
    
    /* Giao diện phương thức thanh toán trải dài */
    .payment-methods-box { border: 1px solid #eee; padding: 25px; border-radius: 10px; background: #fafafa; }
    .payment-item { 
        display: block; 
        border: 2px solid #ddd; 
        padding: 15px; 
        border-radius: 8px; 
        cursor: pointer; 
        margin-bottom: 15px; 
        transition: 0.3s; 
        background: #fff;
        position: relative;
    }
    .payment-item:hover { border-color: #d9534f; }
    .payment-item.active { border-color: #d9534f; background: #fff5f5; }

    /* Vùng QR ẩn hiện ngay dưới option Chuyển khoản */
    #qr-inline-area { 
        display: none; 
        margin-top: 15px; 
        padding: 20px; 
        background: #fff; 
        border: 1px dashed #d9534f; 
        border-radius: 8px; 
        text-align: center; 
    }
    #qr-inline-area img { max-width: 200px; border: 1px solid #eee; }
    .qty-box {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .qty-btn {
        width: 30px;
        height: 30px;
        border: none;
        background: #e08380;
        color: white;
        border-radius: 50%;
        font-size: 18px;
        cursor: pointer;
    }

    .qty-input {
        width: 40px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-weight: bold;
    }
    
    /* Giao diện Bill Cao Cấp */
    .premium-bill {
        background: #fff;
        border-radius: 0;
        border: 1px solid #dcdcdc;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        font-family: 'Times New Roman', Times, serif; 
        color: #333;
        position: relative;
    }

    .bill-header-gold {
        border-top: 0px solid #b8860b;
        padding: 15px 10px 5px; 
        text-align: center;
    }

    .restaurant-logo-center {
        width: 65px; 
        height: 65px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 5px;
    }

    .bill-title-luxury {
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: bold;
        color: #d9534f;
        font-size: 1.1rem;
        margin-bottom: 2px;
    }

    .luxury-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #b8860b, transparent);
        margin: 20px 0;
    }

    .table-premium {
        width: 100%;
        font-size: 13px; 
        line-height: 1.2; 
    }

    .table-premium th {
        font-family: sans-serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #888;
        border-bottom: 1px solid #eee;
        padding: 4px 0;
    }

    .table-premium td {
        padding: 4px 0; 
        border-bottom: 1px dotted #eee;
    }

    .total-highlight {
        background: #fdfaf0;
        padding: 10px 15px;
        border: 1px solid #e6dbca;
        margin-top: 10px;
        text-align: right;
    }

    .btn-luxury-pay {
        background-color: #d9534f; 
        color: white;
        border: none;
        padding: 10px;
        font-weight: bold;
        width: 100%;
        border-radius: 4px;
        margin-top: 15px;
        transition: 0.3s;
    }

    .btn-luxury-pay:hover { transform: scale(1.02); }

    .modal-dialog-centered.custom-width {
        max-width: 380px; 
        margin: 1.75rem auto;
    }
    /* Chỉnh lại khung bao quanh */
    .payment-item { 
        display: flex; 
        align-items: center; 
        padding: 20px; 
        border-radius: 15px; 
        border: 2px solid #f1f5f9; 
        cursor: pointer; 
        transition: 0.3s; 
        background: #fff; 
        margin-bottom: 15px;
        position: relative;
    }

    /* HIỆN THỊ HÌNH TRÒN CHỌN (Custom Radio) */
    .payment-item input[type="radio"] {
        display: block !important; /* Hiện lại nút tròn */
        width: 22px;
        height: 22px;
        margin-right: 15px;
        cursor: pointer;
        accent-color: #d9534f; /* Màu đỏ khi được chọn */
    }

    /* Hiệu ứng khi được chọn (Active) */
    .payment-item.active { 
        border-color: #d9534f; 
        background: #fffafa; 
        box-shadow: 0 5px 15px rgba(217, 83, 79, 0.1); 
    }

    /* Chỉnh lại icon cho cân đối */
    .payment-item i { 
        font-size: 22px; 
        width: 40px; 
        text-align: center; 
        margin-right: 10px;
        color: #718096;
    }

    .payment-item.active i {
        color: #d9534f; /* Icon đổi màu khi chọn */
    }

    .payment-item h6 {
        margin-bottom: 2px;
        font-weight: 700;
        color: #2d3748;
    }

    /* Hiệu ứng mượt khi hiện QR */
    #qr-inline-area { 
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('content')

@php
    $bankID = 'MB'; 
    $accountNo = '0343970743'; 
    $accountName = 'Le Yen Nhi'; 
    $info = "Thanh toan Ban " . ($reservation['table'] ?? '0');
    $qrUrl = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact.png?amount={$total}&addInfo={$info}&accountName={$accountName}";
    $invoiceNumber = 'INV-' . strtoupper(substr(md5(now()->timestamp . auth()->id()), 0, 8));
    $currentDateTime = now()->format('H:i d/m/Y');
@endphp

<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="order-container">
        <h3 class="mb-4 text-center text-uppercase" style="color: #d9534f; font-weight: bold;">
            Hóa Đơn Đặt Bàn
        </h3>

        <div class="bill-info">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã hóa đơn:</strong> {{ $invoiceNumber }}</p>
                    <p><strong>Thời gian hóa đơn:</strong> {{ $currentDateTime }}</p>
                    <p><strong>Ngày đến:</strong> {{ $reservation['date'] }}</p>
                    <p><strong>Giờ đến:</strong> {{ $reservation['time'] }}</p>
                    <p><strong>Khách hàng:</strong> {{ $reservation['name'] }}</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p><strong>Số bàn:</strong> <span class="badge badge-danger">Bàn {{ $reservation['table'] }}</span></p>
                    <p><strong>Trạng thái:</strong> <span class="text-danger font-weight-bold">{{ $reservation['status'] }}</span></p>
                    <p><strong>Ghi chú:</strong> <i class="text-muted">{{ $reservation['notes'] ?: 'Không có ghi chú' }}</i></p>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-order">
                <thead>
                    <tr>
                        <th width="40%">Món ăn</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-right">Đơn giá</th>
                        <th class="text-right">Thành tiền</th>
                        <th class="text-center">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cart as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($item['image'] ?? 'images/default.jpg') }}" class="item-img-mini">
                                <strong>{{ $item['name'] ?? 'Món không tồn tại' }}</strong>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="qty-box">
                                <button type="button" class="qty-btn" onclick="changeQty(this, -1)">-</button>
                                <input type="text" class="qty-input" value="{{ $item['quantity'] }}" readonly data-price="{{ $item['price'] ?? 0 }}">
                                <button type="button" class="qty-btn" onclick="changeQty(this, 1)">+</button>
                            </div>
                        </td>
                        <td class="text-right">{{ number_format($item['price'] ?? 0, 0, ',', '.') }}đ</td>
                        <td class="text-right font-weight-bold">
                            {{ number_format(($item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}đ
                        </td>
                        <td class="text-center">
                            <a href="{{ route('cart.remove', $item['id']) }}" 
   class="btn btn-sm btn-danger" 
   onclick="return confirm('Xóa món này nhé?')">
    <i class="fa fa-times"></i>
</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">Giỏ hàng trống!</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">TỔNG CỘNG:</th>
                        <th class="text-right text-danger" style="font-size: 1.4rem;">
                            {{ number_format($total, 0, ',', '.') }} VNĐ
                        </th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
@if(count($cart))
<form id="checkoutForm" action="{{ route('cart.checkout') }}" method="POST">
    @csrf
    <input type="hidden" name="total_price" value="{{ $total }}">
    <input type="hidden" name="table_number" value="{{ $reservation['table'] }}">
    <input type="hidden" name="order_notes" value="{{ $reservation['notes'] ?? '' }}">

    <div class="payment-methods-box mt-4">
    <h5 class="mb-3 font-weight-bold">
        <i class="fas fa-wallet mr-2"></i>Chọn phương thức thanh toán
    </h5>

    <!-- COD -->
    <label class="payment-item active" id="label-cod">
        <input type="radio" name="payment_method" value="COD" checked>
        <i class="fas fa-money-bill-wave"></i>
        <div>
            <h6>Thanh toán tại quầy</h6>
            <small class="text-muted">Dùng tiền mặt hoặc quẹt thẻ</small>
        </div>
    </label>

    <!-- BANK -->
    <label class="payment-item" id="label-bank">
        <input type="radio" name="payment_method" value="BANK">
        
        <div>
            <h6>Chuyển khoản (Quét QR)</h6>
            <small class="text-muted">Thanh toán nhanh qua ngân hàng</small>
        </div>
    </label>

    <!-- QR AREA -->
    <div id="qr-inline-area" class="text-center">
        <h6 class="text-danger font-weight-bold mb-3">MÃ QR THANH TOÁN</h6>
        <img src="{{ $qrUrl }}" alt="QR Code Payment" class="img-fluid mb-2">
        <p class="small text-muted mb-0">Nội dung: <strong>{{ $info }}</strong></p>
        <p class="small text-muted">Chủ TK: <strong>{{ $accountName }}</strong></p>
    </div>
</div>

    <div class="text-center mt-5">
        <button type="button" class="btn-checkout shadow-lg" data-toggle="modal" data-target="#billModal">
            XÁC NHẬN THANH TOÁN
        </button>
    </div>
</form> 
@endif
    </div>
</div>

<div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-width">
        <div class="modal-content premium-bill shadow-lg">
            <div class="modal-body p-3">
                <div class="bill-header-gold">
                    <img src="{{ asset('images/logonh.png') }}" class="restaurant-logo-center">
                    <h5 class="bill-title-luxury">Món Việt</h5>
                    <p class="small text-muted mb-0" style="font-size: 11px;">Tinh hoa ẩm thực Việt</p>
                </div>
                <div class="luxury-divider" style="height:1px; background:#040404; margin:15px 0; opacity:0.1;"></div>
                <div class="d-flex justify-content-between mb-2" style="font-size: 12px;">
                    <div>
                        <p class="mb-1"><strong>Mã hóa đơn:</strong> {{ $invoiceNumber }}</p>
                        <p class="mb-1"><strong>Thời gian hóa đơn:</strong> {{ $currentDateTime }}</p>
                        <p class="mb-1"><strong>Ngày đến:</strong> {{ $reservation['date'] }}</p>
                        <p class="mb-1"><strong>Giờ đến:</strong> {{ $reservation['time'] }}</p>
                        <p class="mb-0"><strong>Khách:</strong> <strong>{{ $reservation['name'] ?? 'Nhi' }}</strong></p>
                    </div>
                    <div class="text-right">
                        <p class="mb-1"><strong>Bàn:</strong> <span class="text-danger">{{ $reservation['table'] ?? '1' }}</span></p>
                        <p class="mb-0"><strong>Ghi chú:</strong> <small class="text-muted">{{ $reservation['notes'] ?: 'Không có ghi chú' }}</small></p>
                    </div>
                </div>
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th class="text-left">Tên sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Tổng cộng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-right">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="total-highlight">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small font-weight-bold">TỔNG CỘNG:</span>
                        <span class="h5 mb-0 font-weight-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
                <div class="text-center mt-2">
                    <p style="font-size: 10px; font-style: italic;" class="text-muted mb-0">Cảm ơn và hẹn gặp lại Quý khách!</p>
                </div>
                <button type="button" class="btn btn-luxury-pay" onclick="submitOrder()">XÁC NHẬN THANH TOÁN</button>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. CÁC HÀM XỬ LÝ GIAO DIỆN (JS THUẦN)
    function toggleQR(show) {
        const qrArea = document.getElementById('qr-inline-area');
        const labelCod = document.getElementById('label-cod');
        const labelBank = document.getElementById('label-bank');
        const inputBank = labelBank.querySelector('input[value="BANK"]');
        const inputCod = labelCod.querySelector('input[value="COD"]');

        if (show) {
            qrArea.style.display = 'block';
            labelBank.classList.add('active');
            labelCod.classList.remove('active');
            inputBank.checked = true;
        } else {
            qrArea.style.display = 'none';
            labelCod.classList.add('active');
            labelBank.classList.remove('active');
            inputCod.checked = true;
        }
    }
    // Hàm thay đổi số lượng món ăn
    function changeQty(btn, value) {
        const row = btn.closest('tr');
        const input = row.querySelector('.qty-input');
        let qty = parseInt(input.value);
        qty += value;
        if (qty < 1) qty = 1;
        input.value = qty;
        updateRowTotal(row);
        updateGrandTotal();
    }

    function updateRowTotal(row) {
        const qty = parseInt(row.querySelector('.qty-input').value);
        const price = parseInt(row.querySelector('.qty-input').dataset.price);
        const totalCell = row.querySelector('.text-right.font-weight-bold');
        const total = qty * price;
        totalCell.innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            const qty = parseInt(input.value);
            const price = parseInt(input.dataset.price);
            total += qty * price;
        });
        document.querySelector('tfoot th.text-danger').innerText = new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ';
    }

    function submitOrder() {
        // Lấy đúng ID của form Nhi đã đặt ở trên
        var form = document.getElementById('checkoutForm');
        
        if (form) {
            form.submit();
        } else {
            alert("Lỗi hệ thống: Không tìm thấy form thanh toán!");
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

    const qrArea = document.getElementById('qr-inline-area');
    const labelCod = document.getElementById('label-cod');
    const labelBank = document.getElementById('label-bank');
    const radios = document.querySelectorAll('input[name="payment_method"]');

    function updateUI(value) {
        if (value === 'BANK') {
            qrArea.style.display = 'block';
            labelBank.classList.add('active');
            labelCod.classList.remove('active');
        } else {
            qrArea.style.display = 'none';
            labelCod.classList.add('active');
            labelBank.classList.remove('active');
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            updateUI(this.value);
        });
    });

    // trạng thái ban đầu
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked) updateUI(checked.value);
});
</script>
@endsection