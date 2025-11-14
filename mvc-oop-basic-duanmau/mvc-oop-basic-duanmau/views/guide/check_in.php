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
    body {
      font-family: Arial, sans-serif;
      background-color: #f8fbff;
      margin: 0;
      padding: 20px;
    }
    .checkin-page {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #007bff;
      text-align: center;
    }
    .tour-info {
      text-align: center;
      color: #555;
      margin-bottom: 20px;
    }
    .summary-box {
      display: flex;
      justify-content: space-around;
      padding: 10px;
      background-color: #e6f7ff;
      border: 1px solid #91d5ff;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .summary-box strong {
      color: #007bff;
    }
    .action-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 15px;
      gap: 10px;
    }
    button {
      padding: 8px 14px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: white;
    }
    .btn-primary {
      background-color: #007bff;
    }
    .btn-secondary {
      background-color: #6c757d;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #dee2e6;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #f1f5ff;
    }
    td.text-center, th.text-center {
      text-align: center;
    }
    .checkin-toggle {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .toggle-label {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 25px;
      background-color: #ccc;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .toggle-label::after {
      content: '';
      position: absolute;
      width: 21px;
      height: 21px;
      border-radius: 50%;
      background-color: white;
      top: 2px;
      left: 2px;
      transition: transform 0.3s;
    }
    .checkin-toggle:checked + .toggle-label {
      background-color: #28a745;
    }
    .checkin-toggle:checked + .toggle-label::after {
      transform: translateX(25px);
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <nav class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="bi bi-person-circle"></i>Thông tin cá nhân</a></li>
            <li class="nav-item"><a class="nav-link" href="schedule.php"><i class="bi bi-calendar-week"></i>Lịch làm việc</a></li>
            <li class="nav-item"><a class="nav-link" href="report.php"><i class="bi bi-journal-text"></i>Nhật ký tour</a></li>
            <li class="nav-item"><a class="nav-link" href="special_request.php"><i class="bi bi-star"></i>Yêu cầu đặc biệt</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-box-arrow-right"></i>Đăng xuất</a></li>
          </ul>
        </div>
      </nav>

      <main class="checkin-page">
    <h2>✅ Điểm danh khách hàng tour</h2>
    <p class="tour-info">Tour: <strong>Lucca Bike Tour - HL25</strong> | Ngày: 02/10/2025</p>

    <div class="summary-box">
      <span>Tổng khách: <strong>40</strong></span>
      <span>Đã check-in: <strong id="checked-in-count">30</strong></span>
      <span>Còn lại: <strong id="remaining-count">10</strong></span>
    </div>

    <div class="action-bar">
      <button class="btn-secondary" id="filter-btn">Lọc: Chưa check-in</button>
      <button class="btn-primary">💾 Lưu</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên khách</th>
          <th>Mã booking</th>
          <th>SĐT</th>
          <th class="text-center">Check-in</th>
          <th>Ghi chú</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Nguyễn Văn A</td>
          <td>BOOKING-12345</td>
          <td>0901 xxx 999</td>
          <td class="text-center">
            <input type="checkbox" class="checkin-toggle" id="guest1" checked>
            <label for="guest1" class="toggle-label"></label>
          </td>
          <td>Đã thanh toán</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Trần Thị B</td>
          <td>BOOKING-56789</td>
          <td>0902 xxx 888</td>
          <td class="text-center">
            <input type="checkbox" class="checkin-toggle" id="guest2">
            <label for="guest2" class="toggle-label"></label>
          </td>
          <td>Chưa đến</td>
        </tr>
      </tbody>
    </table>
  </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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