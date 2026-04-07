<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-portfolio.css">
    <link rel="stylesheet" href="css/picto-foundry-food.css" />
    <link rel="stylesheet" href="css/jquery-ui.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="icon" href="favicon-1.ico" type="image/x-icon">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">Restaurant </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav main-nav  clear navbar-right ">
                            <li><a class="navactive color_animation" href="{{ url('/') }}">Trang chủ</a></li>
                             <li><a class="color_animation" href="">Thực đơn</a></li> 
                            <li><a class="color_animation" href="{{ url('/about') }}">Giới thiệu</a></li>
                            <li><a class="color_animation" href="{{ url('/featured') }}">Nổi bật</a></li>
                            <li><a class="color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>
                            <li><a class="color_animation" href="{{ url('/contact') }}">Liên hệ</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
    </div>
</nav>

<!-- Banner -->
<section class="hero-banner">
    <div class="overlay"></div>
    
</section>
<section class="menu-header">
    <div class="container text-center">
        <h2>Thực đơn tham khảo theo các bếp</h2>

        <div class="line"></div>

        <p>
            Chúng tôi mang đến bạn thực đơn đầy màu sắc hơn <b>250</b> món ăn
            đa dạng từ các nền ẩm thực nổi tiếng trên thế giới, được chế biến
            từ những nguyên liệu tươi ngon và chất lượng.
        </p>

        <div class="line"></div>
    </div>
</section>
<!-- Menu Section -->
<section class="menu-section">
    <div class="container">
        <div class="row">
            <!-- Menu Item 1 -->
           <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/monviet.jpg') }}" alt="Food" >
                    <h4>Món Việt</h4>
                </div>
            </div>
            <!-- Menu Item 2 -->
             <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/mondacbiet.jpg') }}" alt="Food" >
                    <h4>Món Đặc Biệt</h4>
                </div>
            </div>
            <!-- Menu Item 3 -->
          
            <!-- Menu Item 4 -->
            <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/haisan.jpg') }}" alt="Food" >
                    <h4>Hải sản</h4>
                </div>
            </div>

            

            <!-- Menu Item 5 -->
            <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/salad.jpg') }}" alt="Food" >
                    <h4>Salad</h4>
                </div>
            </div>
            <!-- Menu Item 6 -->
            <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/trangmieng.jpg') }}" alt="Food" >
                    <h4>Tráng Miệng</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="menu-item">
                    <img src="{{ asset('images/thucuong.jpg') }}" alt="Food" >
                    <h4>Thức Uống</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white p-4 text-center">
    &copy; 2026 Restaurant . All rights reserved.
</footer>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
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
                    <li><a class="color_animation" href="{{ url('/menuchitiet') }}">Thực đơn chi tiết</a></li>
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
<style>
    /* Reset cơ bản */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Body */
body {
    background-color: #f9f9f9;
    color: #333;
}

/* Header */
header {
    background-color: #222;
    color: #fff;
    padding: 20px 0;
}
header h2 {
    font-size: 1.8rem;
    margin: 0;
}
header nav a {
    color: #fff;
    text-decoration: none;
    margin-right: 20px;
    font-weight: 500;
    transition: color 0.3s;
}
header nav a:hover {
    color: #e74c3c;
}

/* Footer */
footer {
    background-color: #222;
    color: #fff;
    padding: 30px 0;
    text-align: center;
    font-size: 0.9rem;
}

/* Menu Section */
.menu-section {
    padding: 60px 0;
}

/* Menu Item */
.menu-item {
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 15px;
}

.menu-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    border-bottom: 2px solid #e74c3c;
}

.menu-item h4 {
    font-size: 1.2rem;
    margin-top: 15px;
    color: #333;
}

.menu-item p {
    font-size: 1rem;
    color: #e74c3c;
    font-weight: bold;
    margin-top: 8px;
}

/* Hover Effect */
.menu-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 25px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 991px) {
    .menu-item img {
        height: 200px;
    }
}

@media (max-width: 767px) {
    header nav a {
        display: block;
        margin: 10px 0;
    }
    .menu-item img {
        height: 180px;
    }
}
/* ===== FIX NAVBAR KHÔNG ĐÈ ===== */
body {
    padding-top: 70px; /* tránh navbar đè */
}

/* ===== HERO BANNER ===== */
.hero-banner {
    position: relative;
    width: 100%;
    height: 420px;
    background: url('images/banner.jpg') no-repeat center center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

/* Lớp phủ tối */
.hero-banner .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.45);
}

/* Nội dung */
.hero-content {
    position: relative;
    color: #fff;
    max-width: 800px;
    padding: 20px;
}

.hero-content h1 {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 15px;
}

.hero-content p {
    font-size: 18px;
    line-height: 1.6;
}

/* ===== MENU SECTION ===== */
.menu-section {
    padding: 60px 0;
    margin-top: 20px;
}

/* ===== HEADER MENU (GIỐNG HÌNH BẠN) ===== */
.menu-header {
    background: #f5f5f5;
    padding: 60px 20px;
    text-align: center;
}

.menu-header h2 {
    font-size: 40px;
    color: #2c5c7c; /* xanh giống mẫu */
    margin-bottom: 20px;
}

.menu-header p {
    max-width: 700px;
    margin: 20px auto;
    font-size: 24px;
    line-height: 2;
    color: #444;
}

.menu-header .line {
    width: 700px;
    height: 1px;
    background: #2c5c7c;
    margin: 15px auto;
}
.menu-item {
    height: 320px; /* cố định chiều cao */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>