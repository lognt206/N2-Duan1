<?php
require_once __DIR__ . "/../models/CustomerModel.php";

class CustomerController {
    public $CustomerModel;

    public function __construct() {
        $this->CustomerModel = new CustomerModel();
    }

    public function index() {
    $customers = $this->CustomerModel->all(); 
    include "views/admin/customer/noidung.php";
}


    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer = new Customer();
            $customer->booking_id      = $_POST['booking_id'];
            $customer->full_name       = $_POST['full_name'];
            $customer->gender          = $_POST['gender'];
            $customer->birth_year      = $_POST['birth_year'];
            $customer->id_number       = $_POST['id_number'];
            $customer->contact         = $_POST['contact'];
            $customer->payment_status  = $_POST['payment_status'];
            $customer->special_request = $_POST['special_request'];

            $this->CustomerModel->create($customer);
            header("Location: ?act=customer");
            exit;
        }
        include "views/admin/customer/create.php";
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "Không tìm thấy customer!"; exit; }

        $customer = $this->CustomerModel->find($id);
        if (!$customer) { echo "Customer không tồn tại!"; exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customer->booking_id      = $_POST['booking_id'];
            $customer->full_name       = $_POST['full_name'];
            $customer->gender          = $_POST['gender'];
            $customer->birth_year      = $_POST['birth_year'];
            $customer->id_number       = $_POST['id_number'];
            $customer->contact         = $_POST['contact'];
            $customer->payment_status  = $_POST['payment_status'];
            $customer->special_request = $_POST['special_request'];

            $this->CustomerModel->update($customer);
            header("Location: ?act=customer");
            exit;
        }

        include "views/admin/customer/update.php";
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->CustomerModel->delete($id);
        }
        header("Location: ?act=customer");
        exit;
    }
}    
?>
 