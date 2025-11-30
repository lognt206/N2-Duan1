<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar h3 { text-align: center; padding: 12px 0; border-bottom: 1px solid #495057; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
#sidebar a:hover { background: #495057; }
#sidebar a.active { background: #6c757d; }
#content { flex: 1; padding: 20px; padding-bottom: 60px; background: #f8f9fa; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.topbar .logo { display: flex; align-items: center; font-weight: bold; }
.topbar .user { display: flex; align-items: center; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.card-stats { padding: 20px; color: #fff; border-radius: 8px; margin-bottom: 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.bg-primary { background-color: #0d6efd !important; }
.bg-success { background-color: #198754 !important; }
.bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
.bg-danger { background-color: #dc3545 !important; }
footer { width:100%; background:#fff; text-align:center; padding:10px 0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3>Admin Panel</h3>
<a href="?act=dashboard" class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
<a href="?act=category"><i class="fa-solid fa-list"></i> Quản lý danh mục Tour</a>
<a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
<a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt tour</a>
<a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
<a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
<a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a>
<a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
<div class="topbar">
<div class="logo">
<i class="fa-solid fa-plane-departure me-2"></i> <span>Admin Panel</span>
</div>
<div class="user">
<img src="uploads/logo.png" alt="User">
<span><?= $_SESSION['user']['full_name'] ?? ''; ?></span>
<a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
</div>
</div>

<h3 class="mb-3"><i class="fa-solid fa-chart-line"></i> Bảng điều khiển</h3>

<div class="row">
<div class="col-md-3">
    <div class="card-stats bg-primary">
        <h4>Tổng Tour</h4>
        <p><?= $totalTours ?? 0 ?></p>
    </div>
</div>
<div class="col-md-3">
    <div class="card-stats bg-success">
        <h4>Khách hàng</h4>
        <p><?= $totalCustomers ?? 0 ?></p>
    </div>
</div>
<div class="col-md-3">
    <div class="card-stats bg-warning">
        <h4>Booking</h4>
        <p><?= $totalBookings ?? 0 ?></p>
    </div>
</div>
<div class="col-md-3">
    <div class="card-stats bg-danger">
        <h4>Doanh thu</h4>
        <p><?= number_format($totalRevenue ?? 0, 0, ",", ".") ?>₫</p>
    </div>
</div>
</div>

<!-- Biểu đồ doanh thu theo tour -->
<div class="mt-5">
<h4>Doanh thu theo tour</h4>
<canvas id="revenueChart" height="100"></canvas>
</div>
</div>

<footer>
&copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels ?? []) ?>,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: <?= json_encode($chartValues ?? []) ?>,
            backgroundColor: 'rgba(13, 110, 253, 0.7)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + '₫';
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>
