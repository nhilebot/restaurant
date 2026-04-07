<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant - Reservation</title>
    
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" media="screen" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style-portfolio.css') }}">
    <link rel="stylesheet" href="{{ asset('css/picto-foundry-food.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="icon" href="favicon-1.ico" type="image/x-icon">

    <style>
        /* CSS Giữ nguyên như bản trước để đảm bảo giao diện đẹp */
        .reservation-section { background-color: #ffffff; padding-bottom: 60px; }
        .form-container-custom { max-width: 900px; margin: 40px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .form-title { font-family: 'Playball', cursive; color: #d9534f; font-size: 32px; text-align: center; margin-bottom: 30px; }
        .label-custom { font-weight: 600; color: #444; margin-top: 15px; display: block; margin-bottom: 8px; }
        .input-custom { height: 45px !important; border: 1px solid #ddd !important; border-radius: 5px !important; }
        .table-selection-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-bottom: 25px; }
        .table-item input[type="radio"] { display: none; }
        .table-item label { display: block; padding: 12px; border: 2px solid #eee; border-radius: 6px; text-align: center; cursor: pointer; transition: 0.2s; font-weight: 500; color: #666; }
        .table-item input[type="radio"]:checked + label { border-color: #d9534f; background-color: #d9534f; color: white; }
        .btn-reserve { background-color: #d9534f; color: white; padding: 14px; border: none; border-radius: 6px; width: 100%; font-size: 18px; font-weight: bold; text-transform: uppercase; margin-top: 20px; }
        .btn-food-select { background: white; color: #d9534f; border: 1px solid #d9534f; width: 100%; padding: 10px; margin: 10px 0; font-weight: 600; border-radius: 6px; }
        .cart-wrapper { background: #fdfaf9; padding: 15px; border-radius: 6px; border: 1px dashed #d9534f; margin-bottom: 20px; }
        .menu-item, .cart-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee; }
        .item-info { display: flex; align-items: center; gap: 15px; }
        .item-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
        .item-details h4 { margin: 0 0 5px 0; font-size: 16px; font-weight: 600; color: #333; }
        .item-price { color: #d9534f; font-weight: bold; }
        .btn-add-cart { background-color: #5cb85c; color: white; border: none; padding: 6px 12px; border-radius: 4px; }
        .btn-remove-cart { background-color: #d9534f; color: white; border: none; padding: 4px 8px; border-radius: 4px; }
        .qty-btn { background: #fff; border: 1px solid #ccc; width: 25px; height: 25px; border-radius: 4px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Restaurant</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav main-nav clear navbar-right">
                    <li><a class="color_animation" href="{{ url('/') }}">Trang chủ</a></li>
                    <li><a class="navactive color_animation" href="{{ url('/reservation') }}">Đặt bàn</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="reservation" class="description_content">
        <div class="featured background_content">
            <h1>Reserve a Table!</h1>
        </div>

        <div class="reservation-section">
            <div class="container">
                <div class="form-container-custom">
                    <h2 class="form-title">Thông Tin Đặt Bàn</h2>

                    <form id="contact-us" method="post" action="{{ route('reservation.store') }}">
                        @csrf
                        
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <label class="label-custom">Ngày đặt bàn</label>
                                <input type="date" name="reservation_date" class="form-control input-custom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Giờ đến</label>
                                <input type="time" name="reservation_time" class="form-control input-custom" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="label-custom">Họ và Tên</label>
                                <input type="text" name="full_name" class="form-control input-custom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="label-custom">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control input-custom" required>
                            </div>
                        </div>

                        <label class="label-custom">Vị trí bàn</label>
                        <div class="table-selection-grid">
                            <div class="table-item"><input type="radio" name="table_id" id="t1" value="1" required><label for="t1">Bàn 1 - 12 Ghế</label></div>
                            <div class="table-item"><input type="radio" name="table_id" id="t2" value="2"><label for="t2">Bàn 2 - 12 Ghế</label></div>
                            <div class="table-item"><input type="radio" name="table_id" id="t3" value="3"><label for="t3">Bàn 3 - 8 Ghế</label></div>
                            <div class="table-item"><input type="radio" name="table_id" id="t4" value="4"><label for="t4">Bàn 4 - 6 Ghế</label></div>
                        </div>

                        <label class="label-custom">Thực đơn đặt trước</label>
                        <button type="button" class="btn-food-select" data-toggle="modal" data-target="#foodMenuModal">
                            <i class="fa fa-cutlery"></i> XEM MENU VÀ CHỌN MÓN ĂN
                        </button>

                        <div id="cart-container" class="cart-wrapper" style="display: none;">
                            <h5 class="label-custom" style="margin-top: 0; color: #d9534f;">Món ăn đã chọn:</h5>
                            <div id="cart-list"></div>
                            <div class="cart-total text-right" style="margin-top: 15px;">
                                <strong>Tổng cộng: <span id="total-price">0</span> VNĐ</strong>
                            </div>
                            <div id="hidden-inputs-container"></div>
                        </div>

                        <label class="label-custom">Ghi chú</label>
                        <textarea name="notes" class="form-control input-custom" rows="3" style="height: auto !important;"></textarea>

                        <button type="submit" class="btn-reserve">Xác Nhận Đặt Bàn</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div id="foodMenuModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thực Đơn (Theo Seeder)</h4>
                </div>
                <div class="modal-body" style="max-height: 450px; overflow-y: auto;">
                    <div id="menu-list"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Hoàn tất</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('js/jquery-1.10.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
    // 1. ĐỒNG BỘ DỮ LIỆU TỪ SESSION PHP SANG JAVASCRIPT KHI LOAD TRANG
    const menuItems = @json($menus); 
    // Tìm đoạn này trong <script> của bạn:
let cart = [
    @if(isset($cart) && is_array($cart)) // Thêm kiểm tra này
        @foreach($cart as $id => $item)
        {
            id: {{ $id }},
            name: "{{ $item['name'] }}",
            price: {{ $item['price'] }},
            image: "{{ $item['image'] }}",
            quantity: {{ $item['quantity'] }}
        },
        @endforeach
    @endif
];

    const formatCurrency = (num) => new Intl.NumberFormat('vi-VN').format(num);

    function renderMenu() {
        let html = '';
        menuItems.forEach(item => {
            html += `
                <div class="menu-item">
                    <div class="item-info">
                        <img src="{{ asset('') }}${item.image}" alt="${item.name}" class="item-img" onerror="this.src='https://via.placeholder.com/65'">
                        <div class="item-details">
                            <h4>${item.name}</h4>
                            <small>${item.category} - ${item.description}</small><br>
                            <span class="item-price">${formatCurrency(item.price)} VNĐ</span>
                        </div>
                    </div>
                    <button type="button" class="btn-add-cart" onclick="addToCart(${item.id})">Chọn</button>
                </div>`;
        });
        document.getElementById('menu-list').innerHTML = html;
    }

    // 2. GỬI AJAX KHI THÊM MÓN ĐỂ KIỂM TRA STOCK VÀ LƯU SESSION
    function addToCart(id) {
        $.ajax({
            url: "{{ route('cart.add') }}", // Đảm bảo route này khớp với file web.php
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                menu_id: id,
                quantity: 1
            },
            success: function(response) {
                const item = menuItems.find(i => i.id === id);
                const existing = cart.find(i => i.id === id);
                if (existing) {
                    existing.quantity += 1;
                } else {
                    cart.push({ ...item, quantity: 1 });
                }
                updateCartUI();
            },
            error: function(xhr) {
                // Hiển thị lỗi nếu hết hàng (Lỗi 400 từ Controller)
                alert(xhr.responseJSON.error || "Có lỗi xảy ra khi thêm món.");
            }
        });
    }

    function updateQuantity(id, change) {
        const index = cart.findIndex(i => i.id === id);
        if (index > -1) {
            const newQty = cart[index].quantity + change;
            
            // Nếu giảm về 0 thì xóa, nếu tăng thì vẫn nên check qua AJAX hoặc logic local
            if (newQty <= 0) {
                cart.splice(index, 1);
                // Bạn có thể thêm 1 route AJAX để xóa item khỏi session ở đây nếu cần
                updateCartUI();
            } else {
                // Cập nhật tạm thời trên UI, session sẽ được cập nhật khi nhấn đặt bàn hoặc qua AJAX thêm
                cart[index].quantity = newQty;
                updateCartUI();
            }
        }
    }

    function updateCartUI() {
        const container = document.getElementById('cart-container');
        const list = document.getElementById('cart-list');
        const hidden = document.getElementById('hidden-inputs-container');
        let total = 0, cartHtml = '', inputsHtml = '';

        if (cart.length === 0) {
            container.style.display = 'none';
        } else {
            container.style.display = 'block';
            cart.forEach(item => {
                total += item.price * item.quantity;
                
                cartHtml += `
                    <div class="cart-item" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ asset('') }}${item.image}" alt="${item.name}" 
                                 style="width: 45px; height: 45px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;"
                                 onerror="this.src='https://via.placeholder.com/45'">
                            <div>
                                <strong style="display: block; font-size: 14px;">${item.name}</strong>
                                <span style="font-size: 12px; color: #888;">${formatCurrency(item.price)} VNĐ</span>
                            </div>
                        </div>
                        
                        <div class="qty-control" style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span style="min-width: 20px; text-align: center; font-weight: bold;">${item.quantity}</span>
                            <button type="button" class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            <span style="margin-left: 10px; font-weight: bold; min-width: 80px; text-align: right;">
                                ${formatCurrency(item.price * item.quantity)}
                            </span>
                        </div>
                    </div>`;

                // Quan trọng: Phải khớp name với Controller xử lý (ví dụ: foods hoặc menu_items)
                inputsHtml += `
                    <input type="hidden" name="foods[${item.id}][id]" value="${item.id}">
                    <input type="hidden" name="foods[${item.id}][quantity]" value="${item.quantity}">`;
            });
        }
        list.innerHTML = cartHtml;
        hidden.innerHTML = inputsHtml;
        document.getElementById('total-price').innerText = formatCurrency(total);
    }

    // 3. KHI TRANG SẴN SÀNG: RENDER MENU VÀ HIỂN THỊ LẠI GIỎ HÀNG TỪ SESSION
    $(document).ready(() => {
        renderMenu();
        updateCartUI(); 
    });
</script>
</body>
</html>