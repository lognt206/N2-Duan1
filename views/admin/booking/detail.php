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
    <title>Chi tiết Booking</title>
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

    <!-- TITLE -->
    <h3 class="mb-3"><i class="fa-solid fa-eye"></i> Chi tiết Booking #<?= htmlspecialchars($booking['booking_id'] ?? '') ?></h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-3"><?= htmlspecialchars($booking['tour_name'] ?? '-') ?></h2>

            <?php
            // --- PHÒNG VỆ: lấy giá tour
            $tour_price = 0;
            if (isset($booking['tour_price'])) {
                $tour_price = (float)$booking['tour_price'];
            } elseif (isset($booking['price'])) {
                $tour_price = (float)$booking['price'];
            } elseif (isset($booking['tour']['price'])) {
                $tour_price = (float)$booking['tour']['price'];
            }

            // --- Tính tổng tiền dựa trên trạng thái booking
            $num_people = isset($booking['num_people']) ? (int)$booking['num_people'] : 0;
            $status = isset($booking['status']) ? (int)$booking['status'] : 0;

            if (isset($booking['total_amount'])) {
                $total_amount = (float)$booking['total_amount'];
            } else {
                if ($status === 1) {               // Hoàn thành
                    $total_amount = $num_people * $tour_price;
                } elseif ($status === 2) {         // Đã cọc
                    $total_amount = $num_people * $tour_price * 0.5;
                } elseif ($status === 0) {         // Chờ xác nhận -> ước tính
                    $total_amount = $num_people * $tour_price;
                } else {                            // Hủy hoặc khác
                    $total_amount = 0;
                }
            }

            // format helper
            function fmtVnd($n) {
                return number_format($n, 0, ',', '.') . ' VND';
            }
            ?>

            <!-- HIỂN THỊ THÔNG TIN TỔNG HỢP (Bao gồm Giá và Tổng tiền) -->
            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Giá tour (1 khách):</div>
                <div class="col-md-8"><?= $tour_price > 0 ? fmtVnd($tour_price) : '-' ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Tổng tiền:</div>
                <div class="col-md-8">
                    <span class="fs-5 text-danger"><?= $total_amount > 0 ? fmtVnd($total_amount) : '0 VND' ?></span>
                    <div class="text-muted small">
                        <?php if ($status === 0): ?>
                            (*Ước tính tổng tiền, có thể thay đổi khi xác nhận booking)
                        <?php elseif (!isset($booking['total_amount'])): ?>
                            (*Tính tạm theo trạng thái booking nếu chưa có giá trị từ hệ thống)
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH KHÁCH -->
            <div class="row mb-4">
                <div class="col-md-12 fw-bold">Danh sách khách:</div>
                <div class="col-md-12">

                    <?php if (!empty($booking['customers'])): ?>
                        <table class="table table-bordered table-striped mt-2">
                            <thead>
                                <tr class="table-secondary">
                                    <th>#</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Năm sinh</th>
                                    <th>Số CMND</th>
                                    <th>Liên hệ</th>
                                    <th>Thanh toán</th>
                                    <th>Yêu cầu đặc biệt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking['customers'] as $index => $c): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($c['full_name'] ?? '-') ?></td>
                                        <td><?= isset($c['gender']) ? ($c['gender']==1 ? "Nam" : "Nữ") : "Chưa xác định" ?></td>
                                        <td><?= htmlspecialchars($c['birth_year'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($c['id_number'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($c['contact'] ?? '-') ?></td>
                                        <td><?= isset($c['payment_status']) ? ($c['payment_status'] ? "Đã thanh toán" : "Chưa thanh toán") : "-" ?></td>
                                        <td><?= htmlspecialchars($c['special_request'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php else: ?>
                        <p>- Không có khách hàng -</p>
                    <?php endif; ?>

                </div>
            </div>

            <!-- THÔNG TIN HƯỚNG DẪN VIÊN -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fa-solid fa-user-tie"></i> Thông tin Hướng dẫn viên
                </div>

                <div class="card-body">

                    <?php 
                    $guide = [];
                    if (!empty($booking['guide']) && is_array($booking['guide'])) {
                        $guide = $booking['guide'];
                    } else {
                        $guide = [
                            'full_name'        => $booking['guide_name']     ?? null,
                            'photo'            => $booking['guide_photo']     ?? null,
                            'birth_date'       => $booking['guide_birth']     ?? null,
                            'contact'          => $booking['guide_contact']   ?? null,
                            'certificate'      => $booking['guide_certificate'] ?? null,
                            'languages'        => $booking['guide_languages'] ?? null,
                            'experience'       => $booking['guide_experience'] ?? null,
                            'health_condition' => $booking['guide_health']    ?? null,
                            'rating'           => $booking['guide_rating']    ?? null,
                            'category'         => $booking['guide_category']  ?? null
                        ];
                    }
                    $guide_not_assigned = empty($booking['guide_id']) && empty($guide['full_name']);
                    ?>

                    <?php if ($guide_not_assigned): ?>
                        <p class="text-muted fst-italic">
                            <i class="fa-solid fa-triangle-exclamation text-warning"></i> 
                            Chưa gán hướng dẫn viên cho booking này.
                        </p>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="<?=($guide['photo'])?>"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 180px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <p><strong>Họ và tên:</strong> <?= htmlspecialchars($guide['full_name'] ?: '-') ?></p>
                                <p><strong>Ngày sinh:</strong> <?= htmlspecialchars($guide['birth_date'] ?: '-') ?></p>
                                <p><strong>Liên hệ:</strong> <?= htmlspecialchars($guide['contact'] ?: '-') ?></p>
                                <p><strong>Chứng chỉ nghề:</strong> <?= htmlspecialchars($guide['certificate'] ?: '-') ?></p>
                                <p><strong>Ngôn ngữ:</strong> <?= htmlspecialchars($guide['languages'] ?: '-') ?></p>
                                <p><strong>Kinh nghiệm:</strong><br><?= nl2br(htmlspecialchars($guide['experience'] ?: '-')) ?></p>
                                <p><strong>Tình trạng sức khỏe:</strong><br><?= nl2br(htmlspecialchars($guide['health_condition'] ?: '-')) ?></p>
                                <p><strong>Xếp hạng:</strong> <?= htmlspecialchars($guide['rating'] ?: '-') ?> / 5</p>
                                <p><strong>Phân loại:</strong> <?= ($guide['category'] == 1) ? "HDV Nội địa" : "HDV Quốc tế" ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- THÔNG TIN ĐỐI TÁC -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fa-solid fa-handshake"></i> Thông tin Đối tác dịch vụ
                </div>

                <div class="card-body">
                    <?php if (!empty($booking['partners'])): ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-light">
                                    <th>#</th>
                                    <th>Tên đối tác</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Liên hệ</th>
                                    <th>Địa chỉ</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($booking['partners'] as $i => $p): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($p['partner_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($p['service_type_name'] ?? 'Không xác định') ?></td>
                                        <td><?= htmlspecialchars($p['contact'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($p['address'] ?? '-') ?></td>
                                        <td><?= nl2br(htmlspecialchars($p['notes'] ?? '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Không có đối tác nào được gán.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- THÔNG TIN LỊCH TRÌNH -->
            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Ngày đặt:</div>
                <div class="col-md-8"><?= htmlspecialchars($booking['booking_date'] ?? '-') ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Lịch khởi hành:</div>
                <div class="col-md-8">
                    <?= htmlspecialchars($booking['departure_date'] ?? '-') ?>
                    <?= !empty($booking['return_date']) ? " → " . htmlspecialchars($booking['return_date']) : "" ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Điểm gặp gỡ:</div>
                <div class="col-md-8"><?= htmlspecialchars($booking['meeting_point'] ?? '-') ?></div>
            </div>

            <!-- KHÁC -->
            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Số người:</div>
                <div class="col-md-8"><?= (int)($booking['num_people'] ?? 0) ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Loại đặt:</div>
                <div class="col-md-8">
                    <?= isset($booking['booking_type']) ? ($booking['booking_type']==1 ? "Trực tiếp" : "Online") : '-' ?>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Trạng thái:</div>
                <div class="col-md-8">
                    <span class="badge 
                        <?= ($status===1)?'bg-success':($status===2?'bg-primary':($status===3?'bg-danger':'bg-warning')) ?>">
                        <?= ($status===1)?"Hoàn thành":($status===2?"Đã cọc":($status===3?"Đã hủy":"Chờ xử lý")) ?>
                    </span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4 fw-bold">Ghi chú:</div>
                <div class="col-md-8"><?= htmlspecialchars($booking['notes'] ?? '-') ?></div>
            </div>

            <a href="?act=booking" class="btn btn-secondary mt-3"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>

        </div>
    </div>
</div>

<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>
