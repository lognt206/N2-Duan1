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
<title>Thêm Đặt Tour</title>
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
    footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
</style>
</head>
<body>


<?php
// ALERT cho HDV
if(isset($_SESSION['guide_error']) && $_SESSION['guide_error'] != '') {
    echo "<script>alert('".$_SESSION['guide_error']."');</script>";
    unset($_SESSION['guide_error']);
}

// ALERT cho khách hàng
if(isset($_SESSION['customer_errors']) && !empty($_SESSION['customer_errors'])) {
    $msg = implode('\n', $_SESSION['customer_errors']);
    echo "<script>alert('".$msg."');</script>";
    unset($_SESSION['customer_errors']);
}

// ALERT cho thành công
if(isset($_SESSION['success']) && $_SESSION['success'] != '') {
    echo "<script>alert('".$_SESSION['success']."');</script>";
    unset($_SESSION['success']);
}
?>
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
    <a href="?act=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
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
            <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-plus"></i> Thêm Đặt Tour</h3>

    <div class="card p-4 shadow-sm">
        <form action="?act=storebooking" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tour</label>
                    <select name="tour_id" class="form-control" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>" <?= (isset($_SESSION['old']['tour_id']) && $_SESSION['old']['tour_id']==$t['tour_id']) ? 'selected' : '' ?>>
                                <?= $t['tour_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                   <label class="form-label">Nhóm khách</label>
<select name="group_id" class="form-control" required>
    <option value="">-- Chọn nhóm khách --</option>
    <?php foreach ($customerGroups as $grp): ?>
        <option value="<?= $grp['group_id'] ?>"
            <?= (isset($_SESSION['old']['group_id']) && $_SESSION['old']['group_id'] == $grp['group_id']) ? 'selected' : '' ?>>
            <?= $grp['group_name'] ?> (<?= $grp['total_members'] ?? '' ?> khách)
        </option>
    <?php endforeach; ?>
</select>

                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Hướng dẫn viên</label>
                    <select name="guide_id" class="form-control">
                        <option value="">-- Không chọn --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= $g['guide_id'] ?>" <?= (isset($_SESSION['old']['guide_id']) && $_SESSION['old']['guide_id']==$g['guide_id']) ? 'selected' : '' ?>>
                                <?= $g['full_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lịch khởi hành</label>
                    <select name="departure_id" class="form-control" required>
                        <option value="">-- Chọn lịch khởi hành --</option>
                        <?php foreach ($departures as $d): ?>
                            <option value="<?= $d->departure_id ?>" <?= (isset($_SESSION['old']['departure_id']) && $_SESSION['old']['departure_id']==$d->departure_id) ? 'selected' : '' ?>>
                                <?= $d->departure_date ?> → <?= $d->return_date ?? '-' ?> - <?= $d->tour_name ?? '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Ngày đặt</label>
                    <input type="date" name="booking_date" class="form-control" required
                        value="<?= $_SESSION['old']['booking_date'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Số người</label>
                    <input type="number" name="num_people" class="form-control" required min="1"
                        value="<?= $_SESSION['old']['num_people'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Loại đặt</label>
                    <select name="booking_type" class="form-control">
                        <option value="1" <?= (isset($_SESSION['old']['booking_type']) && $_SESSION['old']['booking_type']==1)?'selected':'' ?>>Trực tiếp</option>
                        <option value="2" <?= (isset($_SESSION['old']['booking_type']) && $_SESSION['old']['booking_type']==2)?'selected':'' ?>>Online</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="4" <?= (isset($_SESSION['old']['status']) && $_SESSION['old']['status']==4)?'selected':'' ?>>Chờ xác nhận</option>
                    <option value="2" <?= (isset($_SESSION['old']['status']) && $_SESSION['old']['status']==2)?'selected':'' ?>>Đã cọc</option>
                    <option value="1" <?= (isset($_SESSION['old']['status']) && $_SESSION['old']['status']==1)?'selected':'' ?>>Hoàn thành</option>
                    <option value="3" <?= (isset($_SESSION['old']['status']) && $_SESSION['old']['status']==3)?'selected':'' ?>>Đã hủy</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3"><?= $_SESSION['old']['notes'] ?? '' ?></textarea>
            </div>

            <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu</button>
            <a href="?act=booking" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>

<footer>&copy; 2025 Công ty Du lịch. All rights reserved.</footer>
<?php unset($_SESSION['old']); // xóa dữ liệu cũ ?>
</body>
</html>
