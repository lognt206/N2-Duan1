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
        .text-primary{font-weight: 500; color: #212529 !important;}
        .form-section { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 50px;}
        .form-section legend { font-weight: 400; color: #000000ff;}
        .btn-submit { background-color: #d59d00ff; color: #fff; border: none; padding: 12px 40px; border-radius: 6px; margin-bottom: 50px;}
        .btn-submit:hover { background-color: #c02600ff; transform: translateY(-2px);}
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=report" class="active"><i class="fa-solid fa-clipboard-list"></i> Nhật ký tour</a>
    <a href="?act=special_request"><i class="fa-solid fa-star"></i> Yêu cầu đặc biệt</a>
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
        <h2 class="text-primary mb-3">Cập nhật tình trạng Tour</h2>
        <p>Tour: <strong>Lucca Bike Tour - HL25</strong> | Ngày: 02 Thg 10, 2025</p>

        <form action="process_report.php" method="POST">
          <input type="hidden" name="tour_id" value="HL25">

          <fieldset class="form-section">
            <legend>1. Trạng thái Tour</legend>
            <div class="mb-3">
              <label for="status" class="form-label">Chọn trạng thái:</label>
              <select id="status" name="status" class="form-select" required>
                <option value="completed"> Đã hoàn thành</option>
                <option value="in_progress"> Đang thực hiện</option>
                <option value="incident"> Có sự cố</option>
                <option value="cancelled"><i class="alert-triangle"></i> Đã hủy</option>
              </select>
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>2. Báo cáo & Ghi chú</legend>
            <div class="mb-3">
              <label for="notes" class="form-label">Tóm tắt & ghi chú:</label>
              <textarea id="notes" name="notes" class="form-control" rows="6" placeholder="Nhận xét khách hàng và đánh giá cá nhân"></textarea>
            </div>
          </fieldset>

          <fieldset class="form-section">
            <legend>3. Chi tiết Sự cố (nếu có)</legend>
            <div class="mb-3">
              <label for="incident_detail" class="form-label">Mô tả sự cố:</label>
              <textarea id="incident_detail" name="incident_detail" class="form-control" rows="5" placeholder="Mô tả chi tiết sự cố nếu có"></textarea>
            </div>
          </fieldset>

          <div class="text-center">
            <button type="submit" class="btn btn-submit">Gửi Báo cáo</button>
          </div>
        </form>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>