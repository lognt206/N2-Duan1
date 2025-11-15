<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lịch làm việc</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    .sidebar {
      background-color: #092f68ff;
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      padding-top: 1rem;
    }
    .sidebar .nav-link {
      color: white;
      font-weight: 500;
      padding: 12px 16px;
      border-radius: 8px;
      margin: 4px 8px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 18px;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #d59d00ff;
      color: #fff;
      transform: translateX(3px);
    }
    main {
      margin-left: 240px;
      background-color: #f8f9fa;
      min-height: 100vh;
      padding: 30px;
    }
    h2.page-title {
      color: #092f68;
      font-size: 28px;
      border-bottom: 3px solid #d59d00ff;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .filter-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      flex-wrap: wrap;
      gap: 10px;
    }
    .filter-btn {
      background-color: #092f68;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 16px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }
    .filter-btn.active,
    .filter-btn:hover {
      background-color: #d59d00ff;
      color: #fff;
    }
    .search-box {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .search-box input {
      width: 270px;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      outline: none;
    }
    .btn-search {
      background-color: #d59d00ff;
      color: white;
      border: none;
      border-radius: 6px;
      padding: 8px 12px;
      cursor: pointer;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    .data-table th, .data-table td {
      padding: 14px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    .data-table th {
      background-color: #092f68ff;
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 14px;
    }
    .data-table tbody tr:hover {
      background-color: #f6f6f6;
    }
    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: bold;
      display: inline-block;
      text-align: center;
    }
    .upcoming {
      background-color: #e8d9ff;
      color: #4b0082;
    }
    .in_progress {
      background-color: #cce5ff;
      color: #004085;
    }
    .completed {
      background-color: #d4edda;
      color: #155724;
    }
    .cancelled {
      background-color: #f8d7da;
      color: #721c24;
    }

    .btn-action {
      background-color: #d59d00ff;
      color: #fff;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-action:hover {
      background-color: #b98600;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="?act=profile"><i class="bi bi-person-circle"></i>Thông tin cá nhân</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=schedule"><i class="bi bi-calendar-week"></i>Lịch làm việc</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=report"><i class="bi bi-journal-text"></i>Nhật ký tour</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=special_request"><i class="bi bi-star"></i>Yêu cầu đặc biệt</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-box-arrow-right"></i>Đăng xuất</a></li>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <h2 class="page-title">📅 Lịch làm việc của tôi</h2>
        <p class="text-muted">Xem các tour bạn đã được phân công và tình trạng của chúng.</p>

        <div class="filter-controls">
          <div class="status-filters">
            <button class="filter-btn active" data-status="all">Tất cả Tour</button>
            <button class="filter-btn" data-status="upcoming">Sắp khởi hành</button>
            <button class="filter-btn" data-status="completed">Đã hoàn thành</button>
            <button class="filter-btn" data-status="in_progress">Đang thực hiện</button>
          </div>
          <div class="search-box">
            <input type="text" placeholder="Tìm kiếm theo mã tour, tên tour..." required>
            <button class="btn-search"><i class="bi bi-search"></i></button>
          </div>
        </div>

        <table class="data-table tour-schedule-table">
          <thead>
            <tr>
              <th>Mã Tour</th>
              <th>Tên Tour</th>
              <th>Ngày Bắt đầu</th>
              <th>Ngày kết thúc</th>
              <th>Địa điểm tập trung</th>
              <th>Tình trạng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php
              if(!empty($lich_lam_viec)){
                foreach ($lich_lam_viec as $tour){
            ?>  
              <tr data-status="<?= $tour['trangthai'] ?>">
                <td><?=$tour['departure_id'] ?></td>
                <td><?=$tour['tour_name'] ?></td>
                <td><?=$tour['departure_date'] ?></td>
                <td><?=$tour['return_date'] ?></td>
                <td><?=$tour['meeting_point'] ?></td>
                <td>
                  <span class="status-badge<?= $tour['trangthai'] ?>"><?= $tour['tinh_trang'] ?></span>
                </td>
                <td><a href="tour_detail.php?id=<?= $tour['departure_id'] ?>" class="btn-action view">Xem chi tiết</a></td>
              </tr>
            <?php }}
            else { ?>
    <tr>
        <td colspan="7" class="text-center">Bạn không có tour nào trong lịch làm việc.</td>
    </tr>
            <?php } 
            ?>
            
          </tbody>
        </table>
      </main>
    </div>
  </div>
  <script>
    document.querySelectorAll('.filter-btn').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        const filterStatus = this.getAttribute('data-status');
        const rows = document.querySelectorAll('.tour-schedule-table tbody tr');
        rows.forEach(row => {
          const rowStatus = row.getAttribute('data-status');
          row.style.display = (filterStatus === 'all' || rowStatus === filterStatus) ? '' : 'none';
        });
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
