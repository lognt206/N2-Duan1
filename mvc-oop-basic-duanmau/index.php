<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
// require_once './controllers/ProductController.php';
require_once './controllers/admincontroller.php';
require_once './controllers/logincontroller.php';
require_once './controllers/TourGuideController.php';
// Require toàn bộ file Models
// require_once './models/ProductModel.php';
require_once './models/GuideModel.php';
// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    // '/'=>(new logincontroller())->home(),
    '/'          => (new logincontroller())->login(),
'dangxuat'          => (new logincontroller())->dangxuat(),
    'login'          => (new logincontroller())->login(),

    //  '/'=>(new admincontroller())->dashboard(),
     'dashboard'=>(new admincontroller())->dashboard(),
    'tour'=>(new admincontroller())->tour(),
     'noidung'=>(new admincontroller())->tour(),
      'guideadmin'=>(new admincontroller())->guideadmin(),
       'booking'=>(new admincontroller())->booking(),
        'customer'=>(new admincontroller())->customer(),
        'partner'=>(new admincontroller())->partner(),
         'category'=>(new admincontroller())->category(),
         
         'create'=>(new admincontroller())->create(),
        'store'=>(new admincontroller())->store(),
         'delete'=>(new admincontroller())->delete(),
         'edit'=>(new admincontroller())->edit(),
         'update'=>(new admincontroller())->update(),


'header'=>(new TourGuideController())-> header(),
    'schedule'=>(new TourGuideController())-> lichlamviec(),
    'profile'=>(new TourGuideController())-> profile(),
    'tour_detail'=>(new TourGuideController())-> tour_detail(),
    'report'=>(new TourGuideController())-> report(),
    'check_in'=>(new TourGuideController())-> check_in(),
    'special_request'=>(new TourGuideController())-> special_request(),


};