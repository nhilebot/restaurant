@extends('shared')

@section('title', 'Khám phá thực đơn - Restaurant')

@section('head')
<style>
    body { background-color: #f9f9f9; padding-top: 100px; }

    /* XÓA VẠCH KẺ NAVBAR */
    .navbar-default {
        background-color: #fff;
        border: none !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
    }

    /* TIÊU ĐỀ SECTION */
    .section-title {
        font-family: 'Pacifico', cursive;
        font-size: 60px;
        color: #e74c3c;
        margin-top: 20px;
        margin-bottom: 40px;
        font-weight: normal;
        text-align: center;
        display: block;
        width: 100%;
        border: none !important;
    }

    @media (max-width: 768px) {
        .section-title { font-size: 40px; }
    }

    /* ĐIỀU HƯỚNG DANH MỤC */
    .category-nav { margin-bottom: 40px; text-align: center; }
    .btn-category {
        padding: 10px 22px;
        margin: 5px;
        border-radius: 30px;
        text-transform: uppercase;
        font-weight: bold;
        transition: 0.3s;
        border: 2px solid #e74c3c;
        color: #e74c3c;
        display: inline-block;
        text-decoration: none;
        font-size: 13px;
    }
    .btn-category:hover, .btn-category.active {
        background: #e74c3c;
        color: white !important;
        text-decoration: none;
    }

    /* HỆ THỐNG GRID 4 CỘT */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        padding: 20px 0;
    }

    @media (max-width: 1024px) { .menu-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
    @media (max-width: 480px) { .menu-grid { grid-template-columns: repeat(1, 1fr); } }

    /* CARD MÓN ĂN ĐỒNG BỘ */
    .menu-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02), 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 5px 10px rgba(0,0,0,0.05), 0 20px 40px rgba(0,0,0,0.1);
    }

    /* KHUNG ẢNH VUÔNG 1:1 */
    .img-container {
        position: relative;
        width: 100%;
        padding-bottom: 100%; 
        margin-bottom: 15px;
        overflow: hidden;
        border-radius: 15px;
        background-color: #fcfcfc;
    }

    .product-img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover; 
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .menu-card:hover .product-img {
        transform: scale(1.1);
    }

    /* THÔNG TIN MÓN */
    .product-name {
        font-family: 'Playball', cursive;
        font-size: 22px;
        color: #333;
        margin: 10px 0 5px 0;
        height: 30px;
        overflow: hidden;
        /* font-weight: 500; */
    }

    .price-text {
        font-size: 18px;
        color: #e74c3c;
        font-weight: 800;
        margin-bottom: 15px;
    }

    /* NÚT BẤM */
    .card-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: auto;
    }

    .btn-action {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        border: none;
        transition: 0.3s;
        flex: 1;
    }

    .btn-detail-red { background-color: #e74c3c; color: white !important; text-decoration: none; }
    .btn-add-green { background-color: #27ae60; color: white !important; }

    /* SEARCH BOX CẢI TIẾN */
    .search-box {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 1px solid #eee;
        background: #fff;
        margin-bottom: 30px;
    }

    .search-box .form-control { border: none; height: 50px; padding-left: 25px; box-shadow: none; }
    .btn-search { background-color: #e74c3c; color: white !important; height: 50px; padding: 0 25px; font-weight: bold; border: none; }

    /* TOAST */
    .toast-notification {
        position: fixed;
        top: 20px; right: 20px;
        background: #27ae60; color: white;
        padding: 15px 20px; border-radius: 8px;
        z-index: 9999; opacity: 0; transform: translateX(100%);
        transition: all 0.3s ease;
    }
    .toast-notification.show { opacity: 1; transform: translateX(0); }
    .toast-notification.error { background: #e74c3c; }

    /* TIÊU ĐỀ DANH MỤC CON */
    .category-section-title {
        font-size: 28px;
        color: #333;
        margin: 40px 0 20px 0;
        padding-left: 15px;
        border-left: 5px solid #e74c3c;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form action="{{ url('/menu') }}" method="GET">
                <div class="input-group search-box">
                    <input type="text" name="search" class="form-control" placeholder="Hôm nay bạn muốn ăn gì..." value="{{ request('search') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-search" type="submit">
                            <i class="fa fa-search"></i> Tìm ngay
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            <h1 class="section-title">Khám phá thực đơn</h1>
        </div>
    </div>
    
    <div class="category-nav">
        <a href="{{ url('/menu') }}" class="btn-category active">Tất cả</a>
        <a href="{{ url('/seafood') }}" class="btn-category">Hải sản</a>
        <a href="{{ url('/special') }}" class="btn-category">Món đặc biệt</a>
        <a href="{{ url('/salad') }}" class="btn-category">Salad</a>
        <a href="{{ url('/vietnamese') }}" class="btn-category">Món Việt</a>
        <a href="{{ url('/desserts') }}" class="btn-category">Tráng miệng</a>
        <a href="{{ url('/drinks') }}" class="btn-category">Đồ uống</a>
    </div>

    <div class="category-container">
        @php $groupedMenus = $menus->groupBy('category'); @endphp

        @if($groupedMenus->isEmpty())
            <div class="text-center" style="padding: 50px; color: #777;">
                <h3>Không tìm thấy thực đơn nào phù hợp.</h3>
            </div>
        @else
            @foreach($groupedMenus as $category => $menuGroup)
                @php
                    $categoryMap = [
                        'seafood' => 'Hải Sản',
                        'special' => 'Món Đặc Biệt ',
                        'salad' => 'Salad ',
                        'vietnamese' => 'Hương Vị Món Việt',
                        'desserts' => 'Tráng Miệng Ngọt Ngào',
                        'drinks' => 'Thức Uống Giải Nhiệt',
                    ];
                    $categoryName = $categoryMap[strtolower($category)] ?? 'Món Ăn Khác';
                @endphp

                <h2 class="category-section-title">{{ $categoryName }}</h2>
                
                <div class="menu-grid">
                    @foreach($menuGroup as $menu)
                        <div class="menu-card">
                            <div class="img-container">
                                <a href="{{ route('menu.detail', $menu->id) }}">
                                    <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" class="product-img">
                                </a>
                            </div>

                            <h3 class="product-name">{{ $menu->name }}</h3>
                            <div class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</div>

                            <div class="card-buttons">
                                <a href="{{ route('menu.detail', $menu->id) }}" class="btn-action btn-detail-red">Chi Tiết</a>
                                <button class="btn-action btn-add-green add-to-cart-btn" data-food-id="{{ $menu->id }}">
                                    <i class="fa fa-shopping-cart"></i> Thêm
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        var foodId = $(this).data('food-id');
        var button = $(this);

        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '{{ route("reservation.addToCart") }}', 
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                food_id: foodId,
                quantity: 1
            },
            success: function(response) {
                if (response.success) {
                    showToast('✓ ' + response.message, 'success');
                    $('.badge').text(response.cartCount); 
                } else {
                    showToast('✗ ' + response.message, 'error');
                }
            },
            error: function() {
                showToast('Bạn cần đăng nhập để thực hiện!', 'error');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm');
            }
        });
    });

    function showToast(message, type) {
        $('.toast-notification').remove();
        var toastClass = type === 'error' ? 'toast-notification error' : 'toast-notification';
        var toast = $('<div class="' + toastClass + '">' + message + '</div>');
        $('body').append(toast);
        setTimeout(() => toast.addClass('show'), 100);
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>
@endsection