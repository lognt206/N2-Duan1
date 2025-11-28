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
<title>Quản lý Tour</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar h3 { text-align: center; padding: 12px 0; border-bottom: 1px solid #495057; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
#sidebar a:hover { background: #495057; }
#sidebar a.active { background: #6c757d; }
#content { flex: 1; padding: 20px; background: #f8f9fa; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.topbar .logo { display: flex; align-items: center; font-weight: bold; }
.topbar .user { display: flex; align-items: center; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.table-responsive { background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.table th { background: #343a40; color: #fff; text-align: center; }
.table td { vertical-align: middle; text-align: center; }
.btn-sm { padding: 4px 8px; }
.badge { font-size: 12px; }
.badge.Available { background: #198754; }
.badge.Closed { background: #6c757d; }
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
.itinerary-list { text-align: left; }
.itinerary-item { font-size: 13px; margin-bottom: 4px; }
.img-tour { width: 80px; height: auto; border-radius: 4px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3>Admin Panel</h3>
<a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour" class="active"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
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
<i class="fa-solid fa-plane-departure me-2"></i>
<span>Admin Panel</span>
</div>
<div class="user">
<img src="uploads/logo.png" alt="User">
<span><?= $_SESSION['user']['username'] ?? '';?></span>
<a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
</div>
</div>

<h3 class="mb-3"><i class="fa-solid fa-plane"></i> Quản lý danh sách Tour</h3>

<div class="d-flex justify-content-between mb-3">
<a href="index.php?act=create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Tour mới</a>
<!-- <form class="d-flex" style="max-width:300px;">
<input type="text" class="form-control me-2" placeholder="Tìm kiếm...">
<button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
</form> -->
</div>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead>
<tr>
<th>Mã Tour</th>
<th>Ảnh</th>
<th>Tên Tour</th>
<th>Loại Tour</th>
<th>Mô tả</th>
<th>Giá tour(VNĐ)</th>
<th>Chính sách</th>
<th>Nhà cung cấp</th>
<th>Hành trình</th>
<th>Tình trạng</th>
<th>Hành động</th>
</tr>
</thead>
<tbody>
<?php if (!empty($tours)) : ?>
    <?php foreach($tours as $tour): ?>
    <tr>
        <td><?= $tour['tour_id'] ?></td>
        <td>
            <?php if (!empty($tour['image']) && file_exists($tour['image'])): ?>
                <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['tour_name']) ?>" class="img-tour">
            <?php else: ?>
                <span class="text-muted">Chưa có ảnh</span>
            <?php endif; ?>
        </td>
        <td><?= $tour['tour_name'] ?></td>
        <td><?= $tour['category_name'] ?></td>
        <td><?= $tour['description'] ?></td>
        <td><?= number_format($tour['price'], 0, ',', '.') ?>₫</td>
        <td><?= $tour['policy'] ?></td>
        <td>
            <?= !empty($tour['partners']) ? implode(', ', array_map(fn($p) => $p['partner_name'], $tour['partners'])) : "<span class='text-muted'>Chưa có nhà cung cấp</span>" ?>
        </td>
        <td class="itinerary-list">
            <?php if(!empty($tour['itineraries'])): ?>
                <?php foreach($tour['itineraries'] as $it): ?>
                    <div class="itinerary-item">
                        Ngày <?= $it['day_number'] ?>: <?= $it['activity'] ?> (<?= $it['start_time'] ?> - <?= $it['end_time'] ?>) tại <?= $it['location'] ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted">Chưa có hành trình</span>
            <?php endif; ?>
        </td>
        <td>
            <span class="badge <?= (int)$tour['status']===1 ? 'Available' : 'Closed' ?>">
                <?= (int)$tour['status']===1 ? 'Còn mở' : 'Đã đóng' ?>
            </span>
        </td>
        <td>
            <a href="?act=update&id=<?= $tour['tour_id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
            <a href="?act=delete&id=<?= $tour['tour_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa tour này?')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else : ?>
<tr><td colspan="11" class="text-center text-muted">Không có dữ liệu tour.</td></tr>
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
