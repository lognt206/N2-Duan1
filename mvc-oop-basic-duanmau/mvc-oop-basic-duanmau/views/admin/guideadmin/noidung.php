

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý Hướng dẫn viên</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }

/* Sidebar */
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar h3 { text-align: center; padding: 12px 0; border-bottom: 1px solid #495057; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
#sidebar a:hover { background: #495057; }
#sidebar a.active { background: #6c757d; }

/* Content */
#content { flex: 1; padding: 20px; background: #f8f9fa; }

/* Topbar */
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
.topbar .logo { display: flex; align-items: center; font-weight: bold; }
.topbar .user { display: flex; align-items: center; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }

/* Table */
.table-responsive { background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.table th { background: #343a40; color: #fff; text-align: center; }
.table td { vertical-align: middle; text-align: center; }
.btn-sm { padding: 4px 8px; }

/* Footer */
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }

/* Action buttons */
.btn-action { margin-right: 5px; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3>Admin Panel</h3>
<a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
  <a href="?act=category"><i class="fa-solid fa-plane"></i> Quản lý danh mục Tour</a>
<a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
<a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</a>
<a href="?act=guideadmin" class="active"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
<a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
<a href="?act=departures"><i class="fa-solid fa-calendar"></i> Lịch khởi hành</a>
<a href="?act=reports"><i class="fa-solid fa-coins"></i> Báo cáo tài chính</a>
<a href="?act=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
<div class="topbar">
<div class="logo">
<i class="fa-solid fa-plane-departure me-2"></i>
<span>Admin Panel</span>
</div>
<div class="user">
<img src="https://via.placeholder.com/40" alt="User">
<span>Admin</span>
<a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
</div>
</div>

<h3 class="mb-3"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</h3>

<div class="d-flex justify-content-between mb-3">
<a href="?act=create_guide" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm HDV mới</a>
<form class="d-flex" style="max-width:300px;">
<input type="text" class="form-control me-2" placeholder="Tìm kiếm...">
<button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
</form>
</div>

<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead>
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
<?php if (!empty($guides)) : ?>
<?php foreach($guides as $guide): ?>
<tr>
<td><?= $guide['guide_id'] ?></td>
<td><?= htmlspecialchars($guide['full_name']) ?></td>
<td><?= $guide['birth_date'] ?></td>
<td>
<?php if(!empty($guide['photo'])): ?>
<img src="<?= $guide['photo'] ?>" alt="HDV" style="width:50px;">
<?php endif; ?>
</td>
<td><?= htmlspecialchars($guide['contact']) ?></td>
<td><?= htmlspecialchars($guide['languages']) ?></td>
<td><?= htmlspecialchars($guide['experience']) ?></td>
<td><?= htmlspecialchars($guide['health_condition']) ?></td>
<td><?= $guide['rating'] ?></td>
<td><?= $guide['category'] === 'Domestic' ? 'Nội địa' : 'Quốc tế' ?></td>
<td>
<a href="?controller=guides&action=edit&id=<?= $guide['guide_id'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
<a href="?controller=guides&action=delete&id=<?= $guide['guide_id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa HDV này?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr><td colspan="11" class="text-center text-muted">Không có dữ liệu HDV.</td></tr>
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
