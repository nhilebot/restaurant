<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->name }} - Chi tiết món ăn</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; padding-top: 50px; }
        .product-container { margin-top: 50px; }
        .image-section { position: relative; text-align: center; }
        .image-section img {
            width: 450px; height: 450px; object-fit: cover;
            border-radius: 50% 40% 50% 40%; border: 10px solid #fff5f5;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .sale-tag {
            position: absolute; top: 20px; right: 15%; background: #ff0000;
            color: #fff; padding: 10px; border-radius: 50%; font-weight: bold;
            font-size: 12px; transform: rotate(-10deg); line-height: 1.2;
        }
        .info-section { padding-left: 40px; }
        .info-section h1 {
            font-size: 42px; font-weight: bold; color: #2c3e50;
            margin-bottom: 25px; border-left: 5px solid #eee; padding-left: 15px;
        }
        .price-bar {
            background-color: #fff0f3; padding: 15px 20px; border-radius: 4px;
            display: flex; align-items: center; margin-bottom: 25px;
        }
        .price-text { font-size: 26px; font-weight: bold; color: #333; margin-right: 30px; }
        .rating-badge { background: #d9534f; color: white; padding: 2px 8px; border-radius: 3px; font-size: 14px; }
        .meta-info { margin-left: 20px; color: #888; font-size: 14px; }
        .description { font-size: 18px; color: #666; margin-bottom: 30px; font-style: italic; }
        
        .quantity-row { display: flex; margin-bottom: 30px; align-items: flex-end; }
        .qty-box { margin-right: 30px; }
        .qty-box label { display: block; font-weight: bold; margin-bottom: 10px; }
        .qty-box input { width: 60px; padding: 5px; text-align: center; border: 1px solid #ddd; background: #fff5f5; }

        .btn-order {
            background-color: #d9534f; color: white; padding: 12px 35px;
            font-size: 18px; border: none; border-radius: 6px; transition: 0.3s;
            text-decoration: none; display: inline-block;
        }
        .btn-order:hover { background-color: #c9302c; color: white; cursor: pointer; }
        .login-notice { color: #d9534f; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="container product-container">
    <div class="row">
        <div class="col-md-6 image-section">
            <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}">
            <div class="sale-tag">SALE<br>50%<br>OFF</div>
        </div>

        <div class="col-md-6 info-section">
            <h1>{{ $menu->name }}</h1>

            <div class="price-bar">
                <span class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</span>
                <span class="rating-badge">5 <i class="fa fa-star"></i></span>
                <span class="meta-info"><i class="fa fa-comments"></i> Bình Luận</span>
                <span class="meta-info" style="color: #d9534f;"><i class="fa fa-heart"></i> 200+ Thích</span>
            </div>

            <p class="description">
                {{ $menu->description ?? 'Món ăn thơm ngon, bổ dưỡng, được chế biến từ hải sản tươi sống chất lượng nhất.' }}
            </p>

            @if(Auth::check())
                {{-- FORM CỦA BẠN ĐÃ ĐƯỢC TÍCH HỢP VÀO ĐÂY --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="food_id" value="{{ $menu->id }}">
                    
                    <div class="quantity-row">
                        <div class="qty-box">
                            <label>Số Lượng</label>
                            <input type="number" name="quantity" value="1" min="1">
                        </div>
                        <div class="qty-box">
                            <label>Số Lượng Có Sẵn</label>
                            <span style="background: #fff5f5; padding: 7px 20px; border: 1px solid #ddd; display: inline-block;">
                                {{ $menu->stock ?? 100 }}
                            </span>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn-order">Thêm Vào Giỏ Hàng</button>

                        <a href="{{ url('/') }}" class="btn-order" style="background-color: #6c757d; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                            🏠 Về Trang Chủ
                        </a>
                    </div>
                </form>
            @else
                <div class="quantity-row">
                    <div class="qty-box">
                        <label>Số Lượng</label>
                        <input type="number" value="1" disabled>
                    </div>
                </div>
                <p class="login-notice"><i class="fa fa-lock"></i> Vui lòng đăng nhập để đặt món ăn này.</p>
                <a href="{{ route('login') }}" class="btn-order">
                    <i class="fa fa-sign-in"></i> Đăng Nhập Để Mua Hàng
                </a>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success" style="margin-top: 20px;">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>