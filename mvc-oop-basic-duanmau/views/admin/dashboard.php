<?phpif (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xử lý đăng xuất
// if (isset($_GET['act']) && $_GET['act'] === 'dangxuat') {
//     session_destroy();
//     header("Location: ?act=login");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
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
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
     <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
    <a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
      <a href="?act=category"><i class="fa-solid fa-plane"></i> Quản lý danh mục Tour</a>
    <a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
    <a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</a>
    <a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
    <a href="?act=partner" ><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
    <a href="?act=departures"><i class="fa-solid fa-calendar"></i> Lịch khởi hành</a>
    <a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a>
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
            <span>Admin</span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container-fluid">
        <h2 class="mb-4">Bảng điều khiển</h2>
        <p>Chào mừng Admin đến với hệ thống quản lý du lịch!</p>

        <div class="row">
            <div class="col-md-3">
                <div class="card-stats bg-primary">
                    <h4>Tổng Tour</h4>
                    <p>25</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stats bg-success">
                    <h4>Khách hàng</h4>
                    <p>120</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stats bg-warning">
                    <h4>Booking</h4>
                    <p>75</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stats bg-danger">
                    <h4>Doanh thu</h4>
                    <p>150.000.000₫</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
