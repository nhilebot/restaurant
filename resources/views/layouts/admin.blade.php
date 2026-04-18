<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nhà hàng Việt – Bảng điều khiển</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin_assets/style.css') }}">
</head>
<body>
<div class="app">

  <!-- ─── SIDEBAR ─── -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon">
    <a href="{{ url('/') }}" style="display: flex; align-items: center;">
        <img src="{{ asset('images/logo.png') }}" 
             alt="Món Việt Logo" 
              width: auto; filter: drop-shadow(0px 0px 2px rgba(0,0,0,0.5));">
    </a>
</div>
      <div>
        <div class="logo-text">Nhà hàng Việt</div>
        <div class="logo-sub">Quản trị hệ thống</div>
      </div>
    </div>

    <div class="sidebar-section-label">Các tính năng</div>

    <nav class="sidebar-nav">
      <a class="nav-item active" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">🏠</div>
          Bảng điều khiển
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="{{ route('admin.categories.index') }}">
        <div class="nav-item-left">
          <div class="nav-icon">📂</div>
          Danh mục
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">📋</div>
          Thực đơn
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">🪑</div>
          Quản lý bàn
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">📅</div>
          Đặt bàn
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">👥</div>
          Người dùng
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">🧾</div>
          Đơn hàng
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">💳</div>
          Thanh toán
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">💬</div>
          Bình luận
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">📝</div>
          Bài viết
        </div>
        <span class="nav-arrow">›</span>
      </a>
      <a class="nav-item" href="#">
        <div class="nav-item-left">
          <div class="nav-icon">🎁</div>
          Khuyến mãi
        </div>
        <span class="nav-arrow">›</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="user-row">
        <div class="avatar">AD</div>
        <div>
          <div class="user-name">Admin</div>
          <div class="user-role">Quản trị viên</div>
        </div>
      </div>
      <button class="btn-logout">Đăng xuất</button>
    </div>
  </aside>

  <!-- ─── MAIN ─── -->
  <div class="main">

    <!-- TOPBAR -->
    <header class="topbar">
      <div class="topbar-left">
        <button class="menu-btn">☰</button>
        <span class="topbar-title">Bảng điều khiển</span>
      </div>
      <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" placeholder="Tìm kiếm...">
      </div>
    </header>

    <!-- CONTENT -->
    <div class="content">

      <!-- STAT CARDS -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-info">
            <div class="stat-label">Tổng doanh thu</div>
            <div class="stat-value">1,470,000 VNĐ</div>
          </div>
          <div class="stat-icon-wrap">💰</div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <div class="stat-label">Tổng doanh thu hôm nay</div>
            <div class="stat-value">0 VNĐ</div>
          </div>
          <div class="stat-icon-wrap">📊</div>
        </div>
        <div class="stat-card">
          <div class="stat-info">
            <div class="stat-label">Tổng doanh thu tháng 08 năm 2024</div>
            <div class="stat-value">1,470,000 VNĐ</div>
          </div>
          <div class="stat-icon-wrap">📈</div>
        </div>
      </div>

      <!-- TABLE 1: Tổng Doanh Thu -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title-group">
            <div class="panel-title">Tổng Doanh Thu</div>
            <div class="panel-meta">Doanh Thu Tất Cả: <span>1,470,000 VNĐ</span></div>
          </div>
          <button class="btn-excel">📥 Xuất Excel</button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Số Thứ Tự</th>
                <th>Mã Đơn Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày Đặt Hàng</th>
                <th>Phương Thức Thanh Toán</th>
                <th>Tổng Tiền</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td class="order-id">DH-749949</td>
                <td>xxx@gmail.com</td>
                <td>17-08-2024</td>
                <td><span class="badge badge-blue">restaurant</span></td>
                <td class="amount">0 VNĐ</td>
              </tr>
              <tr>
                <td>2</td>
                <td class="order-id">DH-213890</td>
                <td>Nguyễn Văn B</td>
                <td>21-08-2024</td>
                <td><span class="badge badge-green">vnpay</span></td>
                <td class="amount">1,470,000 VNĐ</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- TABLE 2: Tổng Doanh Thu Hôm Nay -->
      <div class="panel">
        <div class="panel-header">
          <div class="panel-title-group">
            <div class="panel-title">Tổng Doanh Thu Hôm Nay</div>
            <div class="panel-meta">Doanh Thu Ngày: <span>0 VNĐ</span></div>
          </div>
          <button class="btn-excel">📥 Xuất Excel</button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Số Thứ Tự</th>
                <th>Mã Đơn Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày Đặt Hàng</th>
                <th>Phương Thức Thanh Toán</th>
                <th>Tổng Tiền</th>
              </tr>
            </thead>
            <tbody>
              <tr class="empty-row">
                <td colspan="6">Không có dữ liệu doanh thu hôm nay.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<script>
  // Nav item click
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', e => {
      e.preventDefault();
      document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
      item.classList.add('active');
    });
  });
</script>
</body>
</html>