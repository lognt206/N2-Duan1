<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/ProductController.php';
require_once './controllers/TourGuideController.php';
// Require toàn bộ file Models
require_once './models/ProductModel.php';
require_once './models/TourGuideModel.php';
// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/'=>(new ProductController())->Home(),
    'header'=>(new TuorGuideController())-> header(),
    'schedule'=>(new TuorGuideController())-> lichlamviec(),
    'profile'=>(new TuorGuideController())-> profile(),
    'tour_detail'=>(new TuorGuideController())-> tour_detail(),
    'report'=>(new TuorGuideController())-> report(),
    'check_in'=>(new TuorGuideController())-> check_in(),
    'special_request'=>(new TuorGuideController())-> special_request(),
};