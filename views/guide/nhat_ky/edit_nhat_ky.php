<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';
$nameUser = htmlspecialchars($nameUser);

$tour_log = $tour_log ?? [
    'log_id'        => '',
    'booking_id'    => '',
    'photo'         => '',
    'content'       => '',
    'guide_review'  => ''
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Nhật ký Tour</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }
        #sidebar { width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; }
        #sidebar a:hover { background: #495057; }
        #content { flex: 1; padding: 20px; background: #f8f9fa; }
        .topbar { height: 60px; background: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-section { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .btn-submit { background-color: #d59d00ff; color: #fff; padding: 12px 40px; border-radius: 6px; border: none; }
        .btn-submit:hover { background-color: #c02600ff; }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sidebar">
    <h4 class="text-center py-3 border-bottom">Guide Panel</h4>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">

    <div class="topbar">
        <strong>Chỉnh sửa Nhật ký Tour</strong>
        <div>
            <img src="uploads/logo.png" width="35" class="rounded-circle">
            <span><?= $nameUser ?></span>
        </div>
    </div>

    <form action="index.php?act=update_nhat_ky" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="log_id" value="<?= $tour_log['log_id'] ?>">
        <input type="hidden" name="booking_id" value="<?= $tour_log['booking_id'] ?>">
        <input type="hidden" name="old_photo" value="<?= htmlspecialchars($tour_log['photo']) ?>">

        <!-- ẢNH TOUR -->
        <fieldset class="form-section">
            <legend>Ảnh Tour</legend>

            <?php if (!empty($tour_log['photo'])): ?>
                <img src="<?= htmlspecialchars($tour_log['photo']) ?>" style="max-width:200px" class="mb-3 border rounded">
            <?php endif; ?>

            <input type="file" name="photo_file" class="form-control" accept="image/*">
        </fieldset>

        <!-- PHẢN HỒI KHÁCH HÀNG -->
        <fieldset class="form-section">
            <legend>Phản hồi của khách hàng</legend>
            <textarea name="content" class="form-control" rows="5"
                placeholder="Nhập phản hồi của khách hàng về chuyến tour..."><?= htmlspecialchars($tour_log['content']) ?></textarea>
        </fieldset>

        <!-- CẢM NHẬN HDV -->
        <fieldset class="form-section">
            <legend>Cảm nhận của Hướng dẫn viên</legend>
            <textarea name="guide_review" class="form-control" rows="5" required
                placeholder="Nhập đánh giá và cảm nhận của bạn..."><?= htmlspecialchars($tour_log['guide_review']) ?></textarea>
        </fieldset>

        <div class="text-center">
            <button type="submit" class="btn btn-submit">Lưu cập nhật</button>
        </div>
    </form>

</div>

</body>
</html>
