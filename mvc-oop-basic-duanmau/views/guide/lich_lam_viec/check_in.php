<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nameUser = $_SESSION['user']['name'] ?? '';
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
        .bg-primary { background-color: #000000ff !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        footer { width:100%; background:#fff; position:fixed; bottom:0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
        #sidebar a i { color: #fff !important; }
        .checkin-page { max-width: 900px; margin: auto; background: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);}
        h2 { color: #000000ff; text-align: center;}
        .tour-info { text-align: center; color: #555; margin-bottom: 20px;}
        .summary-box { display: flex; justify-content: space-around; padding: 10px; background-color: #ffffffff; border: 1px solid #131a1eff; border-radius: 5px; margin-bottom: 20px;}
        .summary-box strong { color: #000000ff;}
        .action-bar { display: flex; justify-content: flex-end; margin-bottom: 15px; gap: 10px;}
        button { padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; color: white;}
        .btn-primary { background-color: #000000ff;}
        .btn-secondary { background-color: #6c757d;}
        table { width: 100%; border-collapse: collapse;}
        th, td { border: 1px solid #dee2e6; padding: 10px; text-align: left;}
        th { background-color: #f1f5ff;}
        td.text-center, th.text-center { text-align: center;}
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
    <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule" class="active"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
    <a href="?act=report"><i class="fa-solid fa-clipboard-list"></i> Nhật ký tour</a>
    <a href="?act=special_request"><i class="fa-solid fa-star"></i> Yêu cầu đặc biệt</a>
    <a href="?act=login"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="logo">
            <a href="?act=dashboard" class="text-decoration-none text-dark">
                <i class="fa-solid fa-plane-departure"></i> Admin Panel
            </a>
        </div>
        <div class="user">
            <img src="uploads/logo.png" alt="User">
            <span><?= $nameUser = $_SESSION['user']['username'] ?? '';?></span>
            <a href="?act=login" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="container-fluid">
        <h2> Điểm danh khách hàng tour</h2>
    <p class="tour-info">Tour: <strong>Lucca Bike Tour - HL25</strong> | Ngày: 02/10/2025</p>

    <div class="summary-box">
      <span>Tổng khách: <strong>40</strong></span>
      <span>Đã check-in: <strong id="checked-in-count">30</strong></span>
      <span>Còn lại: <strong id="remaining-count">10</strong></span>
    </div>

    <div class="action-bar">
      <button class="btn-secondary" id="filter-btn">Lọc: Chưa check-in</button>
      <button class="btn-primary"> Lưu</button>
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
        
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

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