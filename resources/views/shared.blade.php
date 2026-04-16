<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon-1.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    @yield('head')
</head>
<body class="@yield('body-class', '')" style="display: flex; flex-direction: column; min-height: 100vh; margin: 0;">

    <div style="flex: 1 0 auto;">
        
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ url('/') }}" style="padding: 0px 0px; display: flex; align-items: center;">
                            <img src="{{ asset('images/logo.png') }}" alt="Món Việt Logo" style="height: 125px; width: auto; filter: drop-shadow(0px 0px 2px rgba(0,0,0,0.5));">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-center-custom">
                            <li><a class="color_animation" href="{{ url('/') }}">Trang chủ</a></li>
                            <li><a class="color_animation" href="{{ url('/menu') }}">Thực đơn</a></li>
                            <li><a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
    <a class="color_animation" href="{{ url('/cart') }}" style="position: relative;">
        <i class="fa fa-shopping-cart"></i>

        @php
            $count = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
        @endphp

        <span class="badge badge-danger" id="cart-count"
              style="background:red; color:white; position:absolute; top:0; right:0;">
            {{ session()->has('cart') ? count(session('cart')) : 0 }}
        </span>
    </a>
</li>
                            @guest
                                <li><a class="color_animation" href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a class="color_animation" href="{{ route('register') }}">Đăng ký</a></li>
                            @endguest
                            @auth
                                <li>
                                    <a class="color_animation" href="{{ url('/order-history') }}" title="Lịch sử đơn hàng">
                                        <i class="fa fa-file-text"></i>&nbsp; Đơn hàng
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle color_animation" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        @if(Auth::user()->role == 'admin')
                                            <li><a href="{{ url('/admin') }}">Quản trị viên</a></li>
                                        @endif
                                        <li><a href="{{ url('/profile') }}">Hồ sơ của tôi</a></li>
                                        <li>
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #e74c3c;">
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
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
        
    </div> <footer class="sub_footer" style="flex-shrink: 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h3 class="footer-logo">RESTAURANT</h3>
                    <p class="footer-desc">
                        Khởi nguồn từ niềm đam mê ẩm thực, chúng tôi mang đến những trải nghiệm chuẩn vị và đậm đà nhất, vươn mình trở thành chuỗi nhà hàng hàng đầu.
                    </p>
                </div>
                <div class="col-md-4 footer-section text-center">
                    <h3 class="footer-title">MENU</h3>
                    <ul class="footer-links">
                        <li><a href="#">Chi nhánh</a></li>
                        <li><a href="{{ url('/menu') }}">Thực đơn</a></li>
                        <li><a href="#">Khuyến mãi</a></li>
                        <li><a href="#">Thành viên</a></li>
                        <li><a href="#">Tin tức</a></li>
                    </ul>
                </div>
                <div class="col-md-4 footer-section text-right">
                    <h3 class="footer-title">LIÊN HỆ</h3>
                    <p>Hotline: 0899911390</p>
                    <p>Email: contact@restaurant.com</p>
                    <div class="footer-social">
                        <p>Theo dõi chúng tôi trên</p>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-tiktok"></i></a>
                        <a href="#"><i class="fa fa-youtube-play"></i></a>
                    </div>
                </div>
            </div>
            <div class="row footer-bottom">
                <div class="col-xs-12 text-center">
                    <hr>
                    <p>&copy; 2026 Restaurant. All Rights Reserved. Powered by YourGroup</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
</html>
