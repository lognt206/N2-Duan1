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
$id = $_GET['id'] ?? null;

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
      
       'booking'=>(new admincontroller())->booking(),
        'customer'=>(new admincontroller())->customer(),
        'partner'=>(new admincontroller())->partner(),

        'accoun'=>(new admincontroller())->accoun(),
        'account_toggle'=>(new admincontroller())->account_toggle($id),
                'account_delete'=>(new admincontroller())->account_delete($id),

         'category'=>(new admincontroller())->category(),
          'category/create'=>(new admincontroller())->category_create(),
           'category/update'=>(new admincontroller())->category_update($id),
               'delete_danhmuc'    => (new admincontroller())->delete_danhmuc($id),

         
         'create'=>(new admincontroller())->create(),
        'store'=>(new admincontroller())->store(),
         'delete'=>(new admincontroller())->delete(),
         'update'=>(new admincontroller())->update(),
//hdv
    'guideadmin' => (new admincontroller())->guideadmin(),
    'create_guide' => (new admincontroller())->create_guide(),
    'delete_guide' => (new admincontroller())->delete_guide(),
    'update_guide' => (new admincontroller())->update_guide(),


'header'=>(new TourGuideController())-> header(),
    'schedule'=>(new TourGuideController())-> lichlamviec(),
    'profile'=>(new TourGuideController())-> profile(),
    'tour_detail'=>(new TourGuideController())-> tour_detail(),
    'report'=>(new TourGuideController())-> report(),
    'check_in'=>(new TourGuideController())-> check_in(),
    'special_request'=>(new TourGuideController())-> special_request(),


};