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
    .profile-header {
      background: linear-gradient(135deg, #092f68ff, #1e90ff);
      color: white;
      padding: 40px 20px;
      border-radius: 12px;
      text-align: center;
      margin-bottom: 30px;
    }
    .profile-header img {
      width: 140px;
      height: 140px;
      object-fit: cover;
      border-radius: 50%;
      border: 4px solid #fff;
      margin-bottom: 15px;
    }
    .profile-header h2 {
      font-weight: 600;
      margin-bottom: 5px;
    }
    .info-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    .info-card h5 {
      color: #092f68ff;
      font-weight: 600;
    }
    .info-item {
      margin-bottom: 10px;
      font-size: 16px;
    }
    .info-item i {
      width: 25px;
      color: #d59d00ff;
    }
    .edit-btn {
      background-color: #d59d00ff;
      color: white;
      border-radius: 25px;
      padding: 10px 20px;
      border: none;
      font-weight: 500;
      transition: 0.3s;
    }
    .edit-btn:hover {
      background-color: #b98600;
      transform: scale(1.05);
    }
    .info-card.text-center p strong {
      color: #092f68ff;
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

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        <section class="profile-header">
          <img src="https://i.pravatar.cc/150?img=12" alt="Avatar Hướng dẫn viên">
          <h2>Nguyễn Minh Tuấn</h2>
          <p>Hướng dẫn viên du lịch chuyên nghiệp 🌍</p>
        </section>

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="info-card">
              <h5><i class="fa-solid fa-id-card"></i> Thông tin cá nhân</h5>
              <hr>
              <div class="info-item"> Họ tên: Nguyễn Minh Tuấn</div>
              <div class="info-item"> Tuổi: 29</div>
              <div class="info-item"> SĐT: 0987 654 321</div>
              <div class="info-item"> Email: tuannguyen@example.com</div>
              <div class="info-item"> Ngôn ngữ: Tiếng Việt, Tiếng Anh</div>
              <div class="info-item"> Kinh nghiệm: 6 năm</div>
              <div class="info-item"></i> Khu vực: Miền Trung - Tây Nguyên</div>
              <hr>
              <h5> Giới thiệu</h5>
              <p>
                Tôi là hướng dẫn viên du lịch với hơn 6 năm kinh nghiệm dẫn tour trong nước và quốc tế.
                Luôn nhiệt huyết, thân thiện và sẵn sàng chia sẻ kiến thức văn hoá địa phương đến du khách.
              </p>
              <button class="edit-btn"> Chỉnh sửa hồ sơ</button>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="info-card text-center">
              <h5><i class="fa-solid fa-star"></i> Thông tin nhanh</h5>
              <hr>
              <p><strong>Tour đã dẫn:</strong> 128</p>
              <p><strong>Khách đánh giá trung bình:</strong> ⭐ 4.8 / 5</p>
              <p><strong>Chuyên tuyến:</strong> Đà Nẵng - Hội An - Huế</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>