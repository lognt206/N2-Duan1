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
        // Lấy guide ID từ session hoặc mặc định = 201
        $guideId = isset($_SESSION['guide_id']) ? $_SESSION['guide_id'] : 201;

        // Lấy dữ liệu từ model
        $tours = $this->modelTourGuide->getLichLamViec($guideId);

        $today = date('Y-m-d');
        $lich_lam_viec = [];

        foreach ($tours as $tour) {
            $bdau  = $tour['departure_date'];
            $kthuc = $tour['return_date'];

            if ($kthuc < $today) {
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
        // Lấy guide ID từ session
        $guideId = isset($_SESSION['guide_id']) ? $_SESSION['guide_id'] : null;
        
        if (!$guideId) {
            echo "Vui lòng đăng nhập!";
            exit();
        }
        
        // Lấy thông tin HDV
        $guide = $this->modelTourGuide->find($guideId);
        
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
?>