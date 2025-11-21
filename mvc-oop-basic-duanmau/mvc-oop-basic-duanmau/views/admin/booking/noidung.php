<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đặt Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        #sidebar {
            min-width: 250px;
            background: #343a40;
            color: #fff;
        }

        #sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        #sidebar a:hover {
            background: #495057;
        }

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .topbar .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        footer {
            width: 100%;
            background: #fff;
            text-align: center;
            padding: 10px 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            bottom: 0;
        }

        /* Table style */
        .table th {
            background: #343a40;
            color: white;
        }

        .btn-sm {
            padding: 4px 8px;
        }

        .badge {
            font-size: 12px;
        }

        .badge.Pending {
            background: #ffc107;
            color: #212529;
        }

        .badge.Deposited {
            background: #0dcaf0;
        }

        .badge.Completed {
            background: #198754;
        }

        .badge.Cancelled {
            background: #dc3545;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <h3 class="text-center py-3 border-bottom">Admin Panel</h3>
        <a href="?act=dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="?act=tour"><i class="fa-solid fa-plane"></i> Quản lý Tour</a>
        <a href="?act=category"><i class="fa-solid fa-plane"></i> Quản lý danh mục Tour</a>
        <a href="?act=customer"><i class="fa-solid fa-users"></i> Quản lý Khách hàng</a>
        <a href="?act=booking" class="bg-secondary"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</a>
        <a href="?act=guideadmin"><i class="fa-solid fa-user-tie"></i> Quản lý Hướng dẫn viên</a>
        <a href="?act=partner"><i class="fa-solid fa-handshake"></i> Quản lý Đối tác</a>
        <a href="?act=departures"><i class="fa-solid fa-calendar"></i> Lịch khởi hành</a>
        <a href="?act=baocao"><i class="fa-solid fa-coins"></i> Báo cáo tài chính</a>
        <a href="?act=logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
    </div>

    <!-- Content -->
    <div id="content">
        <div class="topbar">
            <div class="logo d-flex align-items-center">
                <i class="fa-solid fa-plane-departure me-2"></i>
                <span class="fw-bold">Admin Panel</span>
            </div>
            <div class="user d-flex align-items-center">
                <img src="https://via.placeholder.com/40" alt="User">
                <span>Admin</span>
                <a href="?act=logout" class="btn btn-sm btn-outline-danger ms-3">Đăng xuất</a>
            </div>
        </div>

        <h3 class="mb-3"><i class="fa-solid fa-ticket"></i> Quản lý Đặt Tour</h3>

        <div class="d-flex justify-content-between mb-3">
            <a href="#" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm Đặt Tour</a>
            <form class="d-flex" style="max-width:300px;">
                <input type="text" class="form-control me-2" placeholder="Tìm kiếm...">
                <button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <div class="table-responsive bg-white p-3 rounded shadow-sm">
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tour ID</th>
                        <th>User ID</th>
                        <th>Ngày Đặt</th>
                        <th>Số Người</th>
                        <th>Loại Đặt</th>
                        <th>Trạng Thái</th>
                        <th>Ghi Chú</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)) : ?>
                        <?php foreach ($bookings as $b) : ?>
                            <tr>
                                <td><?= $b->booking_id ?></td>
                                <td><?= $b->tour_id ?></td>
                                <td><?= $b->customer_id ?></td>
                                <td><?= $b->booking_date ?></td>
                                <td><?= $b->num_people ?></td>
                                <td><?= $b->booking_type ?></td>
                                <td><span class="badge <?= $b->status ?>"><?= $b->status ?></span></td>
                                <td><?= $b->notes ?></td>
                                <td>
                                    <a href="?act=booking_update&id=<?= $b->booking_id ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                                    <a href="?act=booking_delete&id=<?= $b->booking_id ?>" onclick="return confirm('Xóa đặt tour này?')" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Không có dữ liệu đặt tour.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        &copy; 2025 Công ty Du lịch. All rights reserved.
    </footer>

</body>

</html>