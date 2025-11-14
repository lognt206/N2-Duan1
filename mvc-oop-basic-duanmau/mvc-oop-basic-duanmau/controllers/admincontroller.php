<?php
class admincontroller {
public function dashboard() {
        include "views/admin/dashboard.php";
    }

public function tour() {
        include "views/admin/tour/noidung.php";
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