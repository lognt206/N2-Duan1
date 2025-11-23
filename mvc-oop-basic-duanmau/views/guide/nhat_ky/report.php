<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nameUser = $_SESSION['user']['name'] ?? '';
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
    <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
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
                <i class="fa-solid fa-plane-departure"></i> Admin Panel
            </a>
        </div>
        <div class="user">
            <img src="uploads/logo.png" alt="User">
            <span><?= $nameUser = $_SESSION['user']['username'] ?? '';?></span>
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
<!-- 

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang hướng dẫn viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
      .sidebar {
      background-color: #092f68ff;
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      padding-top: 1rem;
    }
    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      padding: 12px 16px;
      border-radius: 8px;
      margin: 4px 8px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 18px;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #d59d00ff;
      color: #fff;
      transform: translateX(3px);
    }
    main {
      margin-left: 240px;
      background-color: #f8f9fa;
      min-height: 100vh;
      padding: 30px;
    }
    .form-section {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
    }
    .form-section legend {
      font-weight: 600;
      color: #092f68ff;
    }
    .btn-submit {
      background-color: #d59d00ff;
      color: #fff;
      border: none;
      padding: 12px 28px;
      border-radius: 6px;
    }
    .btn-submit:hover {
      background-color: #c18c00;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="?act=profile"><i class="bi bi-person-circle"></i>Thông tin cá nhân</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=schedule"><i class="bi bi-calendar-week"></i>Lịch làm việc</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=report"><i class="bi bi-journal-text"></i>Nhật ký tour</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=special_request"><i class="bi bi-star"></i>Yêu cầu đặc biệt</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=login"><i class="bi bi-box-arrow-right"></i>Đăng xuất</a></li>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10">
        <h2 class="text-primary mb-3">Cập nhật tình trạng Tour</h2>
        <p>Tour: <strong>Lucca Bike Tour - HL25</strong> | Ngày: 02 Thg 10, 2025</p>

        <form action="process_report.php" method="POST">
          <input type="hidden" name="tour_id" value="HL25">

          <fieldset class="form-section">
            <legend>1. Trạng thái Tour</legend>
            <div class="mb-3">
              <label for="status" class="form-label">Chọn trạng thái:</label>
              <select id="status" name="status" class="form-select" required>
                <option value="completed">✅ Đã hoàn thành</option>
                <option value="in_progress">🔄 Đang thực hiện</option>
                <option value="incident">⚠️ Có sự cố</option>
                <option value="cancelled">❌ Đã hủy</option>
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
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->