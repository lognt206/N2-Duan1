<?php
class TourGuideController
{
    public $modelTourGuide;

    public function __construct()
    {
        $this->modelTourGuide = new TourGuideModel();
    }

    public function header()
    {
        $title = "Đây là trang chủ hdv";
        require_once './views/guide/header.php';
    }

    public function lichlamviec()
{
    $guideId = $_SESSION['guide_id'] ?? 201;

    // Gọi phương thức từ model để lấy tour của guide này
    $tours = $this->modelTourGuide->allguide($guideId); // cần tạo phương thức trong model

    $today = date('Y-m-d');
    $lich_lam_viec = [];

    foreach ($tours as $tour) {
        // tránh warning nếu cột null hoặc không tồn tại
        $bdau  = $tour['departure_date'] ?? null;
        $kthuc = $tour['return_date'] ?? null;

        if (!$bdau || !$kthuc) {
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

    require_once './views/guide/schedule.php';
}



    public function profile()
    {
        require_once './views/guide/profile.php';
    }

    public function report()
    {
        require_once './views/guide/report.php';
    }

    public function tour_detail()
    {
        require_once './views/guide/tour_detail.php';
    }

    public function check_in()
    {
        require_once './views/guide/check_in.php';
    }

    public function special_request()
    {
        require_once './views/guide/special_request.php';
    }
}
