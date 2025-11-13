<?php
// có class chứa các function thực thi xử lý logic 
class TuorGuideController
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
    public function lichlamviec(){
        require_once './views/guide/schedule.php';
    }
    public function profile(){
        require_once './views/guide/profile.php';
    }
    public function report(){
        require_once './views/guide/report.php';
    }
    public function tour_detail(){
        require_once './views/guide/tour_detail.php';
    }
    public function check_in(){
        require_once './views/guide/check_in.php';
    }
    public function special_request(){
        require_once './views/guide/special_request.php';
    }
    
}