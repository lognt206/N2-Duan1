<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang hướng dẫn viên</title>

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
    table{
        margin: 10px 5px;
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
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="?act=profile"><i class="bi bi-person-circle"></i>Thông tin cá nhân</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=schedule"><i class="bi bi-calendar-week"></i>Lịch làm việc</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=report"><i class="bi bi-journal-text"></i>Nhật ký tour</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=special_request"><i class="bi bi-star"></i>Yêu cầu đặc biệt</a></li>
            <li class="nav-item"><a class="nav-link" href="?act=login"><i class="bi bi-box-arrow-right"></i>Đăng xuất</a></li>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10">
        <h2 class="text-primary mb-3">🌟 Yêu cầu Đặc biệt của Khách hàng</h2>
        <p><strong>Tour:</strong> Lucca Bike Tour - HL25 | <strong>Ngày:</strong> 02 Thg 10, 2025</p>

        <div class="summary-box">
          <span>Tổng: <strong id="total-requests">5</strong></span>
          <span>Ăn uống: <strong id="food-requests">3</strong></span>
          <span>Y tế/Khác: <strong id="medical-requests">2</strong></span>
        </div>

        <div class="filter-controls">
          <div class="status-filters">
            <button class="filter-btn active" data-status="all">Tất cả</button>
            <button class="filter-btn" data-status="upcoming">Ăn uống</button>
            <button class="filter-btn" data-status="completed">Y tế</button>
          </div>
          <div class="search-box">
            <input type="text" placeholder="Tìm theo tên khách..." required>
            <button class="btn-search"><i class="bi bi-search"></i></button>
          </div>
        </div>

        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>STT</th>
              <th>Tên Khách</th>
              <th>Loại Yêu cầu</th>
              <th>Nội dung</th>
              <th>Tình trạng</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr data-type="food">
              <td>1</td>
              <td>NGUYỄN VĂN A</td>
              <td><span class="type-badge food">Ăn chay</span></td>
              <td>Không ăn thịt, cá, trứng, sữa.</td>
              <td><span class="status-badge solved">✅ Đã xác nhận</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="food">
              <td>2</td>
              <td>TRẦN THỊ B</td>
              <td><span class="type-badge food">Dị ứng</span></td>
              <td>Dị ứng đậu phộng và hải sản.</td>
              <td><span class="status-badge pending">⚠️ Cần xác minh</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="medical">
              <td>3</td>
              <td>LÊ VĂN C</td>
              <td><span class="type-badge medical">Bệnh lý</span></td>
              <td>Bệnh tim nhẹ, cần mang theo thuốc.</td>
              <td><span class="status-badge solved">✅ Đã thông báo</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
            <tr data-type="other">
              <td>4</td>
              <td>PHẠM THỊ D</td>
              <td><span class="type-badge other">Khác</span></td>
              <td>Yêu cầu phòng tầng thấp.</td>
              <td><span class="status-badge pending">⚠️ Cần xác minh</span></td>
              <td><button type="button" class="btn filter-btn">Cập nhật</button></td>
            </tr>
          </tbody>
        </table>
      </main>
    </div>
  </div>
    <script>
    // Lọc bảng theo loại
    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        document.querySelectorAll('table tbody tr').forEach(row => {
          row.style.display = (filter === 'all' || row.getAttribute('data-type') === filter) ? '' : 'none';
        });
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

 