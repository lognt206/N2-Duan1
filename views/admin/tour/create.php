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
<title>Thêm tour mới</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
#main { display: flex; flex: 1; }
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
.itinerary-item { margin-bottom: 10px; display: flex; gap: 10px; }
footer { text-align: center; padding: 15px 0; background: #343a40; color: #fff; }
.img-preview { width: 150px; margin-top: 10px; }
</style>
<script>
function addItinerary() {
    const container = document.getElementById('itinerary-container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.classList.add('itinerary-item');
    div.innerHTML = `
        <input type="number" name="itinerary[${index}][day_number]" placeholder="Ngày" class="form-control" style="width:80px" min="1" required>
        <input type="text" name="itinerary[${index}][activity]" placeholder="Hoạt động" class="form-control" required maxlength="100">
        <input type="text" name="itinerary[${index}][location]" placeholder="Địa điểm" class="form-control" required maxlength="100">
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Xóa</button>
    `;
    container.appendChild(div);
}
function previewImage(event) {
    const preview = document.getElementById('image-preview');
    const file = event.target.files[0];
    if(file && file.size > 2*1024*1024){ // 2MB
        alert('Kích thước ảnh không được vượt quá 2MB.');
        event.target.value = '';
        preview.style.display = 'none';
        return;
    }
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}

// Validate toàn bộ form trước khi submit
document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e){
        let valid = true;

        // Validate itinerary
       // Validate itinerary (chỉ kiểm tra ngày > 0)
const itineraries = document.querySelectorAll('#itinerary-container .itinerary-item');
itineraries.forEach((item, index) => {
    const day = item.querySelector(`input[name="itinerary[${index}][day_number]"]`);

    if(parseInt(day.value) <= 0){
        alert(`Ngày ở mục ${index+1} phải lớn hơn 0`);
        valid = false;
        return;
    }
});


        // Validate select supplier
        const supplier = form.querySelector('select[name="supplier[]"]');
        if(!supplier.value){
            alert('Vui lòng chọn ít nhất một nhà cung cấp.');
            valid = false;
        }

        if(!valid) e.preventDefault();
    });
});
</script>
</head>
<body>

<div id="main">
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
        <a href="?act=accoun"><i class="fa-solid fa-users"></i> Quản lý tài khoản </a>
        <a href="?act=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

    <!-- Content -->
    <div id="content">
        <div class="topbar">
            <div class="logo"><i class="fa-solid fa-plane-departure me-2"></i>Admin Panel</div>
            <div class="user">
           <img src="uploads/logo.png" alt="User">
<span><?= $_SESSION['user']['full_name'] ?? ''; ?></span>
                <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
            </div>
        </div>

        <h3 class="mb-3"><i class="fa-solid fa-plane"></i> Thêm Tour mới</h3>
        <form action="index.php?act=store" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-6">
                    <h5>Thông tin cơ bản</h5>
                    <div class="mb-3">
                        <label>Tên tour</label>
                        <input type="text" name="tour_name" class="form-control" maxlength="100" pattern="^[a-zA-Z0-9\sÀ-ỹ.,!?-]+$" required
                        title="Tên tour chỉ gồm chữ, số, khoảng trắng và ký tự .,!?-">
                    </div>
                    <div class="mb-3">
    <label>Danh mục tour</label>
    <select name="tour_category" class="form-control" required>
        <option value="">-- Chọn danh mục --</option>
        <?php foreach($categories as $cat): ?>
            <?php if ($cat->status == 1): ?>
                <option value="<?= $cat->category_id ?>">
                    <?= htmlspecialchars($cat->category_name) ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>

                    <div class="mb-3">
                        <label>Mô tả</label>
                        <input type="text" name="description" class="form-control" maxlength="200">
                    </div>
                    <div class="mb-3">
                        <label>Giá (VNĐ)</label>
                        <input type="number" name="price" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="mb-3">
                        <label>Ảnh tour</label>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)" required>
                        <img id="image-preview" class="img-preview" style="display:none;" alt="Preview">
                    </div>
                </div>

                <div class="col-6">
                    <h5>Thông tin khác</h5>
                    <div class="mb-3">
    <label for="policy" class="form-label">Chính sách (PDF/DOCX)</label>
    <input type="file" class="form-control" name="policy" id="policy" accept=".pdf,.doc,.docx">
    <!-- Lưu trữ file cũ nếu không upload mới -->
    <input type="hidden" name="old_policy" value="<?= htmlspecialchars($tour->policy ?? '') ?>">

    <?php if(!empty($tour->policy) && file_exists($tour->policy)): ?>
        <a href="<?= htmlspecialchars($tour->policy) ?>" target="_blank" class="btn btn-sm btn-info mt-2">
            <i class="fa-solid fa-file-arrow-down"></i> Xem/Tải Chính sách hiện tại
        </a>
    <?php endif; ?>
</div>

                    <div class="mb-3">
                        <label>Nhà cung cấp</label>
                        <select name="supplier[]" multiple class="form-select" required>
                            <?php foreach($partners as $p): ?>
                                <option value="<?= $p->partner_id ?>"><?= $p->partner_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Giữ Ctrl để chọn nhiều đối tác</small>
                    </div>
                    <div class="mb-3">
                        <label>Tình trạng</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Chọn tình trạng --</option>
                            <option value="1">Còn mở</option>
                            <option value="0">Đã đóng</option>
                        </select>
                    </div>
                </div>
            </div>

            <h5>Hành trình Tour</h5>
            <div id="itinerary-container" class="mb-3"></div>
            <button type="button" class="btn btn-primary btn-sm mb-3" onclick="addItinerary()">Thêm ngày/hoạt động</button>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Lưu Tour</button>
                <a href="?act=tour" class="btn btn-secondary"><i class="fa-solid fa-ban"></i> Hủy</a>
            </div>
        </form>
    </div>
</div>

<footer>&copy; 2025 Công ty Du lịch. All rights reserved.</footer>
</body>
</html>
