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
    <title>Quản lý Đặt Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #sidebar a.active, #sidebar a.bg-secondary { background: #6c757d; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .table th { background: #343a40; color: white; text-align: center; }
        .table td { vertical-align: middle; text-align: center; }
        .badge-status { font-size: 12px; padding: 5px 8px; border-radius: 4px; display:inline-block; }
        footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
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

    <h3 class="mb-3"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</h3>

    <div class="d-flex justify-content-between mb-3">
        <a href="?act=createbooking" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Đặt Tour</a>
        <form class="d-flex" method="GET" style="max-width:300px;">
            <input type="hidden" name="act" value="booking">
            <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm kiếm...">
            <button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tour</th>
                    <th>Tệp khách hàng</th>
                    <th>Hướng dẫn viên</th>
                    <th>Ngày Đặt</th>
                    <th>Lịch khởi hành</th>
                    <th>Số Người</th>
                    <th>Loại Đặt</th>
                    <th>Trạng Thái</th>
                    <th>Ghi Chú</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($booking)) : ?>
                <?php foreach ($booking as $b) : ?>
                    <tr>
                        <td><?= $b['booking_id'] ?></td>
                        <td><?= $b['tour_name'] ?></td>
                        <td><?= $b['customer_group'] ?? '-' ?></td>
                        <td><?= $b['guide_name'] ?? '-' ?></td>
                        <td><?= $b['booking_date'] ?></td>
                        <td>
    <?= !empty($b['departure_date']) ? $b['departure_date'] : '-' ?>
    <?= !empty($b['return_date']) ? ' → ' . $b['return_date'] : '' ?>
</td>
                        <td><?= $b['num_people'] ?></td>
                        <td>
                     <?= isset($b['booking_type']) ? ($b['booking_type']==1 ? 'Trực tiếp' : ($b['booking_type']==2 ? 'Online' : '-')) : '-' ?>
                        </td>
                        <td>
                            <a href="?act=updateStatus&id=<?= $b['booking_id'] ?>&status=<?= $b['status'] ?>">
                                <span class="badge <?= $b['status'] == 1 ? 'bg-success' : ($b['status'] == 2 ? 'bg-primary' : ($b['status']==3?'bg-danger':'bg-warning')) ?>">
                                    <?= $b['status'] == 1 ? "Hoàn thành" : ($b['status'] == 2 ? "Đã cọc" : ($b['status']==3?"Đã hủy":"Chờ xác nhận")) ?>
                                </span>
                            </a>
                        </td>
                        <td><?= $b['notes'] ?></td>
                        <td>
                            <a href="?act=bookingDetail&id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-info">
                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                            </a>
                            <a href="?act=deletebooking&id=<?= $b['booking_id'] ?>" 
                               onclick="return confirm('Xóa đặt tour này?')" 
                               class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="11" class="text-center text-muted">Không có dữ liệu đặt tour.</td></tr>
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
