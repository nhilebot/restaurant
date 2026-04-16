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

        /* ===== SEARCH BOX STYLE ===== */
.search-container {
    margin-bottom: 40px;
}

.search-box {
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 1px solid #eee;
    background: #fff;
}

.search-box .form-control {
    border: none;
    height: 50px;
    padding-left: 25px;
    font-size: 15px;
    box-shadow: none;
}

.btn-search {
    background-color: #e74c3c;
    color: white !important;
    border: none;
    height: 50px;
    padding: 0 30px;
    font-weight: bold;
    transition: 0.3s;
    font-size: 14px;
}

.btn-search:hover {
    background-color: #c0392b;
}

    </style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="search-container">
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
    </div>
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

    <div class="row" style="display:flex; flex-wrap:wrap;">
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
                    <button type="button"
                    class="btn-cart add-to-cart-btn" 
                    data-food-id="{{ $menu->id }}"> <i class="fa fa-shopping-cart"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // CHỈ DÙNG MỘT ĐOẠN XỬ LÝ CLICK DUY NHẤT
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();

        var foodId = $(this).data('food-id');
        var button = $(this);

        // Hiệu ứng chờ khi đang xử lý
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
                    showToast('✓ ' + (response.message || 'Đã thêm vào giỏ'), 'success');
                    
                    // Cập nhật số lượng trên Navbar
                    // Kiểm tra xem ID của bạn là #cart-count hay class .badge
                    $('.badge').text(response.cartCount); 
                    $('#cart-count').text(response.cartCount); 
                } else {
                    showToast('✗ ' + response.message, 'error');
                }
            },
            error: function(xhr) {
                console.error("Lỗi:", xhr.responseText);
                showToast('✗ Lỗi hệ thống!', 'error');
            },
            complete: function() {
                // Trả lại icon giỏ hàng ban đầu
                button.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i>');
            }
        });
    });

    // HÀM HIỂN THỊ THÔNG BÁO TOAST
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
