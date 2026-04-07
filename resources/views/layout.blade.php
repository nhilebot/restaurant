<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Restaurant</title>
        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
<link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}">
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
<link rel="icon" href="{{ asset('favicon-1.ico') }}" type="image/x-icon">

        <!--  -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
<style>
/* Dropdown custom KHÔNG sửa HTML */
/* ===== NAVBAR KHÔNG XUỐNG DÒNG ===== */

.navbar-nav li {
    position: relative;
}

/* Ẩn menu con */
.navbar-nav li ul.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #f1ece6;
    border-radius: 12px;
    padding: 10px 0;
    min-width: 220px;
    border: none;
    z-index: 999;
}

/* Item */
.navbar-nav li ul.dropdown-menu li a {
    color: #2c3e50 !important;
    padding: 10px 20px;
    display: block;
    transition: 0.3s;
}

/* Hover đẹp */
.navbar-nav li ul.dropdown-menu li a:hover {
    background-color: #e0d8cf;
    color: #e74c3c !important;
}

/* Hover xổ */
.navbar-nav li:hover ul.dropdown-menu {
    display: block;
}

/* ===== DROPDOWN ===== */
/* ===== DROPDOWN ===== */
.dropdown-custom {
    position: relative;
}

/* menu con */
.dropdown-custom .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;

    background: #e9e4dd; /* nền be giống ảnh */
    border-radius: 14px;
    padding: 12px 0;
    min-width: 240px;
    border: none;

    box-shadow: 0 10px 25px rgba(0,0,0,0.15); /* đổ bóng */
    
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: 0.3s;
    z-index: 999;
}

/* item */
.dropdown-custom .dropdown-menu li a {
    display: block;
    padding: 10px 20px;
    color: #2c5c7c; /* xanh giống ảnh */
    font-weight: 500;
    border-radius: 8px;
    margin: 2px 10px;
    transition: 0.3s;
}

/* hover item */
.dropdown-custom .dropdown-menu li a:hover {
    background: #dcd6ce; /* xám be */
    color: #e74c3c; /* đỏ */
}

/* hover xổ */
.dropdown-custom:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
/* ===== FIX NAVBAR ĐÈ NỘI DUNG ===== */
body {
    padding-top: 70px;
}
.dropdown-custom:hover .dropdown-menu {
    display: block;
}
/* Badge giỏ hàng */
.badge {
    padding: 3px 6px;
    border-radius: 50%;
    font-family: sans-serif;
}

/* Fix khoảng cách icon */
.nav i.fa {
    margin-right: 5px;
    font-size: 16px;
}

/* Đảm bảo dropdown user hoạt động giống dropdown thực đơn */
.dropdown-custom:hover .dropdown-menu {
    display: block;
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Màu riêng cho nút đăng xuất trong dropdown */
.dropdown-menu li a i.fa-sign-out {
    color: #e74c3c;
}
</style>
    <body>

<!-- mới thêm -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI (datepicker nằm ở đây) -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- JS của bạn -->
<script src="{{ asset('js/main.js') }}"></script>

        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="row">
                <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Restaurant</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav main-nav clear navbar-right">
    <li><a class="navactive color_animation" href="{{ url('/') }}">Trang chủ</a></li>
    <li class="dropdown-custom">
        <a class="color_animation" href="#">Thực đơn</a>
        </li>
    <li><a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>

    <li>
        <a class="color_animation" href="{{ url('/cart') }}" style="position: relative;">
            <i class="fa fa-shopping-cart"></i>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="badge" style="background: #96E16B; color: #000; position: absolute; top: 0; right: 0; font-size: 10px;">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>
    </li>

    @guest
        <li><a class="color_animation" href="{{ route('login') }}">Đăng nhập</a></li>
        <li><a class="color_animation" href="{{ route('register') }}">Đăng ký</a></li>
    @endguest

    @auth
        <li class="dropdown-custom">
            <a class="color_animation" href="#">
                <i class="fa fa-user"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu">
                @if(Auth::user()->role == 'admin')
                    <li><a href="{{ url('/admin') }}">Quản trị viên</a></li>
                @endif
                <li><a href="{{ url('/profile') }}">Hồ sơ của tôi</a></li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #e74c3c !important;">
                        <i class="fa fa-sign-out"></i> Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    @endauth
</ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </div><!-- /.container-fluid -->
        </nav>
         
        <div id="top" class="starter_container bg">
            <div class="follow_container">
                <div class="col-md-6 col-md-offset-3">
                    <h2 class="top-title"> Nhà Hàng </h2>
                    <h2 class="white second-title">" Tốt nhất trong Thành Phố "</h2>
                    <hr>
                </div>
            </div>
        </div>

        <!-- ============ About Us ============= -->

        <section id="story" class="description_content">
            <div class="text-content container">
                <div class="col-md-6">
                    <h1>Về chúng tôi</h1>
                    <div class="fa fa-cutlery fa-2x"></div>
                    <p class="desc-text">Nhà hàng là nơi cho sự đơn giản. Thức ăn ngon, bia ngon và dịch vụ tốt. Đơn giản là tên của trò chơi và chúng tôi rất giỏi trong việc tìm thấy nó ở tất cả những nơi phù hợp, ngay cả trong trải nghiệm ăn uống của bạn. Chúng tôi là một nhóm nhỏ đến từ Denver, Colorado, những người làm cho những món ăn đơn giản trở nên khả thi. Hãy tham gia cùng chúng tôi và xem sự đơn giản có hương vị như thế nào.</p>
                </div>
                <div class="col-md-6">
                    <div class="img-section">
                       <img src="images/kabob.jpg" width="250" height="220">
                       <img src="images/limes.jpg" width="250" height="220">
                       <div class="img-section-space"></div>
                       <img src="images/radish.jpg"  width="250" height="220">
                       <img src="images/corn.jpg"  width="250" height="220">
                   </div>
                </div>
            </div>
        </section>


       <!-- ============ Pricing  ============= -->


       
        


        <!-- ============ Our Beer  ============= -->


        


        
        <section id="featured" class="description_content">
            <div  class="featured background_content">
<!-- nổi bật click vào menu -->
            <div class="action-buttons">
        <a href="{{ url('/menu') }}" class="btn-main">Xem Menu</a>
        <a href="{{ url('/reservation') }}" class="btn-main">Đặt bàn</a>
    </div>

        </div>
            <div class="text-content container"> 
                <div class="col-md-6">
                    <h1>Hãy xem các món ăn của chúng tôi!</h1>
                    <div class="icon-hotdog fa-2x"></div>
                    <p class="desc-text">Mỗi món ăn đều được làm thủ công vào lúc bình minh, chỉ sử dụng những nguyên liệu đơn giản nhất để mang lại mùi và hương vị vẫy gọi cả khối. Dừng lại bất cứ lúc nào và trải nghiệm sự đơn giản ở mức tốt nhất.</p>
                </div>
                <div class="col-md-6">
                    <ul class="image_box_story2">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="images/slider1.jpg"  alt="...">
                                    <div class="carousel-caption">
                                        
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="images/slider2.jpg" alt="...">
                                    <div class="carousel-caption">
                                        
                                    </div>
                                </div>
                                <div class="item">
                                    <img src="images/slider3.JPG" alt="...">
                                    <div class="carousel-caption">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </section>

        

        <!-- ============ Reservation  ============= -->

        

        <!-- ============ Social Section  ============= -->
      
        

        <!-- ============ Contact Section  ============= -->

        

        <!-- ============ Footer Section  ============= -->

        <footer class="sub_footer">
            <div class="container">
                <div class="col-md-4"><p class="sub-footer-text text-center">&copy; Restaurant 2014, Theme by <a href="https://themewagon.com/">ThemeWagon</a></p></div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Back to <a href="#top">TOP</a></p>
                </div>
                <div class="col-md-4"><p class="sub-footer-text text-center">Built With Care By <a href="#" target="_blank">Us</a></p></div>
            </div>
        </footer>


        <!-- jQuery MỚI -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js"></script>

        <!-- jQuery UI (datepicker) -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

        <!-- Plugin -->
        <script src="js/jquery.mixitup.min.js"></script>

        <!-- Main -->
        <script src="{{ asset('js/main.js') }}"></script>

    </body>
    <script>
$(document).ready(function () {

    // tìm link có chữ "Thực đơn"
    $('.navbar-nav li a').each(function () {
        if ($(this).text().trim() === 'Thực đơn') {

            let parent = $(this).parent();

            // tránh tạo trùng
            if (parent.find('.dropdown-menu').length === 0) {

                let dropdown = `
                <ul class="dropdown-menu">
                    <li><a class="color_animation" href="{{ url('/menu') }}">Thực đơn chi tiết</a></li>
                    <li><a class="color_animation" href="{{ url('/seafood') }}">Hải sản</a></li>
                    <li><a class="color_animation" href="{{ url('/special') }}">Món đặc biệt</a></li>
                    <li><a class="color_animation" href="{{ url('/salad') }}">Salad</a></li>
                    <li><a class="color_animation" href="{{ url('/vietnamese') }}">Món Việt</a></li>
                    <li><a class="color_animation" href="{{ url('/desserts') }}">Tráng miệng</a></li>
                    <li><a class="color_animation" href="{{ url('/drinks') }}">Thức uống</a></li>
                </ul>
                `;

                parent.append(dropdown);
            }
        }
    });

});
</script>
</html>