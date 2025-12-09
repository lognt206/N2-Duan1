<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session, nếu chưa có thì mặc định là "Hướng dẫn viên"
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';

// Dùng htmlspecialchars để tránh lỗi HTML injection
$nameUser = htmlspecialchars($nameUser);
//nếu tour có trạng thái =đã hoàn thành thì k hiện cột check-in
$isCompleted = $tour_detail_data['is_completed'] ?? false;
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
        .bg-primary { background-color: #13499aff !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        footer { width:100%; background:#fff; position:fixed; bottom:0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
        #sidebar a i { color: #fff !important; }
        .container { max-width: 1000px; margin: 20px auto; padding: 30px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);}
        .page-main-title { color: #333; font-size: 28px; margin-bottom: 5px;}
        .tour-status { font-size: 16px; margin-bottom: 15px;}
        .status-badge { padding: 4px 8px; border-radius: 4px; font-weight: bold;}
        .status-badge.upcoming { background-color: #fff3cd;  color: #856404;}
        .status-badge.checkin { background-color: #d4edda; color: #155724;}
        .status-badge.pending { background-color: #f8d7da; color: #721c24;}
        .quick-actions { display: flex; justify-content: flex-end; gap: 10px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;}
        .btn-back, .btn-primary { text-decoration: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; transition: background-color 0.2s;}
        .btn-back { background-color: #f8f9fa; color: #6c757d; border: 1px solid #ccc;}
        .btn-primary { background-color: #000000ff; color: white;}
        .tabs { display: flex; margin-bottom: 0; border-bottom: 2px solid #ddd;}
        .tab-button { background-color: #f4f4f4; border: none; padding: 12px 20px; cursor: pointer; font-size: 16px; font-weight: 600; color: #555; border-radius: 6px 6px 0 0; margin-right: 5px; border: 1px solid transparent; border-bottom: none;}
        .tab-button.active { background-color: #ffffff; color: #000000; border-color: #ddd; border-bottom: 2px solid #000000ff; margin-bottom: -2px; }
        .tab-content-wrapper { padding: 20px 0;}
        .tab-pane { padding: 10px 0;}
        .tab-pane.hidden { display: none;}
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px 30px;}
        .info-item .label { font-weight: bold; color: #666; display: block; margin-bottom: 3px; font-size: 14px;}
        .info-item .value { color: #333; font-size: 16px;}
        .info-item.full-width { grid-column: span 2;}
        .guest-table { width: 100%; border-collapse: collapse; margin-top: 15px;}
        .guest-table th, .guest-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee;}
        .guest-table th { background-color: #f8f8f8; color: #333;}
        .guest-actions { margin-top: 20px; text-align: right;}
        .itinerary-list { margin-top: 15px; padding-left: 20px;}
        .itinerary-list li { margin-bottom: 10px; line-height: 1.5;}
        .table th { background: #343a40; color: white; }
        .checkin-toggle { opacity: 0; width: 0; height: 0;}
        .toggle-label { position: relative; display: inline-block; width: 50px; height: 25px; background-color: #ccc; border-radius: 25px; cursor: pointer; transition: background-color 0.3s;}
        .toggle-label::after { content: ''; position: absolute; width: 21px; height: 21px; border-radius: 50%; background-color: white; top: 2px; left: 2px; transition: transform 0.3s;}
        .checkin-toggle:checked + .toggle-label { background-color: #28a745;}
        .checkin-toggle:checked + .toggle-label::after { transform: translateX(25px);}
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule" class="active"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
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
        <h2 class="page-main-title">Chi tiết Tour: <?= $tour_detail_data['tour_name'] ?></h2>
        <p class="tour-status">Trạng thái: <span class="status-badge upcoming">Sắp khởi hành</span></p>

        <div class="quick-actions">
            <a href="?act=schedule" class="btn-back">Quay lại Lịch làm việc</a>
            <a href="report.php?id=HL25" class="btn-primary"> Cập nhật tình trạng tour</a>
        </div>

        <div class="tabs">
            <button class="tab-button active" onclick="showTab('info')">
                Thông tin chung
            </button>
            <button class="tab-button" onclick="showTab('guests')">
                 Danh sách khách (<?= $tour_detail_data['num_people'] ?> người)
            </button>
            <button class="tab-button" onclick="showTab('itinerary')">
                 Lộ trình chi tiết
            </button>
            <button class="tab-button" onclick="showTab('report')">
                 Nhật ký tour
            </button>
        </div>
        
        <div class="tab-content-wrapper">
            
            <div id="info" class="tab-pane active">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Ngày bắt đầu Tour:</span>
                        <span class="value"><?= $tour_detail_data['departure_date'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Ngày kết thúc Tour:</span>
                        <span class="value"><?= $tour_detail_data['return_date'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Điểm gặp gỡ:</span>
                        <span class="value"><?= $tour_detail_data['meeting_point'] ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Số người:</span>
                        <span class="value"><?= $tour_detail_data['num_people'] ?></span>
                    </div>
                    </div>
                </div>
            </div>
            
            <div id="guests" class="tab-pane hidden">
                <h3>Danh sách khách hàng</h3> 
                <div class="summary-box">
                    <span>Tổng khách: <strong><?= $tour_detail_data['num_people'] ?></strong></span>|
                    <span>Đã check-in: <strong id="checked-in-count"> </strong></span>|
                    <span>Còn lại: <strong id="remaining-count"> </strong></span>
                </div>
                <table class="table table-bordered align-middle text-center data-table guest-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Giới tính</th>
                            <th>Năm sinh</th>
                            <th>Liên hệ</th>
                            <th>Yêu cầu đặc biệt</th>
                            <?php if(!$isCompleted): ?>
                            <th class="text-center">Check_in</th>
                            <?php endif?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tour_detail_data['customers'] as $index => $data): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($data['full_name']) ?></td>
                            <td><?= isset($data['gender']) ? ($data['gender'] == 1 ? "Nam" : "Nữ") : "Chưa xác định" ?></td>
                            <td><?= $data['birth_year'] ?? "Chưa xác định" ?></td>
                            <td><?= htmlspecialchars($data['contact'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['special_request'] ?? '-') ?></td>
                            <?php if(!$isCompleted): ?>
                            <td class="text-center">
                                <input type="checkbox" class="checkin-toggle" id="guest<?= $index ?>" checked>
                                <label for="guest<?= $index ?>" class="toggle-label"></label>
                            </td>
                            <?php endif?>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
               
            </div>

            <div id="itinerary" class="tab-pane hidden">
                <h3>Lịch trình chi tiết</h3>
                <?php if(!empty($tour_detail_data['itineraries'])): ?>
                <?php foreach($tour_detail_data['itineraries'] as $it): ?>
                    <div class="itinerary-item">
                        Ngày <?= $it['day_number'] ?> (<?= $it['start_time'] ?> - <?= $it['end_time'] ?>): <?= $it['activity'] ?>  tại <?= $it['location'] ?>
                    </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted">Chưa có hành trình</span>
                <?php endif; ?>
               
            </div>
            
            <div id="report" class="tab-pane hidden" style="margin-bottom: 100px;">
                <h3>Nhật ký tour</h3>
                <table class="table table-bordered align-middle text-center data-table guest-table">
                    <thead>
                        <th>STT</th>
                        <th>Ngày tạo</th>
                        <th>Khách hàng đánh giá</th>
                        <th>Ảnh</th>
                        <th>Cảm nhận của HDV</th>
                        <th>Hành động</th>
                    </thead>
                    <?php foreach($tourlogs as $index => $tourlog ){ 
                    ?>
                    <tbody>
                        <td><?= $index + 1 ?></td>
                        <td><?= $tourlog['log_date'] ?></td>
                        <td><?= $tourlog['content'] ?></td>
                        <td><img src="" alt=""><?= $tourlog['photo'] ?? null ?></td>
                        <td><?= $tourlog['guide_review'] ?></td>
                        <td>
                            <a href="index.php?act=edit_nhat_ky&id=<?= $tourlog['log_id'] ?>" class="btn btn-sm btn-warning me-1">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="index.php?act=delete_tour_log&id=<?= $tourlog['log_id'] ?>" onclick="return confirm('Xóa khách hàng này?')" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<script>
        function showTab(tabId) {
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
                pane.classList.add('hidden');
            });
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        //Điểm danh khách
            document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('.checkin-toggle');
        const checkedCount = document.getElementById('checked-in-count');
        const remainingCount = document.getElementById('remaining-count');
        const filterBtn = document.getElementById('filter-btn');
        let filtered = false;

        function updateSummary() {
            const total = checkboxes.length;
            const checked = document.querySelectorAll('.checkin-toggle:checked').length;
            checkedCount.textContent = checked;
            remainingCount.textContent = total - checked;
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

        filterBtn.addEventListener('click', () => {
            filtered = !filtered;
            document.querySelectorAll('tbody tr').forEach(row => {
            const cb = row.querySelector('.checkin-toggle');
            row.style.display = (filtered && cb.checked) ? 'none' : '';
            });
            filterBtn.textContent = filtered ? 'Hiện tất cả' : 'Lọc: Chưa check-in';
        });

        updateSummary();
    });
    </script>

</body>
</html>