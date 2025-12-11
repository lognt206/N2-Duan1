<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session, nếu chưa có thì mặc định là "Hướng dẫn viên"
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';

// Dùng htmlspecialchars để tránh lỗi HTML injection
$nameUser = htmlspecialchars($nameUser);
$tour_log = $tour_log ?? [
    'log_id' => '', 
    'photo' => '', 
    'guide_review' => ''
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom CSS giống customer -->
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover, #sidebar a.active { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .topbar .user { display: flex; align-items: center; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }
        .card-stats { padding: 20px; color: #fff; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        footer { width:100%; background:#fff; position:fixed; bottom:0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
        #sidebar a i { color: #fff !important; }
        .text-primary{font-weight: 500; color: #212529 !important;}
        .form-section { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 50px;}
        .form-section legend { font-weight: 400; color: #000000ff;}
        .btn-submit { background-color: #d59d00ff; color: #fff; border: none; padding: 12px 40px; border-radius: 6px; margin-bottom: 50px;}
        .btn-submit:hover { background-color: #c02600ff; transform: translateY(-2px);}
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <a href="?act=dashboard" class="text-decoration-none text-dark">
                <i class="fa-solid fa-plane-departure"></i> Guide Panel
            </a>
        </div>
        <div class="user">
            <img src="uploads/logo.png" alt="User">
                <span><?= $nameUser ?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container-fluid">
        <h2 class="text-primary mb-3">Chỉnh sửa Nhật ký Tour</h2>

        <form action="index.php?act=update_nhat_ky" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="log_id" value="<?= $tour_log['log_id'] ?>">
            <input type="hidden" name="old_photo" value="<?= htmlspecialchars($tour_log['photo']) ?>">
            <input type="hidden" name="booking_id" value="<?= $tour_log['booking_id'] ?>">

            <fieldset class="form-section">
                <legend>Ảnh Tour (Cập nhật)</legend>
                
                <?php if (!empty($tour_log['photo'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Ảnh hiện tại:</label>
                        <div>
                            <img src="<?= htmlspecialchars($tour_log['photo']) ?>" alt="Ảnh hiện tại" style="max-width: 200px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="photo_file" class="form-label">Tải lên Ảnh mới (chọn file khác để thay thế):</label>
                    <input type="file" id="photo_file" name="photo_file" class="form-control" accept="image/*">
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Cảm nhận của Hướng dẫn viên</legend>
                <div class="mb-3">
                    <label for="guide_review" class="form-label">Cảm nhận/Đánh giá của HDV:</label>
                    <textarea id="guide_review" name="guide_review" class="form-control" rows="6" required placeholder="Nhập cảm nhận và đánh giá của bạn về chuyến tour..."><?= htmlspecialchars($tour_log['guide_review']) ?></textarea>
                </div>
            </fieldset>

            <div class="text-center">
                <button type="submit" class="btn btn-submit">Lưu Cập nhật</button>
            </div>
            
            <!-- <a href="index.php?act=report" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Quay lại Nhật ký Tour
            </a> -->
            
        </form>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>