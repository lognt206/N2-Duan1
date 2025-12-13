<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';

// Lấy ID tour
$departure_id = $_GET['departure_id'] ?? 0;
$booking_id   = $_GET['booking_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhật ký tour</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-section { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .btn-submit { background-color: #d59d00; color: #fff; padding: 12px 40px; border-radius: 6px; border: none; }
        .btn-submit:hover { background-color: #c02600; }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sidebar">
    <h4 class="text-center py-3 border-bottom">Guide Panel</h4>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">

    <div class="topbar">
        <strong>Thêm nhật ký tour</strong>
        <div>
            <img src="uploads/logo.png" width="40" class="rounded-circle">
            <span class="ms-2"><?= htmlspecialchars($nameUser) ?></span>
        </div>
    </div>

    <form action="index.php?act=create_tourlog" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="departure_id" value="<?= (int)$departure_id ?>">
        <input type="hidden" name="booking_id" value="<?= (int)$booking_id ?>">

        <!-- Ảnh tour -->
        <fieldset class="form-section">
            <legend>Ảnh tour</legend>
            <input type="file" name="photo_file" class="form-control" accept="image/*">
        </fieldset>

        <!-- Phản hồi khách hàng -->
        <fieldset class="form-section">
            <legend>Phản hồi của khách hàng</legend>

            <div class="mb-3">
                <label class="form-label">Ý kiến / nhận xét:</label>
                <textarea name="customer_feedback" class="form-control" rows="4"
                          placeholder="Ghi nhận phản hồi của khách về dịch vụ, lịch trình, trải nghiệm..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Mức độ hài lòng:</label>
                <select name="customer_rating" class="form-select">
                    <option value="">-- Chọn mức đánh giá --</option>
                    <option value="5">★★★★★ Rất hài lòng</option>
                    <option value="4">★★★★ Hài lòng</option>
                    <option value="3">★★★ Bình thường</option>
                    <option value="2">★★ Không hài lòng</option>
                    <option value="1">★ Rất không hài lòng</option>
                </select>
            </div>
        </fieldset>

        <!-- Cảm nhận HDV -->
        <fieldset class="form-section">
            <legend>Cảm nhận của Hướng dẫn viên</legend>
            <textarea name="guide_review" class="form-control" rows="5" required
                      placeholder="Đánh giá tổng quan tour, khách hàng, lịch trình..."></textarea>
        </fieldset>

        <div class="text-center">
            <button type="submit" class="btn btn-submit">
                <i class="fa-solid fa-save"></i> Lưu nhật ký
            </button>
        </div>

    </form>
</div>

</body>
</html>
