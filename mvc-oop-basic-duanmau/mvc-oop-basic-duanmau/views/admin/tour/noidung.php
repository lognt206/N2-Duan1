<?php
// Dữ liệu mẫu giả lập (thay bằng dữ liệu từ model sau này)
$tours = [
    [
        'tour_id' => 1,
        'tour_name' => 'Du lịch Hạ Long 3N2Đ',
        'tour_type' => 'Domestic',
        'description' => 'Khám phá vịnh Hạ Long - kỳ quan thiên nhiên thế giới.',
        'price' => 3500000.00,
        'policy' => 'Hoàn 50% nếu hủy trước 3 ngày khởi hành.',
        'supplier' => 'Công ty Du lịch Việt Travel',
        'status' => 'Available'
    ],
    [
        'tour_id' => 2,
        'tour_name' => 'Tour Nhật Bản 5 ngày 4 đêm',
        'tour_type' => 'International',
        'description' => 'Trải nghiệm văn hóa và ẩm thực Nhật Bản.',
        'price' => 24500000.00,
        'policy' => 'Không hoàn tiền sau khi đặt tour.',
        'supplier' => 'JapanTour Agency',
        'status' => 'Closed'
    ],
    [
        'tour_id' => 3,
        'tour_name' => 'Tour theo yêu cầu - Phú Quốc',
        'tour_type' => 'Customized',
        'description' => 'Thiết kế tour riêng cho nhóm từ 5 người trở lên.',
        'price' => 5500000.00,
        'policy' => 'Hủy trước 7 ngày được hoàn 80%.',
        'supplier' => 'FPT Travel',
        'status' => 'Available'
    ]
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tour</title>
    
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
        table td { vertical-align: middle; }
        footer { background: #fff; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; width: calc(100% - 250px); }
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

    <h1 class="mb-4">Quản lý Tour</h1>

    <a href="?controller=tours&action=create" class="btn btn-success mb-3"><i class="fa fa-plus"></i> Thêm Tour mới</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>Mã Tour</th>
                <th>Tên Tour</th>
                <th>Loại Tour</th>
                <th>Mô tả</th>
                <th>Giá (VNĐ)</th>
                <th>Chính sách</th>
                <th>Nhà cung cấp</th>
                <th>Tình trạng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tours as $tour): ?>
                <tr>
                    <td class="text-center"><?= $tour['tour_id'] ?></td>
                    <td><?= $tour['tour_name'] ?></td>
                    <td class="text-center">
                        <?php
                            if ($tour['tour_type'] === 'Domestic') echo 'Trong nước';
                            elseif ($tour['tour_type'] === 'International') echo 'Quốc tế';
                            else echo 'Theo yêu cầu';
                        ?>
                    </td>
                    <td><?= $tour['description'] ?></td>
                    <td class="text-end"><?= number_format($tour['price'], 0, ',', '.') ?>₫</td>
                    <td><?= $tour['policy'] ?></td>
                    <td><?= $tour['supplier'] ?></td>
                    <td class="text-center">
                        <?php if ($tour['status'] === 'Available'): ?>
                            <span class="badge bg-success">Còn mở</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Đã đóng</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="?controller=tours&action=show&id=<?= $tour['tour_id'] ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="?controller=tours&action=edit&id=<?= $tour['tour_id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="?controller=tours&action=delete&id=<?= $tour['tour_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa tour này?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
