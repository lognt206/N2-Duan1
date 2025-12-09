<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy danh sách khách đã chọn
$selectedCustomers = $_SESSION['booking_customers'] ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Bước 2: Chọn khách hàng</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body { 
        display: flex; 
        min-height: 100vh; 
        margin: 0; 
        font-family: Arial, sans-serif;
        background: #f8f9fa;
    }

    /* Sidebar */
    #sidebar {
        min-width: 250px;
        background: #343a40;
        color: #fff;
        height: 100vh;
    }
    #sidebar a {
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 12px 20px;
    }
    #sidebar a:hover { background: #495057; }
    #sidebar a.active, #sidebar a.bg-secondary { background: #6c757d; }

    /* Main content */
    #content {
        flex: 1;
        padding: 20px;
    }

    /* Topbar */
    .topbar {
        height: 60px;
        background: #fff;
        display: flex;
        align-items: center; 
        justify-content: space-between;
        padding: 0 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .topbar .user img {
        width: 40px; 
        height: 40px; 
        border-radius: 50%; 
        margin-right: 10px;
    }

    .card {
        border-radius: 10px;
        background: #fff;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    footer {
        width: 100%;
        background: #fff;
        text-align: center;
        padding: 10px 0;
        box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
        position: fixed;
        bottom: 0;
    }
</style>
</head>

<body>

<!-- SIDEBAR -->
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

<!-- CONTENT -->
<div id="content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="logo d-flex align-items-center">
            <i class="fa-solid fa-plane-departure me-2"></i>
            <span class="fw-bold">Admin Panel</span>
        </div>

        <div class="user d-flex align-items-center">
            <img src="uploads/logo.png" alt="User">
            <span><?= $_SESSION['user']['full_name'] ?? '' ?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-users"></i> Bước 2: Chọn hoặc thêm khách hàng</h3>

    <!-- THÔNG BÁO -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- FORM CHỌN KHÁCH HÀNG CŨ -->
    <div class="card mb-4">
        <h5><i class="fa-solid fa-user-check"></i> Chọn khách hàng có sẵn</h5>

        <form action="?act=choose_customer" method="POST" class="row g-3 mt-2">

            <div class="col-md-10">
                <select name="customer_id" class="form-select" required>
                    <option value="">-- Chọn khách hàng --</option>

                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['customer_id'] ?>">
                            <?= $c['full_name'] ?> - <?= $c['contact'] ?>
                            | Năm sinh: <?= $c['birth_year'] ?>
                            | CCCD: <?= $c['id_number'] ?>
                            | Thanh toán: <?= $c['payment_status'] ? 'Đã TT' : 'Chưa TT' ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fa-solid fa-plus"></i> Thêm
                </button>
            </div>

        </form>
    </div>

    <!-- FORM TẠO KHÁCH HÀNG MỚI -->
    <div class="card mb-4">
        <h5><i class="fa-solid fa-user-plus"></i> Tạo khách hàng mới</h5>

        <form action="?act=choose_customer" method="POST" class="row g-3 mt-2">

            <input type="hidden" name="create_new" value="1">

            <div class="col-md-4">
                <label class="form-label">Họ và tên *</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Giới tính</label>
                <select class="form-select" name="gender">
                    <option value="1">Nam</option>
                    <option value="2">Nữ</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Năm sinh</label>
                <input type="number" name="birth_year" class="form-control" min="1900" max="<?= date('Y') ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label">Liên hệ *</label>
                <input type="text" name="contact" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">CCCD/CMND</label>
                <input type="text" name="id_number" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Thanh toán</label>
                <select class="form-select" name="payment_status">
                    <option value="0">Chưa thanh toán</option>
                    <option value="1">Đã thanh toán</option>
                </select>
            </div>

            <div class="col-md-5">
                <label class="form-label">Yêu cầu đặc biệt</label>
                <input type="text" name="special_request" class="form-control" placeholder="VD: Ăn chay, dị ứng...">
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-success">
                    <i class="fa-solid fa-save"></i> Thêm khách hàng
                </button>
            </div>
        </form>
    </div>

    <!-- DANH SÁCH KHÁCH HÀNG ĐÃ CHỌN -->
    <div class="card mb-5">
        <h5><i class="fa-solid fa-list"></i> Khách hàng đã chọn</h5>

        <?php if (empty($selectedCustomers)): ?>

            <p class="text-muted mt-3">Chưa có khách nào được chọn.</p>

        <?php else: ?>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Họ tên</th>
                            <th>Giới tính</th>
                            <th>Năm sinh</th>
                            <th>Liên hệ</th>
                            <th>CCCD</th>
                            <th>Thanh toán</th>
                            <th>Yêu cầu đặc biệt</th>
                            <th width="80">Xóa</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($selectedCustomers as $id => $kh): ?>
                            <tr>
                                <td><?= $kh['full_name'] ?></td>
                                <td><?= $kh['gender'] == 1 ? 'Nam' : 'Nữ' ?></td>
                                <td><?= $kh['birth_year'] ?></td>
                                <td><?= $kh['contact'] ?></td>
                                <td><?= $kh['id_number'] ?></td>
                                <td><?= $kh['payment_status'] ? 'Đã TT' : 'Chưa TT' ?></td>
                                <td><?= $kh['special_request'] ?></td>

                                <td class="text-center">
                                    <a href="?act=choosecustomer_remove&id=<?= $id ?>" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <div class="text-end mt-3">
    <form action="?act=storebooking" method="POST">
        <button type="submit" class="btn btn-primary btn-lg">
            Lưu đặt tour <i class="fa-solid fa-arrow-right"></i>
        </button>
    </form>
</div>

        <?php endif; ?>

    </div>

</div>

<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
