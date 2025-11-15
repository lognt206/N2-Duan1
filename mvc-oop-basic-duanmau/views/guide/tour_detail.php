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
    .container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.page-main-title {
    color: #333;
    font-size: 28px;
    margin-bottom: 5px;
}
.tour-status {
    font-size: 16px;
    margin-bottom: 15px;
}
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}
.status-badge.upcoming {
    background-color: #fff3cd; 
    color: #856404;
}
.status-badge.checkin {
    background-color: #d4edda;
    color: #155724;
}
.status-badge.pending {
    background-color: #f8d7da;
    color: #721c24;
}
.quick-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.btn-back, .btn-primary {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 4px;
    font-weight: bold;
    transition: background-color 0.2s;
}
.btn-back {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #ccc;
}
.btn-primary {
    background-color: #0476d3ff;
    color: white;
}
.tabs {
    display: flex;
    margin-bottom: 0;
    border-bottom: 2px solid #ddd;
}
.tab-button {
    background-color: #f4f4f4;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #555;
    border-radius: 6px 6px 0 0;
    margin-right: 5px;
    border: 1px solid transparent;
    border-bottom: none;
}
.tab-button.active {
    background-color: #ffffff;
    color: #0a6bd3ff;
    border-color: #ddd;
    border-bottom: 2px solid #0559c7ff;
    margin-bottom: -2px; 
}
.tab-content-wrapper {
    padding: 20px 0;
}
.tab-pane {
    padding: 10px 0;
}
.tab-pane.hidden {
    display: none;
}
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px 30px;
}
.info-item .label {
    font-weight: bold;
    color: #666;
    display: block;
    margin-bottom: 3px;
    font-size: 14px;
}
.info-item .value {
    color: #333;
    font-size: 16px;
}
.info-item.full-width {
    grid-column: span 2;
}
.guest-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
.guest-table th, .guest-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.guest-table th {
    background-color: #f8f8f8;
    color: #333;
}
.guest-actions {
    margin-top: 20px;
    text-align: right;
}
.itinerary-list {
    margin-top: 15px;
    padding-left: 20px;
}
.itinerary-list li {
    margin-bottom: 10px;
    line-height: 1.5;
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
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-box-arrow-right"></i>Đăng xuất</a></li>
          </ul>
        </div>
      </nav>

      <main class="container tour-detail-page">
        <h2 class="page-main-title">Chi tiết Tour: Lucca Bike Tour - HL25</h2>
        <p class="tour-status">Trạng thái: <span class="status-badge upcoming">Sắp khởi hành</span></p>

        <div class="quick-actions">
            <a href="schedule.php" class="btn-back">Quay lại Lịch làm việc</a>
            <a href="report.php?id=HL25" class="btn-primary">📝 Cập nhật tình trạng tour</a>
        </div>

        <div class="tabs">
            <button class="tab-button active" onclick="showTab('info')">
                ℹ️ Thông tin chung
            </button>
            <button class="tab-button" onclick="showTab('guests')">
                👥 Danh sách khách (40 người)
            </button>
            <button class="tab-button" onclick="showTab('itinerary')">
                🗺️ Lộ trình chi tiết
            </button>
        </div>
        
        <div class="tab-content-wrapper">
            
            <div id="info" class="tab-pane active">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">📅 Ngày Tour:</span>
                        <span class="value">Tuesday, 02 Oct 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="label">⏰ Thời gian:</span>
                        <span class="value">15:00 PM</span>
                    </div>
                    <div class="info-item">
                        <span class="label">⏳ Thời lượng:</span>
                        <span class="value">15 giờ 45 phút</span>
                    </div>
                    <div class="info-item">
                        <span class="label">🗣️ Ngôn ngữ:</span>
                        <span class="value">English, Italian</span>
                    </div>
                    <div class="info-item">
                        <span class="label">🚌 Vận chuyển:</span>
                        <span class="value">Bus (Xe 45 chỗ - Biển số: 51F-123.45)</span>
                    </div>
                    <div class="info-item full-width">
                        <span class="label">🏨 Điểm tập trung:</span>
                        <span class="value">Khách sạn XYZ, 123 Đường ABC.</span>
                    </div>
                </div>
            </div>
            
            <div id="guests" class="tab-pane hidden">
                <h3>Danh sách khách hàng</h3>
                <table class="data-table guest-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên Khách</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>NGUYỄN VĂN A</td>
                            <td>0901 xxx 999</td>
                            <td><span class="status-checkin">Đã Check-in</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>TRẦN THỊ B</td>
                            <td>0902 xxx 888</td>
                            <td><span class="status-pending">Chờ Check-in</span></td>
                        </tr>
                        </tbody>
                </table>
                <div class="guest-actions">
                    <button class="btn-back"><a href="check_in.php">Mở công cụ Điểm danh</a></button>
                </div>
            </div>

            <div id="itinerary" class="tab-pane hidden">
                <h3>Lịch trình từng bước</h3>
                <ol class="itinerary-list">
                    <li>**15:00:** Tập trung tại Khách sạn XYZ và điểm danh.</li>
                    <li>**15:30:** Di chuyển đến Lucca, giới thiệu lịch sử thành phố.</li>
                    <li>**16:30:** Bắt đầu đạp xe quanh Tường thành Lucca (dài 4.2km).</li>
                    <li>**18:00:** Ăn tối tại nhà hàng địa phương.</li>
                    <li>**20:00:** Tham quan Quảng trường Anfiteatro.</li>
                    <li>**06:00 (Hôm sau):** Kết thúc tour và di chuyển về điểm ban đầu.</li>
                </ol>
            </div>

        </div>
    </main>
    </div>
  </div>
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
    </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>