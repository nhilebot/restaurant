@extends('shared')

@section('title', '{{ $menu->name }} - Chi tiết món ăn')

@section('head')
<style>
        /* GIỮ NGUYÊN STYLE CỦA BẠN VÀ THÊM PHẦN ĐỒNG BỘ */
        body { font-family: 'Arial', sans-serif; background-color: #fff; padding-top: 100px; }
        /* Navbar đen đồng bộ */
        .navbar-default { background-color: #222 !important; border: none !important; }
        .navbar-brand { font-family: 'Pacifico' !important; font-size: 28px !important; color: #ffffff !important; }
        .nav li a { color: #fff !important; font-weight: 600; }
        .nav li a:hover { color: #e74c3c !important; }
        /* Style nội dung của bạn */
        .product-container { margin-top: 50px; margin-bottom: 50px; }
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
            background-color: #d9534f; color: white !important; padding: 12px 35px;
            font-size: 18px; border: none; border-radius: 6px; transition: 0.3s;
            text-decoration: none; display: inline-block; cursor: pointer;
        }
        .btn-order:hover { background-color: #c9302c; text-decoration: none; }
        .login-notice { color: #d9534f; font-weight: bold; margin-bottom: 15px; }
        .btn-home { background-color: #6c757d; color: white !important; }
    </style>
@endsection

@section('content')
<div class="container product-container">
    <div class="row">
        <div class="col-md-6 image-section">
            <div style="position: relative; display: inline-block;">
                <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}">
                <div class="sale-tag">SALE<br>50%<br>OFF</div>
            </div>
        </div>

        <div class="col-md-6">
            <h1 style="font-family: 'Playball', cursive; color: #333; margin-top: 0; margin-bottom: 25px;">
    {{ $menu->name }}
</h1>

            <div class="product-info-box" style="background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <div class="price-bar">
                    <span class="price-text">{{ number_format($menu->price, 0, ',', '.') }} VNĐ</span>
                    <span class="label label-danger" style="margin-left: 15px;">5 <i class="fa fa-star"></i></span>
                    <span style="margin-left: 15px; color: #e74c3c;"><i class="fa fa-heart"></i> 200+ Thích</span>
                </div>

                <p style="font-size: 16px; color: #666; line-height: 1.8; margin-bottom: 30px;">
                    {{ $menu->description ?? 'Đại tiệc ẩm thực tươi ngon mang hương vị biển cả đến bàn ăn của bạn.' }}
                </p>

                <form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="cart-action">
    <label>Số lượng:</label>
    <input type="number" id="quantity" value="1" min="1" class="form-control" style="width: 80px; display: inline-block; margin-bottom: 10px;">
    
    <br>
    
    <button type="button" class="btn-order" onclick="addToCart({{ $menu->id }})" style="background-color: #e74c3c; color: white; border: none; padding: 10px 20px; border-radius: 5px;">
        THÊM VÀO GIỎ HÀNG
    </button>
</div>

<script>
function addToCart(foodId) {
    let qty = document.getElementById('quantity').value;

    $.ajax({
        url: "{{ route('cart.add') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            menu_id: foodId,
            quantity: qty
        },
        success: function(response) {
            // Thông báo thành công
            Swal.fire({
                icon: 'success',
                title: 'Đã thêm vào giỏ!',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            });

            // Cập nhật số lượng trên icon giỏ hàng (nếu bạn có class .badge ở navbar)
            if(response.cart_count !== undefined) {
                $('.badge').text(response.cart_count);
            }
        },
        error: function(xhr) {
            let errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Có lỗi xảy ra!';
            Swal.fire({
                icon: 'error',
                title: 'Lỗi...',
                text: errorMsg,
            });
        }
    });
}
</script>
</form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
@endsection
