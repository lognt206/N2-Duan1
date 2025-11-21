<?php
require_once './commons/env.php'; // Khai báo biến môi trường 
require_once './commons/function.php'; // Hàm hỗ trợ
require_once './controllers/LoginController.php';
require_once './controllers/AdminController.php';
require_once './controllers/TourGuideController.php';
require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';
require_once './models/GuideModel.php'; 
require_once './models/BookingModel.php'; 
require_once './models/CustomerModel.php';
$act = $_GET['act'] ?? '/';
$id  = $_GET['id'] ?? null;

match ($act) {
    // Trang chủ / login
    '/' => (new logincontroller())->login(),
    'login' => (new logincontroller())->login(),
    'dangxuat' => (new logincontroller())->dangxuat(),

    // Admin
    'dashboard' => (new admincontroller())->dashboard(),
    'tour'      => (new admincontroller())->tour(),
    'noidung'   => (new admincontroller())->tour(),
    'partner'   => (new admincontroller())->partner(),
    'accoun'    => (new admincontroller())->accoun(),
    'account_toggle' => (new admincontroller())->account_toggle($id),
    'account_delete' => (new admincontroller())->account_delete($id),

    // Category
    'category'        => (new admincontroller())->category(),
    'category/create' => (new admincontroller())->category_create(),
    'category/update' => (new admincontroller())->category_update($id),
    'delete_danhmuc'  => (new admincontroller())->delete_danhmuc($id),

    // Tour
    'create' => (new admincontroller())->create(),
    'store'  => (new admincontroller())->store(),
    'delete' => (new admincontroller())->delete(),
    'update' => (new admincontroller())->update(),

    // HDV
    'guideadmin'   => (new admincontroller())->guideadmin(),
    'create_guide' => (new admincontroller())->create_guide(),
    'delete_guide' => (new admincontroller())->delete_guide(),
    'update_guide' => (new admincontroller())->update_guide(),

    // TourGuideController
    'header'          => (new TourGuideController())->header(),
    'schedule'        => (new TourGuideController())->lichlamviec(),
    'profile'         => (new TourGuideController())->profile(),
    'tour_detail'     => (new TourGuideController())->tour_detail(),
    'report'          => (new TourGuideController())->report(),
    'check_in'        => (new TourGuideController())->check_in(),
    'special_request' => (new TourGuideController())->special_request(),

    // Booking (dùng BookingController riêng)
    'booking'        => (new BookingController())->index(),
    'booking_create' => (new BookingController())->create(),
    'booking_update' => (new BookingController())->update(),
    'booking_delete' => (new BookingController())->delete($id),

    // Customer (dùng CustomerController riêng)
    'customer'        => (new CustomerController())->index(),
    'customer_create' => (new CustomerController())->create(),
    'customer_update' => (new CustomerController())->update(),  
    'customer_delete' => (new CustomerController())->delete($id),
};
  