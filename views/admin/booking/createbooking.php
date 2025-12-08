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
body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; background:#f8f9fa; }
#sidebar { min-width: 250px; background: #343a40; color: #fff; }
#sidebar h3 { margin-bottom:0; }
#sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; transition:0.3s; }
#sidebar a:hover, #sidebar a.active, #sidebar a.bg-secondary { background: #6c757d; }
#content { flex: 1; padding: 20px; }
.topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; border-radius:0.25rem; }
.topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
.card { background: #fff; border-radius: 0.5rem; }
.form-label { font-weight: 500; }
.btn { min-width: 100px; }
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
            <span><?= $_SESSION['user']['full_name'] ?? ''; ?></span>
            <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <h3 class="mb-3"><i class="fa-solid fa-plus"></i> Thêm Đặt Tour</h3>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['warning'])): ?>
        <div class="alert alert-warning"><?= $_SESSION['warning'] ?></div>
        <?php unset($_SESSION['warning']); ?>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form id="bookingForm" action="?act=storebooking" method="POST" novalidate>
            <!-- Tour & Guide -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tour <span class="text-danger">*</span></label>
                    <select name="tour_id" class="form-control" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>" <?= (isset($_SESSION['old']['tour_id']) && $_SESSION['old']['tour_id']==$t['tour_id']) ? 'selected' : '' ?>>
                                <?= $t['tour_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn tour.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hướng dẫn viên <span class="text-danger">*</span></label>
                    <select name="guide_id" class="form-control" required>
                        <option value="">-- Chọn hướng dẫn viên --</option>
                        <?php foreach ($guides as $g): ?>
                            <option value="<?= $g['guide_id'] ?>" <?= (isset($_SESSION['old']['guide_id']) && $_SESSION['old']['guide_id']==$g['guide_id']) ? 'selected' : '' ?>>
                                <?= $g['full_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn hướng dẫn viên.</div>
                </div>
            </div>

            <!-- Departure & Return -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày khởi hành <span class="text-danger">*</span></label>
                    <input type="date" name="departure_date" class="form-control" value="<?= $_SESSION['old']['departure_date'] ?? '' ?>" required>
                    <div class="invalid-feedback">Vui lòng chọn ngày khởi hành.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày về <span class="text-danger">*</span></label>
                    <input type="date" name="return_date" class="form-control" value="<?= $_SESSION['old']['return_date'] ?? '' ?>" required>
                    <div class="invalid-feedback">Vui lòng chọn ngày về (>= ngày khởi hành).</div>
                </div>
            </div>

            <!-- Meeting Point -->
            <div class="mb-3">
                <label class="form-label">Điểm hẹn <span class="text-danger">*</span></label>
                <input type="text" name="meeting_point" class="form-control" value="<?= $_SESSION['old']['meeting_point'] ?? '' ?>" required>
                <div class="invalid-feedback">Vui lòng nhập điểm hẹn.</div>
            </div>

            <!-- Khách hàng -->
            <div class="mb-3">
                <label class="form-label">Chọn khách hàng <span class="text-danger">*</span></label>
                <div style="max-height:300px; overflow:auto; border:1px solid #ccc; padding:10px;">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Chọn</th>
                                <th scope="col">Họ & Tên</th>
                                <th scope="col">Giới tính</th>
                                <th scope="col">Năm sinh</th>
                                <th scope="col">CMND/CCCD</th>
                                <th scope="col">Liên hệ</th>
                                <th scope="col">Trạng thái thanh toán</th>
                                <th scope="col">Yêu cầu đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody id="customer_list">
                            <?php foreach ($customers as $c): ?>
                                <?php
                                    $full_name = $c['full_name'] ?? '';
                                    $gender = isset($c['gender']) ? ($c['gender']==1?'Nam':($c['gender']==2?'Nữ':'Khác')) : '';
                                    $birth_year = $c['birth_year'] ?? '';
                                    $id_number = $c['id_number'] ?? '';
                                    $contact = $c['contact'] ?? '';
                                    $payment_status = isset($c['payment_status']) ? ($c['payment_status']==1?'Đã thanh toán':'Chưa thanh toán') : '';
                                    $special_request = $c['special_request'] ?? '';
                                    $checked = (!empty($_SESSION['old']['customer_ids']) && in_array($c['customer_id'], $_SESSION['old']['customer_ids'])) ? 'checked' : '';
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="customer_ids[]" value="<?= $c['customer_id'] ?>" <?= $checked ?>>
                                    </td>
                                    <td><?= $full_name ?></td>
                                    <td><?= $gender ?></td>
                                    <td><?= $birth_year ?></td>
                                    <td><?= $id_number ?></td>
                                    <td><?= $contact ?></td>
                                    <td><?= $payment_status ?></td>
                                    <td><?= $special_request ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thêm khách mới
            <div class="mb-3">
                <label class="form-label">Thêm khách mới</label>
                <div class="card p-3 mb-2" style="background:#f1f3f5;">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Họ & Tên</label>
                            <input type="text" name="new_customer_name" class="form-control" placeholder="Tên khách mới">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại / Email</label>
                            <input type="text" name="new_customer_contact" class="form-control" placeholder="Số điện thoại hoặc email">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label class="form-label">Giới tính</label>
                            <select name="new_customer_gender" class="form-control">
                                <option value="">--Chọn--</option>
                                <option value="1">Nam</option>
                                <option value="2">Nữ</option>
                                <option value="0">Khác</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Năm sinh</label>
                            <input type="number" name="new_customer_birth_year" class="form-control" placeholder="YYYY">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CMND / CCCD</label>
                            <input type="text" name="new_customer_id_number" class="form-control">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Yêu cầu đặc biệt</label>
                        <textarea name="new_customer_special_request" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="button" id="addCustomerBtn" class="btn btn-success"><i class="fa-solid fa-user-plus"></i> Thêm khách mới</button>
                </div>
            </div>

            <input type="hidden" name="num_people" id="num_people"> -->

            <!-- Booking Date & Type -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày đặt <span class="text-danger">*</span></label>
                    <input type="date" name="booking_date" class="form-control" required value="<?= $_SESSION['old']['booking_date'] ?? '' ?>">
                    <div class="invalid-feedback">Vui lòng nhập ngày đặt.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Loại đặt <span class="text-danger">*</span></label>
                    <select name="booking_type" class="form-control" required>
                        <option value="">-- Chọn loại đặt --</option>
                        <option value="1" <?= (isset($_SESSION['old']['booking_type']) && $_SESSION['old']['booking_type']==1)?'selected':'' ?>>Trực tiếp</option>
                        <option value="2" <?= (isset($_SESSION['old']['booking_type']) && $_SESSION['old']['booking_type']==2)?'selected':'' ?>>Online</option>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn loại đặt.</div>
                </div>
            </div>

            <!-- Status & Notes -->
            <div class="mb-3">
                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="0" <?= (isset($_SESSION['old']['status']) && $_SESSION['old']['status']==4)?'selected':'' ?>>Chờ xác nhận</option>
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

<script>
(() => {
    const form = document.querySelector('#bookingForm');
    const checkboxes = document.querySelectorAll('input[name="customer_ids[]"]');
    const numPeopleInput = document.getElementById('num_people');

    // Đếm số khách chọn
    const updateNumPeople = () => {
        const count = document.querySelectorAll('input[name="customer_ids[]"]:checked').length;
        numPeopleInput.value = count;
    };
    updateNumPeople();

    checkboxes.forEach(cb => cb.addEventListener('change', updateNumPeople));

    // Validate form
    form.addEventListener('submit', function(event){
        const departure = form.departure_date.value;
        const returnd = form.return_date.value;

        if (!form.checkValidity() || (departure && returnd && returnd < departure)) {
            event.preventDefault();
            event.stopPropagation();
            if(departure && returnd && returnd < departure){
                form.return_date.setCustomValidity("Ngày về phải >= ngày khởi hành.");
            } else {
                form.return_date.setCustomValidity("");
            }
        }
        form.classList.add('was-validated');
    }, false);

    // Thêm khách mới AJAX
    document.querySelector('#bookingForm button.btn-success').addEventListener('click', function(e) {
        e.preventDefault();

        const name     = document.querySelector('input[name="new_customer_name"]').value.trim();
        const contact  = document.querySelector('input[name="new_customer_contact"]').value.trim();
        const gender   = document.querySelector('select[name="new_customer_gender"]').value;
        const birth    = document.querySelector('input[name="new_customer_birth_year"]').value;
        const idNumber = document.querySelector('input[name="new_customer_id_number"]').value;
        const special  = document.querySelector('textarea[name="new_customer_special_request"]').value;

        if(!name){ alert("Vui lòng nhập tên khách"); return; }

        fetch('?act=addcustomerajax', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({name, contact, gender, birth, idNumber, special})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                const id = data.customer_id;

                // Tạo checkbox khách mới và check luôn
                const tableBody = document.querySelector('div[style*="overflow:auto"] tbody');
                const tr = document.createElement('tr');

                tr.innerHTML = `
                    <td class="text-center">
                        <input type="checkbox" name="customer_ids[]" value="${id}" checked>
                    </td>
                    <td>${name}</td>
                    <td>${gender==1?'Nam':(gender==2?'Nữ':'Khác')}</td>
                    <td>${birth || ''}</td>
                    <td>${idNumber || ''}</td>
                    <td>${contact || ''}</td>
                    <td>Chưa thanh toán</td>
                    <td>${special || ''}</td>
                `;
                tableBody.appendChild(tr);

                // Xóa input sau khi thêm
                document.querySelector('input[name="new_customer_name"]').value = '';
                document.querySelector('input[name="new_customer_contact"]').value = '';
                document.querySelector('select[name="new_customer_gender"]').value = '';
                document.querySelector('input[name="new_customer_birth_year"]').value = '';
                document.querySelector('input[name="new_customer_id_number"]').value = '';
                document.querySelector('textarea[name="new_customer_special_request"]').value = '';

                // Cập nhật số lượng khách
                updateNumPeople();
            } else {
                alert(data.error || "Lỗi thêm khách mới");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Lỗi kết nối server!");
        });
    });
})();
</script>
<?php unset($_SESSION['old']); ?>
</body>
</html>