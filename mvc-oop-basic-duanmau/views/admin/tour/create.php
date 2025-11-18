<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm tour mới</title>
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
.badge { font-size: 12px; }

/* Status badges */
.badge.Available { background: #198754; }
.badge.Closed { background: #6c757d; }

/* Footer */
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
<h3>Admin Panel</h3>
<a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
<a href="?act=tour" class="active"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
  <a href="?act=category"><i class="fa-solid fa-plane"></i> Quản lý danh mục Tour</a>
<a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
<a href="?act=booking"><i class="fa-solid fa-ticket"></i> Quản lý Đặt tour</a>
<a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
<a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
<a href="?act=departures"><i class="fa-solid fa-calendar"></i> Lịch khởi hành</a>
<a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a>
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

<h3 class="mb-3"><i class="fa-solid fa-plane"></i> Thêm Tour mới</h3>
<form action="index.php?act=store" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col 1">
            <h5 class="mb-3">Thông tin cơ bản</h5>
                <div class="mb-3">
                    <label for="tour_name" class="form-label">Tên tour</label>
                    <input type="text" class="form-control" id="tour_name" name="tour_name" >
                </div>
                <div class="mb-3">
                    <label for="tour_category" class="form-label">Danh mục tour</label>
                    <select class="form-select" id="tour_category" name="tour_category">
                        <option value="1">Tour trong nước</option>
                        <option value="2">Tour ngoài nước</option>
                        <option value="3">Tour khác</option>
                        </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">mô tả</label>
                    <input type="text" class="form-control" id="description" name="description" >
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Giá (VNĐ)</label>
                    <input type="text" class="form-control" id="price" name="price" >
                </div>
        </div>

        <div class="col 1">
            <h5 class="mb-3">Thông tin khác</h5>
                <div class="mb-3">
                    <label for="policy" class="form-label">Chính sách </label>
                    <input type="text" class="form-control" id="policy" name="policy" >
                </div>
                <div class="mb-3">
                    <label for="supplier" class="form-label">Nhà cung cấp</label>
                    <input type="text" class="form-control" id="supplier" name="supplier" >
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select class="form-select" name="status" id="status">
                        <option value="1">Còn mở</option>
                        <option value="0">Đã đóng</option>
                    </select>
                </div>
        </div>
    </div>
    
    <div class="text-end mb-5">
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Lưu Tour</button>
        <button type="button" class="btn btn-secondary"><a href="?act=tour"style="color:white;">Hủy</a> </button>
    </div>
</form>

</div>

<footer>
&copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
