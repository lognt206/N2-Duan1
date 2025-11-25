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
    <title>Chi tiết Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; 
                 box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
    </style>
</head>
<body>

<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
    <a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
    <a href="?act=category"><i class="fa-solid fa-list"></i> Quản lý danh mục Tour</a>
    <a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
    <a href="?act=booking" class="bg-secondary"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</a>
    <a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
    <a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
    <a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<div id="content">
    <div class="topbar">
        <div class="logo d-flex align-items-center">
            <i class="fa-solid fa-plane-departure me-2"></i>
            <span class="fw-bold">Admin Panel</span>
        </div>
        <div class="user d-flex align-items-center">
            <img src="uploads/logo.png" alt="User">
            <span><?= $_SESSION['user']['username'] ?? '' ?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-eye"></i> Chi tiết Booking #<?= $booking['booking_id'] ?></h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3"><?= $booking['tour_name'] ?></h5>

          <div class="row mb-2">
    <div class="col-md-4 fw-bold">danh sách khách :</div>
    <div class="col-md-8">
        <?php if (!empty($booking['customers'])): ?>
            <ul class="mb-0">
                <?php foreach ($booking['customers'] as $c): ?>
                    <li><?= htmlspecialchars($c['full_name']) ?></li>
                     <td><?= isset($customer['gender']) ? ($customer['gender']==1 ? "Nam" : "Nữ") : "Chưa xác định" ?></td>
                <td><?= $customer['birth_year'] ?? "Chưa xác định" ?></td>
                <td><?= htmlspecialchars($customer['id_number'] ?? '') ?></td>
                <td><?= htmlspecialchars($customer['contact'] ?? '') ?></td>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            -
        <?php endif; ?>
    </div>
</div>



            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Hướng dẫn viên:</div>
                <div class="col-md-8"><?= $booking['guide_name'] ?? '-' ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Ngày đặt:</div>
                <div class="col-md-8"><?= $booking['booking_date'] ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Lịch khởi hành:</div>
                <div class="col-md-8">
                    <?= !empty($booking['departure_date']) ? $booking['departure_date'] : '-' ?>
                    <?= !empty($booking['return_date']) ? ' → ' . $booking['return_date'] : '' ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Điểm gặp gỡ:</div>
                <div class="col-md-8"><?= $booking['meeting_point'] ?? '-' ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Số người:</div>
                <div class="col-md-8"><?= $booking['num_people'] ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Loại đặt:</div>
                <div class="col-md-8"><?= $booking['booking_type'] ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Trạng thái:</div>
                <div class="col-md-8">
                    <span class="badge <?= $booking['status'] == 1 ? 'bg-success' : ($booking['status'] == 2 ? 'bg-primary' : ($booking['status']==3?'bg-danger':'bg-warning')) ?>">
                        <?= $booking['status'] == 1 ? "Hoàn thành" : ($booking['status'] == 2 ? "Đã cọc" : ($booking['status']==3?"Đã hủy":"Chờ xác nhận")) ?>
                    </span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Ghi chú:</div>
                <div class="col-md-8"><?= $booking['notes'] ?? '-' ?></div>
            </div>

            <a href="?act=booking" class="btn btn-secondary mt-3"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>

</div>

<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
