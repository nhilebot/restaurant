@extends('layouts.admin')

@section('title', 'Bảng điều khiển')
@section('page_title', 'Bảng điều khiển')

@section('topbar_actions')
    <a href="{{ url('/') }}" class="button button-secondary">Trang chủ</a>
@endsection

@section('content')
    <div class="dashboard-grid">
        <article class="stat-card">
            <div class="stat-label">Tổng đơn hàng</div>
            <div class="stat-value">{{ $orders->count() }}</div>
            <div class="stat-meta"><span class="dot"></span> Tất cả đơn hàng</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Doanh thu</div>
            <div class="stat-value">{{ number_format($orders->sum('total_amount') ?? 0, 0, ',', '.') }} ₫</div>
            <div class="stat-meta"><span class="dot"></span> Tổng doanh thu đơn</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Đơn đang xử lý</div>
            <div class="stat-value">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="stat-meta"><span class="dot"></span> Chờ phục vụ</div>
        </article>
        <article class="stat-card">
            <div class="stat-label">Số món</div>
            <div class="stat-value">{{ $menus->count() }}</div>
            <div class="stat-meta"><span class="dot"></span> Thực đơn hiện có</div>
        </article>
    </div>

    <section class="panel">
        <div class="panel-header">
            <div class="panel-heading">Đơn hàng gần đây</div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders->sortByDesc('created_at')->take(8) as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Khách' }}</td>
                                <td>{{ number_format($order->total_amount ?? 0, 0, ',', '.') }} ₫</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="order-status pending">Chờ xác nhận</span>
                                    @elseif($order->status === 'serving')
                                        <span class="order-status serving">Đang phục vụ</span>
                                    @elseif($order->status === 'paid')
                                        <span class="order-status paid">Đã thanh toán</span>
                                    @else
                                        <span class="order-status pending">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at?->format('d/m/Y H:i') ?? 'Chưa có' }}</td>
                                <td>
                                    <a href="{{ url('/orders/' . $order->id) }}" class="button button-sm">Xem</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 24px; color: #667085;">Chưa có đơn hàng nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
