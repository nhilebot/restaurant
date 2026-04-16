@extends('shared')

@section('title', 'Lịch sử đơn hàng')

@section('head')
<style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
    .history-container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    .title-page { text-align: center; font-weight: bold; margin-bottom: 25px; font-size: 24px; text-transform: uppercase; color: #d9534f; }
    
    .order-tabs { display: flex; justify-content: space-around; border-bottom: 2px solid #eee; margin-bottom: 20px; background: #fff; position: sticky; top: 0; z-index: 10; }
    .tab-item { padding: 15px 10px; cursor: pointer; font-weight: 600; color: #666; transition: 0.3s; border-bottom: 3px solid transparent; flex: 1; text-align: center; }
    .tab-item:hover { color: #d9534f; }
    .tab-item.active { color: #d9534f; border-bottom-color: #d9534f; }

    .order-block { border: 1px solid #ebebeb; margin-bottom: 25px; border-radius: 4px; overflow: hidden; }
    .order-info-header { background: #fafafa; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; }
    
    .badge-status { padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }

    .product-row { display: flex; padding: 15px 20px; align-items: center; border-bottom: 1px solid #f1f1f1; background: #fff; }
    .product-img { width: 80px; height: 80px; object-fit: cover; border: 1px solid #eee; border-radius: 4px; background: #f9f9f9; }
    .product-detail { flex: 1; padding-left: 15px; }
    .product-name { font-weight: bold; font-size: 16px; color: #333; }
    .product-qty { color: #888; font-size: 14px; }

    .order-footer { padding: 15px 20px; background: #fff; display: flex; justify-content: flex-end; align-items: center; gap: 15px; border-top: 1px solid #f1f1f1; }
    .total-price-all { font-size: 20px; font-weight: bold; color: #d9534f; }
    
    .btn-action { padding: 8px 20px; border-radius: 4px; font-size: 14px; font-weight: 600; text-decoration: none; border: none; transition: 0.3s; }
    .btn-view { background-color: #d9534f; color: white; }
    .btn-view:hover { background-color: #c9302c; color: #fff; }

    .empty-state { text-align: center; padding: 50px; display: none; }
    
</style>
@endsection

@section('content')
<div class="history-container">
    <h3 class="title-page">Danh sách đơn hàng của Nhi</h3>

    @php
        $justPaidId = session('just_paid_id');
        $displayOrders = $justPaidId ? $orders->where('id', $justPaidId) : $orders;
    @endphp

    @if($justPaidId)
        <div class="alert alert-success text-center mb-4">
            🎉 Thanh toán thành công! Đây là đơn hàng bạn vừa đặt. 
            <a href="{{ route('orders.history') }}" class="ml-2" style="text-decoration: underline;">Xem tất cả lịch sử</a>
        </div>
    @endif

    <div class="order-tabs">
        <div class="tab-item {{ !$justPaidId ? 'active' : '' }}" onclick="filterByStatus('all', this)">Tất cả</div>
        <div class="tab-item" onclick="filterByStatus('pending', this)">Đang chờ xử lý</div>
        <div class="tab-item" onclick="filterByStatus('paid', this)">Đã thanh toán</div>
        <div class="tab-item" onclick="filterByStatus('cancelled', this)">Đã hủy</div>
    </div>

    <div id="order-wrapper">
        @forelse($displayOrders as $order)
        <div class="order-block" data-status="{{ $order->status }}">
            <div class="order-info-header">
                <div class="font-weight-bold text-muted">📅 {{ $order->created_at->format('d/m/Y H:i') }}</div>
                
                <div class="status-badge">
                    @if($order->status == 'paid')
                        <span class="badge badge-success badge-status">
                            <i class="fas fa-check-circle"></i> ĐÃ THANH TOÁN
                        </span>
                    @elseif($order->status == 'pending')
                        <span class="badge badge-warning badge-status" style="background-color: #f0ad4e; color: white;">
                            <i class="fas fa-clock"></i> CHỜ XỬ LÝ
                        </span>
                    @elseif($order->status == 'cancelled')
                        <span class="badge badge-danger badge-status">
                            <i class="fas fa-times-circle"></i> ĐÃ HỦY
                        </span>
                    @else
                        <span class="badge badge-secondary badge-status">{{ strtoupper($order->status) }}</span>
                    @endif
                </div>
            </div>

            {{-- ĐOẠN CODE XỬ LÝ ẢNH MỚI NẰM Ở ĐÂY --}}
            @foreach($order->items as $item)
            <div class="product-row">
                @php
                    // 1. Lấy thuộc tính image từ quan hệ menu (thông qua food_id)
                    $imageName = ($item->menu && $item->menu->image) ? $item->menu->image : 'default.jpg';

                    // 2. Kiểm tra đường dẫn để asset() hiểu đúng
                    $imageUrl = str_contains($imageName, 'images/') ? asset($imageName) : asset('images/' . $imageName);
                @endphp

                <img src="{{ $imageUrl }}" 
                     class="product-img" 
                     alt="{{ $item->product_name }}"
                     onerror="this.src='{{ asset('images/default.jpg') }}'">
                
                <div class="product-detail">
                    <div class="product-name">{{ $item->product_name }}</div>
                    <div class="product-qty">Số lượng: {{ $item->quantity }}</div>
                </div>
                
                <div class="font-weight-bold">
                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                </div>
            </div>
            @endforeach
            {{-- KẾT THÚC ĐOẠN CODE ẢNH --}}

            <div class="order-footer">
    <div class="total-info">
        <span class="text-muted">Tổng thanh toán:</span>
        <span class="total-price-all">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
    </div>
    <button class="btn-action btn-view" onclick="toggleDetail('detail-{{ $order->id }}')">
        Xem chi tiết đơn
    </button>
</div>

<div id="detail-{{ $order->id }}" style="display: none; padding: 20px; border-top: 1px dashed #eee; background: #fdfdfd;">
    <div class="row">
        <div class="col-md-6">
            <h5 style="font-weight: bold; color: #d9534f;">Thông tin khách hàng</h5>
            <p><strong>Họ tên:</strong> {{ $order->name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Bàn số:</strong> {{ $order->table_number }}</p>
            
            <p><strong>Ghi chú:</strong> {{ $order->notes ?? 'Không có' }}</p>
        </div>
        <div class="col-md-6 text-right">
            <h5 style="font-weight: bold; color: #d9534f;">Thanh toán & Trạng thái</h5>
            
            <p><strong>Phương thức:</strong> 
                @if(strtoupper($order->payment_method) == 'BANK' || (isset($order->notes) && str_contains($order->notes, 'QR')))
                    <span class="label label-primary" style="background-color: #3498db; color: white; padding: 2px 8px; border-radius: 4px;">Chuyển khoản QR</span>
                @else
                    <span class="label label-default" style="background-color: #95a5a6; color: white; padding: 2px 8px; border-radius: 4px;">Tiền mặt (Tại quầy)</span>
                @endif
            </p>

            <p><strong>Trạng thái đơn:</strong> 
                @if($order->status == 'paid')
                    <span class="text-success" style="font-weight: bold;">Đã thanh toán</span>
                @elseif($order->status == 'pending')
                    <span class="text-warning" style="font-weight: bold;">Chờ xử lý</span>
                @else
                    <span class="text-danger" style="font-weight: bold;">{{ $order->status }}</span>
                @endif
            </p>
        </div>
    </div>
</div>
        </div>
        @empty
        <div class="text-center py-5">
            <p>Bạn chưa có đơn hàng nào.</p>
        </div>
        @endforelse
    </div>

    <div id="no-orders" class="empty-state">
        <p>Không có đơn hàng nào ở trạng thái này.</p>
    </div>
</div>

<script>
function filterByStatus(status, element) {
    document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
    element.classList.add('active');

    let orders = document.querySelectorAll('.order-block');
    let visibleCount = 0;

    orders.forEach(order => {
        if (status === 'all' || order.getAttribute('data-status') === status) {
            order.style.display = 'block';
            visibleCount++;
        } else {
            order.style.display = 'none';
        }
    });

    document.getElementById('no-orders').style.display = (visibleCount === 0) ? 'block' : 'none';
}
// THÊM ĐOẠN NÀY VÀO DƯỚI ĐÂY
function toggleDetail(id) {
    let detailBox = document.getElementById(id);
    if (detailBox.style.display === "none" || detailBox.style.display === "") {
        detailBox.style.display = "block";
    } else {
        detailBox.style.display = "none";
    }
}
</script>
@endsection