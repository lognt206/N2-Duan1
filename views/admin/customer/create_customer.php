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
<title>Thêm khách hàng mới</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar h3 { text-align: center; padding: 12px 0; border-bottom: 1px solid #495057; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
#sidebar a:hover { background: #495057; }
#sidebar a.active { background: #6c757d; }
#content { flex: 1; padding: 20px; background: #f8f9fa; padding-bottom: 70px; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.topbar .logo { display: flex; align-items: center; font-weight: bold; }
.topbar .user { display: flex; align-items: center; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.table-responsive { background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3>Admin Panel</h3>
<a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
<a href="?act=category"><i class="fa-solid fa-list"></i> Quản lý danh mục Tour</a>
<a href="?act=customer" class="active"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
<a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt tour</a>
<a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
<a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
<a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a>
<a href="?act=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
<div class="topbar">
<div class="logo"><i class="fa-solid fa-plane-departure me-2"></i>Admin Panel</div>
<div class="user">
<img src="https://via.placeholder.com/40" alt="User">
<span><?= $_SESSION['user']['full_name'] ?? ''; ?></span>
<a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
</div>
</div>

<h3 class="mb-3"><i class="fa-solid fa-users"></i> Thêm khách hàng mới</h3>



<form action="" method="POST">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Giới tính</label>
                <select class="form-select" id="gender" name="gender">
                    <option value="1">Nam</option>
                    <option value="2">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="birth_year" class="form-label">Năm sinh</label>
                <input type="number" class="form-control" id="birth_year" name="birth_year" min="1900" max="<?= date('Y') ?>">
            </div>
            <div class="mb-3">
                <label for="id_number" class="form-label">CMND/CCCD</label>
                <input type="text" class="form-control" id="id_number" name="id_number">
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="contact" class="form-label">Liên hệ</label>
                <input type="text" class="form-control" id="contact" name="contact" placeholder="Số điện thoại hoặc email">
            </div>
            <div class="mb-3">
                <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                <select class="form-select" id="payment_status" name="payment_status">
                    <option value="1">Đã thanh toán</option>
                    <option value="0">Chưa thanh toán</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="special_request" class="form-label">Yêu cầu đặc biệt</label>
                <textarea class="form-control" id="special_request" name="special_request" rows="3" placeholder="Ví dụ: dị ứng hải sản"></textarea>
            </div>
        </div>
    </div>

    <div class="text-end mb-5">
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Lưu Khách hàng</button>
        <a href="?act=customer" class="btn btn-secondary"><i class="fa-solid fa-times"></i> Hủy</a>
    </div>
</form>

</div>

<footer>
&copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
