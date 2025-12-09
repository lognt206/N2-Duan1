<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên hướng dẫn viên từ session
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
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; font-family: Arial, sans-serif;}
        #main-wrapper { display: flex; flex: 1;}

        /* SIDEBAR */
        #sidebar { min-width: 250px; background: #343a40; color: #fff; }
        #sidebar a { color: #fff; padding: 12px 20px; display: block; text-decoration: none; }
        #sidebar a:hover, #sidebar a.active { background: #495057; }

        /* CONTENT */
        #content { flex: 1; padding: 20px; background: #f8f9fa;}
        .topbar { height: 60px; background: #fff; display: flex; align-items: center;
            justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;}
        .topbar .user img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;}

        /* FILTERS */
        .filter-controls { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;}
        .filter-btn { background-color: #000001ff; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;}
        .filter-btn.active, .filter-btn:hover { background-color: #d59d00;}
        .search-box input { width: 250px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px;}
        .btn-search { background-color: #d59d00; color: white; border: none; padding: 8px 12px; border-radius: 6px;}

        /* TABLE */
        .data-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);}
        .data-table th, .data-table td { padding: 14px 15px; border-bottom: 1px solid #eee; }
        .data-table th { background: #000; color: white; text-transform: uppercase; font-size: 14px; }

        .data-table td img {
            width: 70px; 
            height: 50px; 
            object-fit: cover; 
            border-radius: 6px; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; }
        .upcoming { background: #e8d9ff; color: #4b0082;}
        .in_progress { background: #cce5ff; color: #004085;}
        .completed { background: #d4edda; color: #155724;}
        .cancelled { background: #f8d7da; color: #721c24;}

        .btn-action { background-color: #d59d00; color: #fff; padding: 6px 12px; border-radius: 6px; }
        .btn-action:hover { background: #b98600;}

        footer { width: 100%; background:#fff; padding: 10px; text-align:center; box-shadow:0 -2px 4px rgba(0,0,0,0.1);}
    </style>
</head>
<body>

<div id="main-wrapper">

    <!-- Sidebar -->
    <div id="sidebar">
        <h3 class="text-center py-3 border-bottom">Guide Panel</h3>
        <a href="?act=header"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
        <a href="?act=schedule" class="active"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
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
                    <button class="filter-btn" data-status="cancelled">Sắp khởi hành</button>
                    <button class="filter-btn" data-status="in_progress">Đang thực hiện</button>
                    <button class="filter-btn" data-status="upcoming">Đã hoàn thành</button>
                    <button class="filter-btn" data-status="completed">Đã hủy</button>
                </div>

                <div class="search-box">
                    <input type="text" placeholder="Tìm kiếm theo mã tour, tên tour...">
                    <button class="btn-search"><i class="fa-solid fa-search"></i></button>
                </div>
            </div>

            <table class="data-table table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Mã Tour</th>
                    <th>Tên Tour</th>
                    <th>Ngày Bắt đầu</th>
                    <th>Ngày Kết thúc</th>
                    <th>Địa điểm tập trung</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($lich_lam_viec)): ?>
                <?php foreach ($lich_lam_viec as $tour): ?>

    <?php  
        $tourImage = htmlspecialchars($tour['image'] ?? '');
        $bookingId = htmlspecialchars($tour['booking_id'] ?? '');
        $tourName = htmlspecialchars($tour['tour_name'] ?? '');
        $departureDate = htmlspecialchars($tour['departure_date'] ?? '');
        $returnDate = htmlspecialchars($tour['return_date'] ?? '');
        $meetingPoint = htmlspecialchars($tour['meeting_point'] ?? '');

        $statusValue = $tour['schedule_status'] ?? $tour['tour_status'] ?? 0;

        switch ($statusValue) {
            case 1: $statusText='Đã hoàn thành'; $statusClass='completed'; break;
            case 2: $statusText='Đang thực hiện'; $statusClass='in_progress'; break;
            case 3: $statusText='Đã hủy'; $statusClass='cancelled'; break;
            default: $statusText='Sắp khởi hành'; $statusClass='upcoming';
        }
    ?>

    <tr>
    <td>
    <img src="<?= $tourImage ?>" style="width:80px; height:60px; object-fit:cover;">
</td>
        <td><?= $bookingId ?></td>
        <td><?= $tourName ?></td>
        <td><?= $departureDate ?></td>
        <td><?= $returnDate ?></td>
        <td><?= $meetingPoint ?></td>
        <td>
            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
        </td>
        <td>
            <a href="?act=tour_detail&id=<?= $bookingId ?>" class="btn-action">
                <i class="fa-solid fa-eye"></i>
            </a>
        </td>
    </tr>

<?php endforeach; ?>

            <?php else: ?>
                <tr><td colspan="8" class="text-center text-muted">Không có tour nào.</td></tr>
            <?php endif; ?>
            </tbody>

        </table>
        </div>

    </div>
</div>

<footer>
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

<!-- JS -->
<script>
    // Lọc trạng thái
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.status;
            document.querySelectorAll('.tour-schedule-table tbody tr').forEach(row => {
                const status = row.dataset.status;
                row.style.display = (filter === 'all' || filter === status) ? '' : 'none';
            });
        });
    });

    // Tìm kiếm
    document.querySelector('.btn-search').onclick = () => {
        const q = document.querySelector('.search-box input').value.toLowerCase();
        document.querySelectorAll('.tour-schedule-table tbody tr').forEach(row => {
            const id = row.cells[1].textContent.toLowerCase();
            const name = row.cells[2].textContent.toLowerCase();
            row.style.display = (id.includes(q) || name.includes(q)) ? '' : 'none';
        });
    };
</script>

</body>
</html>
