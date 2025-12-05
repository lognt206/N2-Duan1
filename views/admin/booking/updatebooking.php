<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Hàm lấy dữ liệu cũ
function old($field, $default = '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return $_POST[$field] ?? $default;
    }
    return $_SESSION['old'][$field] ?? $default;
}

// Kiểm tra booking customer_ids
$booking_customer_ids = !empty($booking['customer_ids']) 
    ? (is_array($booking['customer_ids']) ? $booking['customer_ids'] : json_decode($booking['customer_ids'], true))
    : [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cập Nhật Đặt Tour</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; background:#f8f9fa; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; transition:0.3s; }
#sidebar a:hover, #sidebar a.active, #sidebar a.bg-secondary { background: #6c757d; }
#content { flex: 1; padding: 20px; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; border-radius:0.25rem; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.card { background: #fff; border-radius: 0.5rem; }
.form-label { font-weight: 500; }
footer { width: 100%; background: #fff; text-align: center; padding: 10px 0; box-shadow: 0 -2px 4px rgba(0,0,0,0.1); position: fixed; bottom: 0; }
.table-scroll { max-height:300px; overflow:auto; }
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
            <span><?= htmlspecialchars($_SESSION['user']['full_name'] ?? '') ?></span>
            <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-edit"></i> Cập Nhật Đặt Tour</h3>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form id="bookingForm" action="?act=updatebooking" method="POST" novalidate>
            <input type="hidden" name="booking_id" value="<?= (int)($_GET['id'] ?? 0) ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tour <span class="text-danger">*</span></label>
                    <select name="tour_id" class="form-control" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= (int)$t['tour_id'] ?>" <?= old('tour_id', $booking['tour_id'] ?? '') == $t['tour_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['tour_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                    <select name="guide_id" class="form-control" required>
                        <option value="">-- Chọn hướng dẫn viên --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= (int)$g['guide_id'] ?>" <?= old('guide_id', $booking['guide_id'] ?? '') == $g['guide_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Lịch khởi hành <span class="text-danger">*</span></label>
                    <select name="departure_id" class="form-control" required>
                        <option value="">-- Chọn lịch khởi hành --</option>
                        <?php foreach ($departures as $d): ?>
                            <option value="<?= (int)$d->departure_id ?>" <?= old('departure_id', $booking['departure_id'] ?? '') == $d->departure_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d->departure_date) ?> <?= isset($d->note) ? "(".htmlspecialchars($d->note).")" : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Booking date & type -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Ngày đặt <span class="text-danger">*</span></label>
                    <input type="date" name="booking_date" class="form-control" required value="<?= old('booking_date', $booking['booking_date'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Loại đặt <span class="text-danger">*</span></label>
                    <select name="booking_type" class="form-control" required>
                        <option value="1" <?= old('booking_type', $booking['booking_type'] ?? '')==1?'selected':'' ?>>Trực tiếp</option>
                        <option value="2" <?= old('booking_type', $booking['booking_type'] ?? '')==2?'selected':'' ?>>Online</option>
                    </select>
                </div>
            </div>

            <!-- Customers cũ -->
            <div class="mb-3">
                <label class="form-label">Chọn khách hàng hiện có</label>
                <div class="table-scroll">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Chọn</th>
                                <th>Họ & Tên</th>
                                <th>Giới tính</th>
                                <th>Năm sinh</th>
                                <th>CMND/CCCD</th>
                                <th>Liên hệ</th>
                                <th>Trạng thái thanh toán</th>
                                <th>Yêu cầu đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($customers as $c):
                            $checked = (!empty($_POST['customer_ids']) && in_array($c['customer_id'], $_POST['customer_ids'])) 
                                        || in_array($c['customer_id'], $booking_customer_ids) 
                                        ? 'checked' : '';
                            $gender = isset($c['gender']) ? ($c['gender']==1?'Nam':($c['gender']==2?'Nữ':'Khác')) : '';
                            $payment_status = isset($c['payment_status']) ? ($c['payment_status']==1?'Đã thanh toán':'Chưa thanh toán') : '';
                        ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" name="customer_ids[]" value="<?= (int)$c['customer_id'] ?>" <?= $checked ?>></td>
                                <td><?= htmlspecialchars($c['full_name']) ?></td>
                                <td><?= htmlspecialchars($gender) ?></td>
                                <td><?= htmlspecialchars($c['birth_year'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['id_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($c['contact'] ?? '') ?></td>
                                <td><?= htmlspecialchars($payment_status) ?></td>
                                <td><?= htmlspecialchars($c['special_request'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thêm khách mới -->
            <div class="mb-3">
                <label class="form-label">Thêm khách mới</label>
                <input type="text" name="new_customer_name" class="form-control mb-1" placeholder="Họ & Tên" value="<?= htmlspecialchars(old('new_customer_name')) ?>">
                <input type="text" name="new_customer_contact" class="form-control mb-1" placeholder="Liên hệ" value="<?= htmlspecialchars(old('new_customer_contact')) ?>">
                <input type="number" name="new_customer_birth" class="form-control mb-1" placeholder="Năm sinh" value="<?= htmlspecialchars(old('new_customer_birth')) ?>">
                <select name="new_customer_gender" class="form-control mb-1">
                    <option value="1" <?= old('new_customer_gender')=='1'?'selected':'' ?>>Nam</option>
                    <option value="2" <?= old('new_customer_gender')=='2'?'selected':'' ?>>Nữ</option>
                    <option value="0" <?= old('new_customer_gender')=='0'?'selected':'' ?>>Khác</option>
                </select>
                <input type="text" name="new_customer_id" class="form-control mb-1" placeholder="CMND/CCCD" value="<?= htmlspecialchars(old('new_customer_id')) ?>">
                <select name="new_customer_payment" class="form-control mb-1">
                    <option value="0" <?= old('new_customer_payment')=='0'?'selected':'' ?>>Chưa thanh toán</option>
                    <option value="1" <?= old('new_customer_payment')=='1'?'selected':'' ?>>Đã thanh toán</option>
                </select>
                <input type="text" name="new_customer_request" class="form-control" placeholder="Yêu cầu đặc biệt" value="<?= htmlspecialchars(old('new_customer_request')) ?>">
            </div>

            <!-- Ghi chú -->
            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars(old('notes', $booking['notes'] ?? '')) ?></textarea>
            </div>

            <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu</button>
            <a href="?act=booking" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>

<footer>&copy; 2025 Công ty Du lịch. All rights reserved.</footer>

<script>
(() => {
    const form = document.querySelector('#bookingForm');
    form.addEventListener('submit', function(event){
        const checked = document.querySelectorAll('input[name="customer_ids[]"]:checked').length;
        const newCustomer = form.new_customer_name.value.trim();
        if(checked === 0 && !newCustomer){
            alert("Vui lòng chọn hoặc thêm ít nhất 1 khách hàng.");
            event.preventDefault();
        }
    });
})();
</script>

</body>
</html>
