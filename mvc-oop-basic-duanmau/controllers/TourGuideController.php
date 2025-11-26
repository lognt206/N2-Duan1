<?php
require_once __DIR__ . '/../models/ScheduleModel.php';
require_once __DIR__ . '/../models/Guidemodel.php';
class TourGuideController
{
    public $modelTourGuide;
    public $UserModel;
    public $ScheduleModel;

    public function __construct()
    {
        $this->ScheduleModel = new ScheduleModel();
        $this->modelTourGuide = new TourGuideModel();
    }

    public function header()
    {
        $title = "Đây là trang chủ hdv";
        require_once './views/guide/header.php';
    }

    public function lichlamviec()
{
    $guideId = $_SESSION['guide_id'] ?? 3;

    // Gọi phương thức từ model để lấy tour của guide này
    $tours = $this->ScheduleModel->Scheduleguideid($guideId); // cần tạo phương thức trong model

    $today = date('Y-m-d');
    $lich_lam_viec = [];

    foreach ($tours as $tour) {
        // tránh warning nếu cột null hoặc không tồn tại
        $bdau  = $tour['Ngay_Bat_Dau'] ?? null;
        $kthuc = $tour['Ngay_Ket_Thuc'] ?? null;
        $scheduleStatus = $tour['Trang_Thai_Schedule'] ?? null;
        if ($scheduleStatus == 3) {
            $tour['tinh_trang'] = "Đã hủy";
            $tour['trangthai'] = "cancelled"; 
        }
        elseif (!$bdau || !$kthuc) {
            $tour['tinh_trang'] = "Chưa có dữ liệu";
            $tour['trangthai'] = "unknown";
        } elseif ($kthuc < $today) {
            $tour['tinh_trang'] = "Đã hoàn thành";
            $tour['trangthai'] = "completed";
        } elseif ($bdau <= $today && $kthuc >= $today) {
            $tour['tinh_trang'] = "Đang thực hiện";
            $tour['trangthai'] = "in_progress";
        } else {
            $tour['tinh_trang'] = "Sắp khởi hành";
            $tour['trangthai'] = "upcoming";
        }

        $lich_lam_viec[] = $tour;
    }
    require_once './views/guide/lich_lam_viec/schedule.php';
}



    public function profile()
    {   //ktra đăng nhập, k có user đó thì về login
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location:?act=login");
            exit;
        }
        //lấy user_id đã lưu khi login vào
        $user_id = $_SESSION['user']['user_id'];
        $tourguideModel = new TourGuideModel();
        $tourguide = $this->modelTourGuide->find_guide($user_id);
        require_once './views/guide/thong_tin_ca_nhan/profile.php';
    }

    public function report()
    {   
        require_once './views/guide/nhat_ky/report.php';
    }

    public function tour_detail()
    {
        require_once './views/guide/lich_lam_viec/tour_detail.php';
    }

    public function check_in()
    {
        require_once './views/guide/lich_lam_viec/check_in.php';
    }

    public function special_request()
    {
        require_once './views/guide/yeu_cau_dbiet/special_request.php';
    }
}
