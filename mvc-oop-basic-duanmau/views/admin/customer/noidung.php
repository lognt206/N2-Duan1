<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Model khách hàng (giả sử bạn đã include CustomerModel)
require_once 'models/CustomerModel.php';
$customerModel = new CustomerModel();

// Xử lý tìm kiếm
$keyword = $_GET['keyword'] ?? '';
$allCustomers = $customerModel->allcustomer();

if ($keyword) {
    $customers = array_filter($allCustomers, function($c) use ($keyword) {
        // Dùng strpos để tránh lỗi PHP < 8
        return strpos(strtolower($c['full_name']), strtolower($keyword)) !== false;
    });
} else {
    $customers = $allCustomers;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý Khách hàng</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
#sidebar a:hover, #sidebar a.active { background: #495057; }
#content { flex: 1; padding: 20px; background: #f8f9fa; padding-bottom: 70px; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
.table th { background: #343a40; color: white; }
.btn-sm { padding: 4px 8px; }
.badge.pay { background: #198754; }
.badge.no_pay { background: #6c757d; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3 class="text-center py-3 border-bottom">Admin Panel</h3>
<a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
<a href="?act=category"><i class="fa-solid fa-list"></i> Quản lý danh mục Tour</a>
<a href="?act=customer" class="active"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
<a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</a>
<a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
<a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
<a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản</a>
<a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
<div class="topbar">
    <div class="logo d-flex align-items-center">
        <i class="fa-solid fa-plane-departure me-2"></i>
        <span class="fw-bold">Admin Panel</span>
    </div>
    <div class="user d-flex align-items-center">
        <img src="uploads/logo.png" alt="User">
        <span><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?></span>
        <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
    </div>
</div>

<h3 class="mb-3"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</h3>

<div class="d-flex justify-content-between mb-3">
    <a href="index.php?act=create_customer" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Thêm Khách hàng
    </a>

    <form class="d-flex" style="max-width:300px;" method="get">
        <input type="hidden" name="act" value="customer">
        <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
    </form>
</div>

<div class="table-responsive bg-white p-3 rounded shadow-sm">
<table class="table table-bordered align-middle text-center">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nhóm khách</th>
            <th>Họ và Tên</th>
            <th>Giới tính</th>
            <th>Năm sinh</th>
            <th>CMND/CCCD</th>
            <th>Liên hệ</th>
            <th>Thanh toán</th>
            <th>Yêu cầu đặc biệt</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($customers)) : ?>
            <?php foreach ($customers as $customer) : ?>
            <tr>
                <td><?= $customer['customer_id'] ?></td>
                <td><?= htmlspecialchars($customer['group_name'] ?? 'Không có nhóm') ?></td>
                <td><?= htmlspecialchars($customer['full_name'] ?? '') ?></td>
                <td><?= isset($customer['gender']) ? ($customer['gender']==1 ? "Nam" : "Nữ") : "Chưa xác định" ?></td>
                <td><?= $customer['birth_year'] ?? "Chưa xác định" ?></td>
                <td><?= htmlspecialchars($customer['id_number'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['contact'] ?? '') ?></td>
                <td>
                    <span class="badge <?= $customer['payment_status']==1 ? 'pay' : 'no_pay' ?>">
                        <?= $customer['payment_status']==1 ? "Đã thanh toán" : "Chưa thanh toán" ?>
                    </span>
                </td>
                <td><?= $customer['special_request'] ? nl2br(htmlspecialchars($customer['special_request'])) : "Không" ?></td>
                <td>
                    <a href="index.php?act=update_customer&id=<?= $customer['customer_id'] ?>" class="btn btn-sm btn-warning me-1">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="index.php?act=delete_customer&id=<?= $customer['customer_id'] ?>" onclick="return confirm('Xóa khách hàng này?')" class="btn btn-sm btn-danger">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="10" class="text-center text-muted">Không có dữ liệu khách hàng.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<footer>
&copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
