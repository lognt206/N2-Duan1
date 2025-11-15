<?php
class admincontroller {
    public $modelTour;
    public function __construct(){
        $this->modelTour = new TourModel();
    }
public function dashboard() {
        include "views/admin/dashboard.php";
    }

public function tour() {
    $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
    }
public function create(){
    include "views/admin/tour/create.php";
}
public function store(){
    $tour = new Tour();
    $tour->tour_name = $_POST['tour_name'];
    $tour->category_id = $_POST['tour_category'];
    $tour->description  = $_POST['description'];
    $tour->price        = $_POST['price'];
    $tour->policy       = $_POST['policy'];
    $tour->supplier     = $_POST['supplier'];
    $tour->status       = $_POST['status'];
    $this->modelTour->create($tour);
    $tours = $this->modelTour->all();
    include "views/admin/tour/noidung.php";
    exit();
}
public function delete(){
    if(isset($_GET['id'])){
        $id = (int)$_GET['id'];
        $this->modelTour->delete($id);
    }
    $tours = $this->modelTour->all();
    include "views/admin/tour/noidung.php";
    exit();
}
public function edit(){
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $tour = $this->modelTour->find($id);
    }
    include "views/admin/tour/update.php";
}
public function update(){
    if(isset($_GET['id'])){
        $id = (int)$_GET['id'];
        $tour = new Tour();
        $tour->tour_id      = $id;
        $tour->tour_name = $_POST['tour_name'];
        $tour->category_id = $_POST['tour_category'];
        $tour->description  = $_POST['description'];
        $tour->price        = $_POST['price'];
        $tour->policy       = $_POST['policy'];
        $tour->supplier     = $_POST['supplier'];
        $tour->status       = $_POST['status'];
        $this->modelTour->update($tour);
        $tours = $this->modelTour->all();
        include "views/admin/tour/noidung.php";
        exit();
    }
}
public function guideadmin() {
        include "views/admin/guideadmin/noidung.php";
    }
public function booking() {
        include "views/admin/booking/noidung.php";
    }
public function customer() {
        include "views/admin/customer/noidung.php";
    }
public function partner() {
        include "views/admin/partner/noidung.php";
    }
    public function category() {
        include "views/admin/category/noidung.php";
    }
}


?>