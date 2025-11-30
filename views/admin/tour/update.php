<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sửa thông tin tour</title>
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
.img-preview { width: 150px; margin-top: 10px; display: block; }
.itinerary-item { margin-bottom: 10px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
footer { text-align: center; padding: 15px 0; background: #ffffff; color: #000000; }
</style>
<script>
function previewImage(event) {
    const preview = document.getElementById('image-preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}

function addItinerary(itinerary_id = '', day_number = '', start_time = '', end_time = '', activity = '', location = '') {
    const container = document.getElementById('itinerary-container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.classList.add('itinerary-item');
    div.innerHTML = `
        <input type="hidden" name="itinerary[${index}][id]" value="${itinerary_id}">
        <input type="number" name="itinerary[${index}][day_number]" placeholder="Ngày" class="form-control" style="width:80px" value="${day_number}" required>
        <input type="time" name="itinerary[${index}][start_time]" class="form-control" value="${start_time}" required>
        <input type="time" name="itinerary[${index}][end_time]" class="form-control" value="${end_time}" required>
        <input type="text" name="itinerary[${index}][activity]" placeholder="Hoạt động" class="form-control" value="${activity}" required>
        <input type="text" name="itinerary[${index}][location]" placeholder="Địa điểm" class="form-control" value="${location}" required>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Xóa</button>
    `;
    container.appendChild(div);
}
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
            <div class="logo"><i class="fa-solid fa-plane-departure me-2"></i> Admin Panel</div>
            <div class="user">
                <img src="https://via.placeholder.com/40" alt="User">
                <span><?= $_SESSION['user']['username'] ?? '';?></span>
                <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
            </div>
        </div>

        <h3 class="mb-3"><i class="fa-solid fa-plane"></i> Sửa thông tin tour</h3>
        <form action="index.php?act=update&id=<?= $tour->tour_id ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tour_id" value="<?= $tour->tour_id ?>">

            <div class="row">
                <div class="col-6">
                    <h5 class="mb-3">Thông tin cơ bản</h5>
                    <div class="mb-3">
                        <label for="tour_name" class="form-label">Tên tour</label>
                        <input type="text" class="form-control" id="tour_name" name="tour_name" value="<?= htmlspecialchars($tour->tour_name) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tour_category" class="form-label">Danh mục tour</label>
                        <select class="form-select" id="tour_category" name="tour_category">
                            <option value="1" <?= $tour->category_id == 1 ? 'selected' : '' ?>>Tour trong nước</option>
                            <option value="2" <?= $tour->category_id == 2 ? 'selected' : '' ?>>Tour ngoài nước</option>
                            <option value="3" <?= $tour->category_id == 3 ? 'selected' : '' ?>>Tour khác</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($tour->description) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($tour->price) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Ảnh tour</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*" onchange="previewImage(event)">
                        <?php if(!empty($tour->image)): ?>
                            <img src="uploads/tours/<?= htmlspecialchars($tour->image) ?>" id="image-preview" class="img-preview" alt="Preview">
                        <?php else: ?>
                            <img src="" id="image-preview" class="img-preview" style="display:none;" alt="Preview">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-6">
                    <h5 class="mb-3">Thông tin khác</h5>
                    <div class="mb-3">
                        <label for="policy" class="form-label">Chính sách</label>
                        <input type="text" class="form-control" id="policy" name="policy" value="<?= htmlspecialchars($tour->policy) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="partners" class="form-label">Nhà cung cấp</label>
                        <select name="supplier[]" class="form-select" id="partners" multiple>
                            <?php $selectedPartners = $selectedPartners ?? []; ?>
                            <?php foreach ($partners as $p): ?>
                                <option value="<?= $p->partner_id ?>" <?= in_array($p->partner_id, $selectedPartners) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->partner_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Giữ Ctrl để chọn nhiều đối tác</small>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Tình trạng</label>
                        <select class="form-select" name="status" id="status">
                            <option value="1" <?= $tour->status == 1 ? 'selected' : '' ?>>Còn mở</option>
                            <option value="0" <?= $tour->status == 0 ? 'selected' : '' ?>>Đã đóng</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Itinerary Section -->
            <h5 class="mt-4">Hành trình Tour</h5>
<div id="itinerary-container" class="mb-3">
    <?php if(!empty($itineraries)): ?>
        <?php foreach($itineraries as $i): ?>
            <div class="itinerary-item">
                <input type="hidden" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][id]" value="<?= $i['itinerary_id'] ?? '' ?>">
                <input type="number" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][day_number]" placeholder="Ngày" class="form-control" style="width:80px" value="<?= $i['day_number'] ?? '' ?>" required>

                <input type="time" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][start_time]" class="form-control" value="<?= $i['start_time'] ?? '' ?>" required >

                <input type="time" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][end_time]" class="form-control" value="<?= $i['end_time'] ?? '' ?>" required>
                <input type="text" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][activity]" placeholder="Hoạt động" class="form-control" value="<?= htmlspecialchars($i['activity'] ?? '') ?>" required>
                <input type="text" name="itinerary[<?= $i['itinerary_id'] ?? '' ?>][location]" placeholder="Địa điểm" class="form-control" value="<?= htmlspecialchars($i['location'] ?? '') ?>" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Xóa</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

            <button type="button" class="btn btn-primary btn-sm mb-3" onclick="addItinerary()">Thêm ngày/hoạt động</button>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Lưu Tour</button>
                <a href="?act=tour" class="btn btn-secondary"><i class="fa-solid fa-ban"></i> Hủy</a>
            </div>
        </form>
    </div>
</div>

<footer>
&copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
