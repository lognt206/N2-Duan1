<?php
class admincontroller {
public function dashboard() {
        include "views/admin/dashboard.php";
    }

public function tour() {
        include "views/admin/tour/noidung.php";
    }
public function guide() {
        include "views/admin/guide/noidung.php";
    }
}

?>