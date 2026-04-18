    @extends('shared')

    @section('title', 'FoodHub - Thông tin tài khoản')

    @section('head')
        {{-- Copy phần style này để có giao diện giống hệt ảnh --}}
        <style>
            body {
                background-color: #fcf8f2; /* Màu nền nâu nhạt của web */
            }
            
            .profile-wrapper {
                max-width: 900px;
                margin: 100px auto 50px; /* Căn giữa và đẩy xuống để không bị menu đè */
                background: #fff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            }

            .profile-title {
                text-align: center;
                font-size: 28px;
                color: #d9534f;
                margin-bottom: 40px;
                font-weight: 600;
            }

            /* Bố cục 2 cột */
            .profile-container {
                display: flex;
                gap: 40px; /* Khoảng cách giữa 2 cột */
            }

            /* Cột trái: Avatar */
            .profile-left {
                flex: 0 0 150px; /* Cố định độ rộng cột trái */
                text-align: center;
            }

            .avatar-container {
                width: 150px;
                height: 150px;
                margin: 0 auto 15px;
                position: relative;
            }

            .profile-avatar {
                width: 100%;
                height: 100%;
                border-radius: 50%; /* Bo tròn */
                object-fit: cover;
                border: 3px solid #fcf8f2;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }

            /* Nút sửa avatar */
            .edit-avatar-btn {
                position: absolute;
                bottom: 5px;
                right: 5px;
                background: #d9534f; /* Màu đỏ của web Nhi */
                color: white;
                border-radius: 50%;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                font-size: 14px;
            }

            /* Cột phải: Form thông tin */
            .profile-right {
                flex: 1; /* Tự động rộng ra */
            }

            .profile-form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr; /* Chia 2 cột nhỏ bên trong form */
                gap: 20px 30px; /* Khoảng cách giữa các ô input */
            }

            /* Style cho từng ô input */
            .form-group-custom label {
                display: block;
                font-weight: 600;
                color: #555;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .input-custom {
                width: 100%;
                height: 45px !important;
                padding: 10px 15px;
                border: 1px solid #e0e0e0 !important;
                border-radius: 6px !important;
                background-color: #f9f9f9;
                color: #333;
                font-size: 14px;
                transition: 0.3s;
            }

            .input-custom:focus {
                border-color: #d9534f !important;
                background-color: #fff;
                outline: none;
            }

            /* Nút Lưu thay đổi */
            .btn-update-profile {
                background-color: #d9534f; /* Màu đỏ */
                color: white;
                padding: 12px 30px;
                border: none;
                border-radius: 6px;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 14px;
                margin-top: 30px;
                cursor: pointer;
                transition: 0.3s;
            }

            .btn-update-profile:hover {
                background-color: #c9302c;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
        </style>
    @endsection

    @section('content')
    <div class="container">
        <div class="profile-wrapper">
            <h2 class="profile-title">Thông tin tài khoản</h2>

            {{-- Form cập nhật thông tin --}}
            {{-- Sửa dòng này --}}
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="profile-container">
                    
                    {{-- CỘT TRÁI: AVATAR & NÚT SỬA --}}
                    <div class="profile-left">
    <div class="avatar-container">
        {{-- Hiện ảnh từ storage, nếu chưa có thì hiện ảnh mặc định từ mạng --}}
        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name }}" 
     id="avatar-preview" class="profile-avatar">
        
        {{-- Nút bút chì: Khi bấm sẽ gọi cái ô chọn file ẩn ở dưới --}}
        <button type="button" class="edit-avatar-btn" onclick="document.getElementById('avatar-input').click();">
            <i class="fa fa-pencil"></i>
        </button>
    </div>

    {{-- Ô chọn file bị ẩn đi cho đẹp --}}
    <input type="file" name="avatar" id="avatar-input" style="display:none" onchange="previewImage(this)" accept="image/*">
</div>

                    {{-- CỘT PHẢI: FORM CÁC Ô NHẬP LIỆU --}}
                    <div class="profile-right">
                        <div class="profile-form-grid">
                            
                            {{-- Ô 1: Tên --}}
                            <div class="form-group-custom">
                                <label>Tên</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="input-custom" required>
                            </div>

                            {{-- Ô 2: Quận --}}
                            <div class="form-group-custom">
                                <label>Quận</label>
                                <input type="text" name="city" value="{{ $user->city ?? '' }}" class="input-custom" placeholder="Nhập quận/huyện">
                            </div>

                            {{-- Ô 3: Email (Khóa, không cho sửa) --}}
                            <div class="form-group-custom">
                                <label>Email</label>
                                <input type="email" value="{{ $user->email }}" class="input-custom" style="background-color: #eee; cursor: not-allowed;" readonly>
                            </div>

                            {{-- Ô 4: Địa chỉ cụ thể --}}
                            <div class="form-group-custom">
                                <label>Địa chỉ cụ thể</label>
                                <input type="text" name="address" value="{{ $user->address ?? '' }}" class="input-custom" placeholder="Số nhà, tên đường...">
                            </div>

                            {{-- Ô 5: Tài khoản tham gia vào --}}
                            <div class="form-group-custom">
                                <label>Tài khoản tham gia vào</label>
                                <input type="text" value="{{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }}" class="input-custom" style="background-color: #eee; cursor: not-allowed;" readonly>
                            </div>

                            {{-- Ô 6: Số điện thoại --}}
                            <div class="form-group-custom">
                                <label>Số điện thoại</label>
                                <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" class="input-custom" placeholder="Nhập số điện thoại">
                            </div>

                        </div>

                        {{-- Nút Lưu thay đổi nằm ở dưới bên phải --}}
                        <div style="text-align: right;">
                            <button type="submit" class="btn-update-profile">Lưu thay đổi</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endsection
    <script>
// Hàm này giúp Nhi chọn ảnh xong là nó hiện lên màn hình ngay lập tức để xem trước
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>