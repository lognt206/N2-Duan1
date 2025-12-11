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
<title>Bước 1: Nhập thông tin Booking</title>

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
    #sidebar a.bg-secondary { background: #6c757d; }

    #content {
        flex: 1;
        padding: 20px;
    }

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
            <span><?= $_SESSION['user']['full_name'] ?? ''; ?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-plus"></i> Bước 1: Nhập thông tin Booking</h3>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <form id="bookingForm" action="?act=booking_step1" method="POST">

            <!-- Tour & Guide -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tour <span class="text-danger">*</span></label>
                    <select name="tour_id" class="form-control" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>"><?= $t['tour_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                    <select name="guide_id" class="form-control" required>
                        <option value="">-- Chọn hướng dẫn viên --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= $g['guide_id'] ?>"><?= $g['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Dates -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Ngày đi <span class="text-danger">*</span></label>
                    <input type="date" name="departure_date" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày về <span class="text-danger">*</span></label>
                    <input type="date" name="return_date" class="form-control" required>
                </div>
            </div>

            <!-- Meeting -->
            <div class="mb-3">
                <label class="form-label">Điểm hẹn <span class="text-danger">*</span></label>

                <input type="text" name="meeting_point" class="form-control" required>
            </div>


            <!-- Booking Information -->

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Ngày đặt <span class="text-danger">*</span></label>
                    <input type="date" name="booking_date" class="form-control" required>
                </div>


                <div class="col-md-6">

                    <label class="form-label">Loại đặt <span class="text-danger">*</span></label>
                    <select name="booking_type" class="form-control" required>
                        <option value="">-- Chọn --</option>
                        <option value="1">Trực tiếp</option>
                        <option value="2">Online</option>
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="0">Chờ xác nhận</option>
                    <option value="2">Đã cọc</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <button class="btn btn-primary">
                <i class="fa-solid fa-arrow-right"></i> Thêm khách hàng
            </button>
            <a href="?act=booking" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>


<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>


<!-- VALIDATE NGÀY -->
<script>
document.getElementById("bookingForm").addEventListener("submit", function(e) {
    const departure = document.querySelector("input[name='departure_date']").value;
    const retour    = document.querySelector("input[name='return_date']").value;
    const booking   = document.querySelector("input[name='booking_date']").value;

    const today = new Date().toISOString().split("T")[0];

    // Validate 1: Ngày đi >= hôm nay
    if (departure < today) {
        alert("❌ Ngày đi không được nhỏ hơn ngày hiện tại!");
        e.preventDefault();
        return;
    }

    // Validate 2: Ngày về >= hôm nay
    if (retour < today) {
        alert("❌ Ngày về không được nhỏ hơn ngày hiện tại!");
        e.preventDefault();
        return;
    }

    // Validate 3: Ngày đi >= ngày đặt
    if (departure < booking) {
        alert("❌ Ngày đi không được nhỏ hơn ngày đặt!");
        e.preventDefault();
        return;
    }

    // Validate 4: Ngày về >= ngày đặt
    if (retour < booking) {
        alert("❌ Ngày về không được nhỏ hơn ngày đặt!");
        e.preventDefault();
        return;
    }

    // Validate 5: Ngày về >= ngày đi
    if (retour < departure) {
        alert("❌ Ngày về không được nhỏ hơn ngày đi!");
        e.preventDefault();
        return;
    }
});
</script>


</body>
</html>
