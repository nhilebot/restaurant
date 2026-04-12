@extends('shared')

@section('title', 'Thực đơn chi tiết - Restaurant')

@section('head')
<style>
        body { background-color: #f9f9f9; padding-top: 100px; }
        /* XÓA VẠCH KẺ ĐEN CỦA NAVBAR */
        .navbar-default {
            background-color: #fff;
            border: none !important; /* Xóa viền mặc định */
            box-shadow: none !important; /* Xóa bóng đổ nếu có */
        }
        /* CHỈNH TIÊU ĐỀ NẰM TRÊN 1 HÀNG DUY NHẤT */
       /* CHỈNH TIÊU ĐỀ NẰM TRÊN 1 HÀNG VÀ ẨN KHUNG KẺ */
    .section-title {
        font-family: 'Pacifico', cursive;
        font-size: 60px;
        color: #e74c3c;
        margin-top: 20px;
        margin-bottom: 40px;
        font-weight: normal;
        text-transform: none;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        /* Ép 1 hàng */
        white-space: nowrap;      
        display: block;           
        width: 100%;              
        overflow: visible;        
        text-align: center;
        /* TRIỆT TIÊU VẠCH KẺ ĐEN */
        border: none !important;    /* Xóa mọi loại viền */
        outline: none !important;   /* Xóa đường viền tập trung */
    }
        @media (max-width: 768px) {
            .section-title {
                font-size: 40px; 
            }
        }
        /* Các style khác giữ nguyên */
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
        }
        .menu-card { 
            background: #fff;
            margin-bottom: 30px; 
            text-align: center; 
            transition: 0.4s;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .menu-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .product-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 45% 55% 45% 55% / 55% 45% 55% 45%; 
            border: 6px solid #fff2f2;
            transition: 0.5s;
        }
        .menu-card:hover .product-img {
            border-color: #e74c3c;
            transform: rotate(5deg);
        }
        .product-name { 
            font-family: 'Playball', cursive;
            font-size: 22px; 
            color: #333; 
            margin-top: 15px; 
        }
        .price-text { 
            font-size: 18px; 
            color: #e74c3c; 
            font-weight: bold; 
            margin: 8px 0; 
        }
        .btn-detail {
            background-color: #e74c3c;
            color: white !important;
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            border: none;
            transition: 0.3s;
        }

        .btn-detail:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .btn-cart {
            background-color: #27ae60;
            color: white !important;
            padding: 8px 16px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            border: none;
            transition: 0.3s;
            margin-left: 5px;
        }

        .btn-cart:hover {
            background-color: #229954;
            transform: scale(1.05);
        }

        .btn-cart i {
            margin-right: 5px;
        }

        .menu-card-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        /* Toast notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-notification.error {
            background: #e74c3c;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 30px 0;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a, .pagination span {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            display: block;
        }

        .pagination a:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        .pagination .active span {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="section-title">Khám Phá Thực Đơn</h1>
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

    <div class="row">
        @foreach($menus as $menu)
        <div class="col-md-3 col-sm-6">
            <div class="menu-card">
                <a href="{{ route('menu.detail', $menu->id) }}">
                    <img src="{{ asset($menu->image) }}" class="product-img" alt="{{ $menu->name }}">
                </a>
                <h4 class="product-name">{{ $menu->name }}</h4>
                <p class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</p>
                <div class="menu-card-buttons">
                    <a href="{{ route('menu.detail', $menu->id) }}" class="btn-detail">Xem Chi Tiết</a>
                    <button class="btn-cart add-to-cart-btn" data-food-id="{{ $menu->id }}" data-food-name="{{ $menu->name }}">
                        <i class="fa fa-shopping-cart"></i> Thêm
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>

<script>
$(document).ready(function() {
    console.log('Menu page loaded, jQuery version:', $.fn.jquery);
    console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
    console.log('Found', $('.add-to-cart-btn').length, 'add to cart buttons');
    
    // Test click event
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        console.log('Button clicked!');
        
        var foodId = $(this).data('food-id');
        var foodName = $(this).data('food-name');
        var button = $(this);
        
        console.log('Food ID:', foodId, 'Food Name:', foodName);
        console.log('Route URL:', '{{ route("cart.add") }}');
        
        // Disable button để tránh spam click
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...');
        
        // Gửi AJAX request
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                food_id: foodId,
                quantity: 1
            },
            success: function(response) {
                console.log('AJAX success:', response);
                // Hiển thị thông báo thành công
                showToast('✓ ' + response.message, 'success');
                
                // Cập nhật số lượng giỏ hàng trong navbar nếu có
                if (response.cart_count) {
                    updateCartCount(response.cart_count);
                }
                
                // Reset button
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm');
            },
            error: function(xhr, status, error) {
                console.log('AJAX error:', xhr.status, xhr.responseText, status, error);
                var errorMessage = 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
                
                if (xhr.status === 401) {
                    errorMessage = 'Bạn cần đăng nhập để thêm món vào giỏ hàng!';
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                
                showToast('✗ ' + errorMessage, 'error');
                
                // Reset button
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm');
            }
        });
    });
    
    // Hàm hiển thị toast notification
    function showToast(message, type) {
        console.log('Showing toast:', message, type);
        // Xóa toast cũ nếu có
        $('.toast-notification').remove();
        
        // Tạo toast mới
        var toastClass = type === 'error' ? 'toast-notification error' : 'toast-notification';
        var toast = $('<div class="' + toastClass + '">' + message + '</div>');
        
        // Thêm vào body
        $('body').append(toast);
        
        // Hiển thị toast
        setTimeout(function() {
            toast.addClass('show');
        }, 100);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            toast.removeClass('show');
            setTimeout(function() {
                toast.remove();
            }, 300);
        }, 3000);
    }
    
    // Hàm cập nhật số lượng giỏ hàng
    function updateCartCount(count) {
        console.log('Updating cart count to:', count);
        var cartBadge = $('.navbar .badge');
        if (cartBadge.length) {
            cartBadge.text(count);
        }
    }
});
</script>
@endsection
