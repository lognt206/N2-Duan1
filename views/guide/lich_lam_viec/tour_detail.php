<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session, nếu chưa có thì mặc định là "Hướng dẫn viên"
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';

// Dùng htmlspecialchars để tránh lỗi HTML injection
$nameUser = htmlspecialchars($nameUser);

$statusValue =(int) $tour_detail_data['status'];

switch ($statusValue) {
            case 1: $statusText='Đã hoàn thành'; $statusClass='completed'; break;
            case 2: $statusText='Đang thực hiện'; $statusClass='in_progress'; break;
            case 3: $statusText='Đã hủy'; $statusClass='cancelled'; break;
            default: $statusText='Sắp khởi hành'; $statusClass='upcoming';
        }

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

        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .upcoming { background: #e8d9ff; color: #4b0082;}
        .in_progress { background: #cce5ff; color: #004085;}
        .completed { background: #d4edda; color: #155724;}
        .cancelled { background: #f8d7da; color: #721c24;}
        form[action*="finish_tour"] {
    display: flex;
    justify-content: flex-end;
    margin-right: 12px;
}
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
        <p class="tour-status">Trạng thái: <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></p>

        <div class="quick-actions">
            <a href="?act=schedule" class="btn-back">Quay lại Lịch làm việc</a>
            <!-- <a href="report.php?id=HL25" class="btn-primary"> Cập nhật tình trạng tour</a> -->
        </div>
       <?php if ($statusValue == 2): ?>
<form action="index.php?act=finish_tour" method="post"
      onsubmit="return confirm('Bạn chắc chắn muốn kết thúc tour này?');">

    <input type="hidden" name="booking_id"
           value="<?= (int)$tour_detail_data['booking_id'] ?>">

    <button type="submit" class="btn btn-success" >
        <i class="fa-solid fa-flag-checkered"></i> Kết thúc tour
    </button>
</form>
<?php endif; ?>



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

            
            <div id="guests" class="tab-pane hidden">
                <h3>Danh sách khách hàng</h3>
                <div class="summary-box">
                    <span>Tổng khách: <strong><?= $tour_detail_data['num_people'] ?></strong></span>|
                    <?php if($statusValue==2){?> 
                    <span>Đã check-in: <strong id="checked-in-count"> </strong></span>|
                    <span>Còn lại: <strong id="remaining-count"> </strong></span>
                    <?php }?>
                </div>
                <form action="index.php?act=update_checkin&booking_id=<?= $tour_detail_data['booking_id'] ?>" method="post" id="checkin-form">
                <table class="table table-bordered align-middle text-center data-table guest-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Giới tính</th>
                            <th>Năm sinh</th>
                            <th>Liên hệ</th>
                            <th>Yêu cầu đặc biệt</th>

                            <?php if(in_array($statusValue,[1,2])){?>
                            <th class="text-center">Check_in</th>
                            <?php }?>


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

                            <?php if(in_array($statusValue,[1,2])):?>
<td class="text-center">
    <input type="checkbox"
           class="checkin-toggle"
           data-customer-id="<?= $data['customer_id'] ?>"
           id="guest<?= $data['customer_id'] ?>"
           <?= $statusValue==1 ? 'disabled' : '' ?>>
    <label for="guest<?= $data['customer_id'] ?>" class="toggle-label"></label>
</td>
<?php endif;?>


                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                </table>
               </form>
            </div>

            <div id="itinerary" class="tab-pane hidden">
    <h3>Lịch trình chi tiết & Check-in theo chặng</h3>

    <?php if(!empty($tour_detail_data['itineraries'])): ?>
        <?php foreach($tour_detail_data['itineraries'] as $itIndex => $it): ?>

            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <strong>Ngày <?= $it['day_number'] ?>:</strong>
                    <?= $it['activity'] ?> – <?= $it['location'] ?>
                    
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0 text-center align-middle">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                               <?php if(in_array($statusValue,[1,2])): ?>
                            <th>Check-in</th>
                            <?php endif; ?>


                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tour_detail_data['customers'] as $cIndex => $cus): ?>
                                <?php 
                                    $checkKey = "itinerary-{$itIndex}-customer-{$cIndex}";
                                ?>
                                <tr>
                                    <td><?= $cIndex + 1 ?></td>
                                    <td><?= htmlspecialchars($cus['full_name']) ?></td>
                                    <td><?= htmlspecialchars($cus['contact'] ?? '-') ?></td>

                                   <?php if(in_array($statusValue,[1,2])): ?>
                                    <td>
                                        <input type="checkbox"
                                            class="checkin-toggle itinerary-check"
                                            data-key="<?= $checkKey ?>"
                                            id="<?= $checkKey ?>"
                                            <?= $statusValue==1 ? 'disabled' : '' ?>>
                                        <label for="<?= $checkKey ?>" class="toggle-label"></label>
                                    </td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted fst-italic">Chưa có lịch trình chi tiết</p>
    <?php endif; ?>
</div>

           <div id="report" class="tab-pane hidden" style="margin-bottom: 100px;">
    <h3>Nhật ký tour</h3>

    <?php 
        $departure_id_for_log = $tour_detail_data['departure_id'] ?? 0;
        $booking_id_for_log   = $tour_detail_data['booking_id'] ?? 0;
    ?>

    <!-- CHỈ TOUR ĐANG THỰC HIỆN MỚI ĐƯỢC THÊM -->
    <?php if ($statusValue == 2): ?>
        <a href="index.php?act=create_tourlog_view
            &departure_id=<?= (int)$departure_id_for_log ?>
            &booking_id=<?= (int)$booking_id_for_log ?>"
            class="btn btn-dark mb-3">
            ➕ Thêm nhật ký
        </a>
    <?php endif; ?>

    <table class="table table-bordered align-middle text-center data-table guest-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày tạo</th>
                <th>Phản hồi của khách hàng</th>
                <th>Ảnh</th>
                <th>Cảm nhận của HDV</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($tourlogs)): ?>
            <?php foreach ($tourlogs as $index => $tourlog): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($tourlog['log_date']) ?></td>

                    <td class="text-start">
                        <?= nl2br(htmlspecialchars($tourlog['content'] ?? '')) ?>
                    </td>

                    <td>
                        <?php if (!empty($tourlog['photo'])): ?>
                            <img src="<?= htmlspecialchars($tourlog['photo']) ?>"
                                 width="100" height="100" style="object-fit:cover">
                        <?php else: ?>
                            <span class="text-muted">Không có ảnh</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-start">
                        <?= nl2br(htmlspecialchars($tourlog['guide_review'] ?? '')) ?>
                    </td>

                    <td>
                        <?php if ($statusValue == 2): ?>
                            <a href="index.php?act=edit_nhat_ky&id=<?= (int)$tourlog['log_id'] ?>"
                               class="btn btn-sm btn-warning me-1">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <a href="index.php?act=delete_nhatky&id=<?= (int)$tourlog['log_id'] ?>"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa nhật ký này không?')"
                               class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php else: ?>
                            <span class="text-muted fst-italic">Không được chỉnh sửa</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-muted fst-italic">
                    Chưa có nhật ký tour
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

        </div>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<script>
    /* ========= TAB ========= */
    function showTab(tabId, event) {
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

    const STATUS = <?= (int)$statusValue ?>; // 1 = hoàn thành, 2 = đang chạy
    const BOOKING_ID = <?= (int)$tour_detail_data['booking_id'] ?>;

    /* ========= ĐIỂM DANH KHÁCH ========= */
    document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.checkin-toggle:not(.itinerary-check)');
    const checkedCount = document.getElementById('checked-in-count');
    const remainingCount = document.getElementById('remaining-count');
    const BOOKING_ID = <?= (int)$tour_detail_data['booking_id'] ?>;
    const STATUS = <?= (int)$statusValue ?>;

    const key = `tour-checkin-${BOOKING_ID}`;
    const savedCheckin = JSON.parse(localStorage.getItem(key)) || {};

    function updateSummary() {
        const total = checkboxes.length;
        const checked = [...checkboxes].filter(cb => cb.checked).length;
        if(checkedCount) checkedCount.textContent = checked;
        if(remainingCount) remainingCount.textContent = total - checked;
    }

    checkboxes.forEach(cb => {
        const cid = cb.dataset.customerId;
        // Load trạng thái check-in cũ
        if (savedCheckin[cid]) cb.checked = true;

        // Nếu tour đã hoàn thành → khóa
        if (STATUS === 1) {
            cb.disabled = true;
            return;
        }

        // Tour đang chạy → cho phép check-in
        cb.addEventListener('change', () => {
            savedCheckin[cid] = cb.checked;
            localStorage.setItem(key, JSON.stringify(savedCheckin));
            updateSummary();
        });
    });

    updateSummary();
});

    /* ========= CHECK-IN THEO LỊCH TRÌNH ========= */
    document.addEventListener('DOMContentLoaded', () => {
        const itineraryChecks = document.querySelectorAll('.itinerary-check');
        const keyPrefix = `tour-itinerary-checkin-${BOOKING_ID}`;

        const savedData = JSON.parse(localStorage.getItem(keyPrefix)) || {};

        itineraryChecks.forEach(cb => {
            const key = cb.dataset.key;

            // load lại trạng thái
            if (savedData[key]) cb.checked = true;

            // tour hoàn thành → khóa
            if (STATUS === 1) {
                cb.disabled = true;
                return;
            }

            // tour đang chạy → cho sửa
            cb.addEventListener('change', () => {
                savedData[key] = cb.checked;
                localStorage.setItem(keyPrefix, JSON.stringify(savedData));
            });
        });
    });
</script>


</body>
</html>