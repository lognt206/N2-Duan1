<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session, nếu chưa có thì mặc định là "Hướng dẫn viên"
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';

// Dùng htmlspecialchars để tránh lỗi HTML injection
$nameUser = htmlspecialchars($nameUser);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS giống customer -->
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover, #sidebar a.active { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user { display: flex; align-items: center; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .card-stats { padding: 20px; color: #fff; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        footer { width:100%; background:#fff; position:fixed; bottom:0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
        #sidebar a i { color: #fff !important; }
        .filter-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px;}
        .filter-btn { background-color: #010518ff; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; cursor: pointer; font-weight: 500; transition: 0.3s;}
        .filter-btn.active, .filter-btn:hover { background-color: #d59d00ff; color: #fff;}
        .search-box { display: flex; align-items: center; gap: 5px;}
        .search-box input { width: 270px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; outline: none;}
        .btn-search { background-color: #d59d00ff; color: white; border: none; border-radius: 6px; padding: 8px 12px; cursor: pointer;}
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=report"><i class="fa-solid fa-clipboard-list"></i> Nhật ký tour</a>
    <a href="?act=special_request" class="active"><i class="fa-solid fa-star"></i> Yêu cầu đặc biệt</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <a href="?act=dashboard" class="text-decoration-none text-dark">
                <i class="fa-solid fa-plane-departure"></i> Guide Panel
            </a>
        </div>
        <div class="user">
            <img src="uploads/logo.png" alt="User">
            <span><?= $nameUser ?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container-fluid">
        <h2 class="text-primary mb-3">🌟 Yêu cầu Đặc biệt của Khách hàng</h2>
        <p><strong>Tour:</strong> Lucca Bike Tour - HL25 | <strong>Ngày:</strong> 02 Thg 10, 2025</p>

        <div class="summary-box">
          <span>Tổng: <strong id="total-requests">5</strong></span>
          <span>Ăn uống: <strong id="food-requests">3</strong></span>
          <span>Y tế/Khác: <strong id="medical-requests">2</strong></span>
        </div>

        <div class="filter-controls">
          <div class="status-filters">
            <button class="filter-btn active" data-status="all">Tất cả</button>
            <button class="filter-btn" data-status="upcoming">Ăn uống</button>
            <button class="filter-btn" data-status="completed">Y tế</button>
          </div>
          <div class="search-box">
            <input type="text" placeholder="Tìm theo tên khách..." required>
            <button class="btn-search"><i class="fa-solid fa-search"></i></i></button>
          </div>
        </div>

        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>STT</th>
              <th>Tên Khách</th>
              <th>Loại Yêu cầu</th>
              <th>Nội dung</th>
              <th>Tình trạng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr data-type="food">
              <td>1</td>
              <td>NGUYỄN VĂN A</td>
              <td><span class="type-badge food">Ăn chay</span></td>
              <td>Không ăn thịt, cá, trứng, sữa.</td>
              <td><span class="status-badge solved">✅ Đã xác nhận</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="food">
              <td>2</td>
              <td>TRẦN THỊ B</td>
              <td><span class="type-badge food">Dị ứng</span></td>
              <td>Dị ứng đậu phộng và hải sản.</td>
              <td><span class="status-badge pending">⚠️ Cần xác minh</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="medical">
              <td>3</td>
              <td>LÊ VĂN C</td>
              <td><span class="type-badge medical">Bệnh lý</span></td>
              <td>Bệnh tim nhẹ, cần mang theo thuốc.</td>
              <td><span class="status-badge solved">✅ Đã thông báo</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="other">
              <td>4</td>
              <td>PHẠM THỊ D</td>
              <td><span class="type-badge other">Khác</span></td>
              <td>Yêu cầu phòng tầng thấp.</td>
              <td><span class="status-badge pending">⚠️ Cần xác minh</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
          </tbody>
        </table>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<script>
    // Lọc bảng theo loại
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        document.querySelectorAll('table tbody tr').forEach(row => {
          row.style.display = (filter === 'all' || row.getAttribute('data-type') === filter) ? '' : 'none';
        });
      });
    });
  </script>

</body>
</html>