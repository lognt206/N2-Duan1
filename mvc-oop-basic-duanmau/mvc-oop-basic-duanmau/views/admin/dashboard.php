<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user { display: flex; align-items: center; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .card-stats { padding: 20px; color: #fff; border-radius: 8px; margin-bottom: 20px; }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3">Admin Panel</h3>
    <a href="?act=dashboard">Dashboard</a>
    <a href="?act=tour">Quản lý Tour</a>
    <a href="?act=customer">Quản lý Khách hàng</a>
    <a href="?act=booking">Quản lý Đặt tour</a>
    <a href="?act=guide">Quản lý Hướng dẫn viên</a>
    <a href="?act=partner">Quản lý Đối tác</a>
    <a href="?act=departures">Lịch khởi hành</a>
    <a href="?act=baocao">Báo cáo tài chính</a>
    <a href="?act=logout">Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <a href="?controller=dashboard" class="text-decoration-none text-dark">
                <i class="fa-solid fa-plane-departure"></i> Admin Panel
            </a>
        </div>
        <div class="user">
            <img src="https://via.placeholder.com/40" alt="User">
            <span>Admin</span>
            <a href="?controller=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <h1>Dashboard</h1>
    <p>Chào mừng admin đến với hệ thống quản lý!</p>
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

<!-- Footer (nếu muốn) -->
<footer class="text-center py-3" style="width:100%; background:#fff; position:fixed; bottom:0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1);">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
