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
        .bg-primary { background-color: #0d6efd !important; }
        .bg-success { background-color: #198754 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #212529 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        footer { width:100%; background:#fff; position:fixed; bottom:0; box-shadow:0 -2px 4px rgba(0,0,0,0.1); }
        #sidebar a i { color: #fff !important; }
        .profile-header {background: linear-gradient(135deg, #000000ff, #125fabff);color: white;padding: 40px 20px;border-radius: 12px;text-align: center;margin-bottom: 30px;}
        .profile-header img {width: 140px;height: 140px;object-fit: cover;border-radius: 50%;border: 4px solid #fff;margin-bottom: 15px;}
        .profile-header h2 {font-weight: 600;margin-bottom: 5px;}
        .info-card {background: white;border-radius: 15px;padding: 25px;box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);}
        .info-card h5 {color: #000000ff;font-weight: 600;}
        .info-item {margin-bottom: 10px;font-size: 16px;}
        .info-item i {width: 25px;color: #d59d00ff;}
        .edit-btn {background-color: #d59d00ff;color: white;border-radius: 25px;padding: 10px 20px;border: none;font-weight: 500;transition: 0.3s;}
        .edit-btn:hover {background-color: #b98600;transform: scale(1.05);}
        .info-card.text-center p strong { color: #092f68ff;}
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
    <a href="?act=header"><i class="fa-solid fa-chart-line"></i>Dashboard</a>
    <a href="?act=profile" class="active"><i class="fa-solid fa-user"></i> Thông tin cá nhân</a>
    <a href="?act=schedule"><i class="fa-solid fa-calendar-day"></i> Lịch làm việc</a>
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
        <section class="profile-header">
          <img src="https://i.pravatar.cc/150?img=12" alt="Avatar Hướng dẫn viên">
          <h2>Nguyễn Minh Tuấn</h2>
          <p>Hướng dẫn viên du lịch chuyên nghiệp</p>
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
              <div class="info-item"> Chứng chỉ: HDV quốc tế</div>
              <div class="info-item"> Kinh nghiệm: 6 năm</div>
              <div class="info-item"></i> Khu vực: Miền Trung - Tây Nguyên</div>
              <hr>
              <h5> Giới thiệu</h5>
              <p>
                Tôi là hướng dẫn viên du lịch với hơn 6 năm kinh nghiệm dẫn tour trong nước và quốc tế.
                Luôn nhiệt huyết, thân thiện và sẵn sàng chia sẻ kiến thức văn hoá địa phương đến du khách.
              </p>
              <button class="edit-btn"><i class="fa-solid fa-pen"></i> Chỉnh sửa hồ sơ</button>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="info-card text-center">
              <h5><i class="fa-solid fa-star"></i> Thông tin nhanh</h5>
              <hr>
              <p><strong>Tour đã dẫn:</strong> 128</p>
              <p><strong>Khách đánh giá trung bình:</strong><i class="fa-solid fa-star" style="color:#EB8317"></i>4.8 / 5</p>
              <p><strong>Chuyên tuyến:</strong> Đà Nẵng - Hội An - Huế</p>
            </div>
          </div>
        </div>
    </div>
</div>

<footer class="text-center py-3">
    &copy; 2025 Công ty Du lịch. All rights reserved.
</footer>

</body>
</html>