<?php
// Lấy dữ liệu từ model
// $guides = $this->model->getAllGuides();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hướng dẫn viên</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { display: flex; min-height: 100vh; margin:0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user { display: flex; align-items: center; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        table td, table th { vertical-align: middle; }
        footer { background: #fff; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; width: calc(100% - 250px); }
        .btn-action { margin-right: 5px; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3">Admin Panel</h3>
    <a href="?controller=dashboard"><i class="fa fa-home"></i> Dashboard</a>
    <a href="?controller=tours"><i class="fa fa-map"></i> Quản lý Tour</a>
    <a href="?controller=customers"><i class="fa fa-users"></i> Quản lý Khách hàng</a>
    <a href="?controller=bookings"><i class="fa fa-ticket"></i> Quản lý Đặt tour</a>
    <a href="?controller=guides"><i class="fa fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
    <a href="?controller=partners"><i class="fa fa-handshake"></i> Quản lý Đối tác</a>
    <a href="?controller=departures"><i class="fa fa-calendar"></i> Lịch khởi hành</a>
    <a href="?controller=reports"><i class="fa fa-chart-line"></i> Báo cáo tài chính</a>
    <a href="?controller=logout"><i class="fa fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
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

    <h1 class="mb-4">Quản lý Hướng dẫn viên</h1>

<a href="?controller=guides&action=create" class="btn btn-success mb-3"><i class="fa fa-plus"></i> Thêm HDV mới</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Ngày sinh</th>
            <th>Ảnh</th>
            <th>Điện thoại</th>
            <th>Ngôn ngữ</th>
            <th>Kinh nghiệm</th>
            <th>Sức khỏe</th>
            <th>Đánh giá</th>
            <th>Chuyên môn</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($guides as $guide): ?>
            <tr>
                <td class="text-center"><?= $guide['guide_id'] ?></td>
                <td><?= htmlspecialchars($guide['full_name']) ?></td>
                <td class="text-center"><?= $guide['birth_date'] ?></td>
                <td class="text-center">
                    <?php if(!empty($guide['photo'])): ?>
                        <img src="<?= $guide['photo'] ?>" alt="HDV" style="width:50px;">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($guide['contact']) ?></td>
                <td><?= htmlspecialchars($guide['languages']) ?></td>
                <td><?= htmlspecialchars($guide['experience']) ?></td>
                <td><?= htmlspecialchars($guide['health_condition']) ?></td>
                <td class="text-center"><?= $guide['rating'] ?></td>
                <td class="text-center">
                    <?= $guide['category'] === 'Domestic' ? 'Nội địa' : 'Quốc tế' ?>
                </td>
                <td class="text-center">
                    <a href="?controller=guides&action=edit&id=<?= $guide['guide_id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                    <a href="?controller=guides&action=delete&id=<?= $guide['guide_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa HDV này?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
