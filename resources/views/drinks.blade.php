@extends('shared')

@section('title', 'Thực đơn Đồ uống - Restaurant')

@section('head')
<style>
    body { background-color: #f9f9f9; padding-top: 100px; }
    
    .navbar-default {
        background-color: #fff;
        border: none !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
    }

    .section-title {
        font-family: 'Pacifico', cursive;
        font-size: 60px;
        color: #e74c3c;
        margin-top: 20px;
        margin-bottom: 10px;
        font-weight: normal;
        text-align: center; 
        display: block;
        width: 100%;
    }

    @media (max-width: 768px) {
        .section-title { font-size: 40px; }
    }

    .section-subtitle {
        text-align: center;
        color: #777;
        max-width: 700px;
        margin: 0 auto 40px auto;
        font-size: 16px;
        line-height: 1.6;
    }

    /* ĐỒNG BỘ CATEGORY NAV */
    .category-nav { margin-bottom: 50px; text-align: center; }
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

    /* ĐỒNG BỘ GRID 4 CỘT */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        padding: 20px 0;
    }

    @media (max-width: 1024px) { .menu-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; } }
    @media (max-width: 480px) { .menu-grid { grid-template-columns: repeat(1, 1fr); } }

    /* ĐỒNG BỘ CARD MÓN ĂN */
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

    /* KHUNG HÌNH VUÔNG 1:1 */
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
        border-radius: 15px;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* HIỆU ỨNG ZOOM KHI HOVER */
    .menu-card:hover .product-img {
        transform: scale(1.1);
        filter: brightness(1.05);
    }

    .product-name {
        font-family: 'Playball', cursive;
        font-size: 22px;
        color: #333;
        margin: 10px 0 5px 0;
        /* font-weight: 500; */
        height: 30px;
        overflow: hidden;
    }

    .price-text {
        font-size: 18px;
        color: #e74c3c;
        font-weight: 800;
        margin-bottom: 15px;
    }

    /* ĐỒNG BỘ CỤM NÚT */
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
        text-decoration: none !important;
        transition: all 0.3s ease;
        border: none;
        text-transform: uppercase;
        flex: 1;
    }

    .btn-detail-red { background-color: #e74c3c; color: white !important; }
    .btn-add-green { background-color: #27ae60; color: white !important; }

    /* NHÃN TRANG TRÍ RIÊNG CHO ĐỒ UỐNG (REFRESH) */
    .badge-drinks {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        padding: 8px 6px;
        border-radius: 50%;
        font-weight: bold;
        font-size: 8px;
        z-index: 2;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        border: 2px solid #fff;
        line-height: 1.2;
    }

    /* TOAST */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #27ae60;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 9999;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }
    .toast-notification.show { opacity: 1; transform: translateX(0); }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="section-title">Thức uống</h1>
            <p class="section-subtitle">
                Giải nhiệt tức thì với quầy nước phong phú từ nước ép trái cây tươi, trà thanh mát đến các loại cà phê đậm đà hương vị.
            </p>
        </div>
    </div>
    
    <div class="category-nav">
        <a href="{{ url('/menu') }}" class="btn-category">Tất cả</a>
        <a href="{{ url('/seafood') }}" class="btn-category">Hải sản</a>
        <a href="{{ url('/special') }}" class="btn-category">Món đặc biệt</a>
        <a href="{{ url('/salad') }}" class="btn-category">Salad</a>
        <a href="{{ url('/vietnamese') }}" class="btn-category">Món Việt</a>
        <a href="{{ url('/desserts') }}" class="btn-category">Tráng miệng</a>
        <a href="{{ url('/drinks') }}" class="btn-category active">Đồ uống</a>
    </div>

    <div class="menu-grid">
        @foreach($menus as $menu)
        <div class="menu-card">

            
            <div class="img-container">
                <a href="{{ route('menu.detail', $menu->id) }}">
                    <img src="{{ asset($menu->image) }}" class="product-img" alt="{{ $menu->name }}">
                </a>
            </div>

            <h4 class="product-name">{{ $menu->name }}</h4>
            <p class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</p>
            
            <div class="card-buttons">
                <a href="{{ route('menu.detail', $menu->id) }}" class="btn-action btn-detail-red">Chi Tiết</a>
                <button class="btn-action btn-add-green add-to-cart-btn" data-food-id="{{ $menu->id }}">
                    <i class="fa fa-shopping-cart"></i> Thêm
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        var foodId = $(this).data('food-id');
        var button = $(this);
        
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { menu_id: foodId, quantity: 1 },
            success: function(response) {
                showToast('✓ ' + response.message);
                if (response.cart_count) {
                    $('.navbar .badge').text(response.cart_count);
                }
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm');
            },
            error: function(xhr) {
                var msg = xhr.status === 401 ? 'Vui lòng đăng nhập!' : 'Lỗi xảy ra!';
                showToast('✗ ' + msg, 'error');
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm');
            }
        });
    });

    function showToast(message, type = 'success') {
        $('.toast-notification').remove();
        var toastClass = type === 'error' ? 'toast-notification error' : 'toast-notification';
        var toast = $('<div class="' + toastClass + '">' + message + '</div>');
        $('body').append(toast);
        setTimeout(function() { toast.addClass('show'); }, 100);
        setTimeout(function() {
            toast.removeClass('show');
            setTimeout(function() { toast.remove(); }, 300);
        }, 3000);
    }
});
</script>
@endsection