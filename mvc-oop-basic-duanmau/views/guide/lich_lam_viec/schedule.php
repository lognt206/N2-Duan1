<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session, nếu chưa có thì mặc định là "Hướng dẫn viên"
$nameUser = $_SESSION['user']['full_name'] ?? 'Hướng dẫn viên';
$nameUser = htmlspecialchars($nameUser);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide - Lịch làm việc</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* BODY & WRAPPER */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        #main-wrapper {
            display: flex;
            flex: 1; /* cho content mở rộng */
        }

        /* SIDEBAR */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
        }
        #sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }
        #sidebar a:hover, #sidebar a.active { background: #495057; }

        /* CONTENT */
        #content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }

        .topbar {
            height: 60px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .topbar .user { display: flex; align-items: center; }
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; }

        /* FILTERS & SEARCH */
        .filter-controls { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;}
        .filter-btn { background-color: #000001ff; color: #fff; border: none; border-radius: 6px; padding: 8px 16px; cursor: pointer; font-weight: 500; transition: 0.3s;}
        .filter-btn.active, .filter-btn:hover { background-color: #d59d00;}
        .search-box { display: flex; align-items: center; gap: 5px;}
        .search-box input { width: 250px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; outline: none;}
        .btn-search { background-color: #d59d00; color: white; border: none; border-radius: 6px; padding: 8px 12px; cursor: pointer;}

        /* TABLE */
        .data-table { width: 100%; border-collapse: collapse; background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);}
        .data-table th, .data-table td { padding: 14px 15px; text-align: left; border-bottom: 1px solid #eee;}
        .data-table th { background-color: #000000ff; color: white; font-weight: 600; text-transform: uppercase; font-size: 14px;}
        .data-table tbody tr:hover { background-color: #f6f6f6;}
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; display: inline-block; text-align: center;}
        .upcoming { background-color: #e8d9ff; color: #4b0082;}
        .in_progress { background-color: #cce5ff; color: #004085;}
        .completed { background-color: #d4edda; color: #155724;}
        .cancelled { background-color: #f8d7da; color: #721c24;}
        .btn-action { background-color: #d59d00; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 14px; text-decoration: none; transition: 0.3s;}
        .btn-action:hover { background-color: #b98600;}

        /* FOOTER */
        footer {
            width: 100%;
            background:#fff;
            box-shadow:0 -2px 4px rgba(0,0,0,0.1);
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<div id="main-wrapper">
    <!-- Sidebar -->
    <div id="sidebar">
        <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
        <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
        <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
        <a href="?act=schedule" class="active"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
        <a href="?act=report"><i class="fa-solid fa-clipboard-list"></i> Nhật ký tour</a>
        <a href="?act=special_request"><i class="fa-solid fa-star"></i> Yêu cầu đặc biệt</a>
        <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

    <!-- Content -->
    <div id="content">
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

        <div class="container-fluid">
            <h2 class="page-title">Lịch làm việc của tôi</h2>
            <p class="text-muted">Các tour bạn đã được phân công và tình trạng của chúng.</p>

            <div class="filter-controls">
                <div class="status-filters">
                    <button class="filter-btn active" data-status="all">Tất cả Tour</button>
                    <button class="filter-btn" data-status="upcoming">Sắp khởi hành</button>
                    <button class="filter-btn" data-status="in_progress">Đang thực hiện</button>
                    <button class="filter-btn" data-status="completed">Đã hoàn thành</button>
                    <button class="filter-btn" data-status="cancelled">Đã hủy</button>
                </div>
                <div class="search-box">
                    <input type="text" placeholder="Tìm kiếm theo mã tour, tên tour...">
                    <button class="btn-search"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>

            <table class="data-table tour-schedule-table">
                <thead>
                    <tr>
                        <th>Mã Tour</th>
                        <th>Tên Tour</th>
                        <th>Ngày Bắt đầu</th>
                        <th>Ngày Kết thúc</th>
                        <th>Địa điểm tập trung</th>
                        <th>Trạng thái </th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($lich_lam_viec)): ?>
                    <?php foreach ($lich_lam_viec as $tour): ?>
    <?php 
        $departureId = htmlspecialchars($tour['booking_id'] ?? '');
        $tourName = htmlspecialchars($tour['tour_name'] ?? '');
        $departureDate = htmlspecialchars($tour['departure_date'] ?? '');
        $returnDate = htmlspecialchars($tour['return_date'] ?? '');
        $meetingPoint = htmlspecialchars($tour['meeting_point'] ?? '');
        
        // Status từ model (schedule_status hoặc tour_status)
        $statusValue = $tour['schedule_status'] ?? $tour['tour_status'] ?? 0;
        switch($statusValue){
            case 1: $statusText = 'Đã hoàn thành'; $statusClass='upcoming'; break;
            case 2: $statusText = 'Đang thực hiện'; $statusClass='in_progress'; break;
            case 3: $statusText = 'Đã hủy'; $statusClass='completed'; break;
            default: $statusText = 'Sắp khởi hành'; $statusClass='cancelled';
        }
    ?>
    <tr data-status="<?= $statusClass ?>">
        <td><?= $departureId ?></td>
        <td><?= $tourName ?></td>
        <td><?= $departureDate ?></td>
        <td><?= $returnDate ?></td>
        <td><?= $meetingPoint ?></td>
        <td><span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span></td>
        <td>
            <a href="?act=tour_detail&id=<?= $departureId ?>" class="btn-action"><i class="fa-solid fa-eye"></i></a>
        </td>
    </tr>
<?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Bạn không có tour nào trong lịch làm việc.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<script>
    // Lọc theo trạng thái
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const filterStatus = this.getAttribute('data-status');
            document.querySelectorAll('.tour-schedule-table tbody tr').forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                row.style.display = (filterStatus === 'all' || rowStatus == filterStatus) ? '' : 'none';
            });
        });
    });

    // Tìm kiếm
    document.querySelector('.btn-search').addEventListener('click', function() {
        const query = document.querySelector('.search-box input').value.toLowerCase();
        document.querySelectorAll('.tour-schedule-table tbody tr').forEach(row => {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            row.style.display = (id.includes(query) || name.includes(query)) ? '' : 'none';
        });
    });
</script>

</body>
</html>
