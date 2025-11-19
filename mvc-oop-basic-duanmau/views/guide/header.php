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

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <h1 class="h3 text-primary">Chào mừng hướng dẫn viên <?= $nameUser = $_SESSION['user']['username'] ?? '';?>!</h1>
        <p>Chọn một mục ở menu bên trái để bắt đầu quản lý tour của bạn.</p>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>