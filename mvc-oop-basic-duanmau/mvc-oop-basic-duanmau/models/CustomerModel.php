<?php
require_once './commons/env.php';

class Customer {
    public $customer_id;
    public $booking_id;
    public $full_name;
    public $gender;
    public $birth_year;
    public $id_number;
    public $contact;
    public $payment_status;
    public $special_request;
}

class CustomerModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function all() {
        try {
            $sql = "SELECT * FROM customer ORDER BY customer_id DESC";
            $data = $this->conn->query($sql)->fetchAll();
            $list = [];
            foreach ($data as $row) {
                $c = new Customer();
                $c->customer_id = $row['customer_id'];
                $c->booking_id = $row['booking_id'];
                $c->full_name = $row['full_name'];
                $c->gender = $row['gender'];
                $c->birth_year = $row['birth_year'];
                $c->id_number = $row['id_number'];
                $c->contact = $row['contact'];
                $c->payment_status = $row['payment_status'];
                $c->special_request = $row['special_request'];
                $list[] = $c;
            }
            return $list;
        } catch (PDOException $err) {
            echo "Lỗi truy vấn Customer: " . $err->getMessage();
        }
    }

    public function find($id) {
        try {
            $sql = "SELECT * FROM customer WHERE customer_id = $id";
            $row = $this->conn->query($sql)->fetch();
            if ($row !== false) {
                $c = new Customer();
                $c->customer_id = $row['customer_id'];
                $c->booking_id = $row['booking_id'];
                $c->full_name = $row['full_name'];
                $c->gender = $row['gender'];
                $c->birth_year = $row['birth_year'];
                $c->id_number = $row['id_number'];
                $c->contact = $row['contact'];
                $c->payment_status = $row['payment_status'];
                $c->special_request = $row['special_request'];
                return $c;
            }
        } catch (PDOException $err) {
            echo "Lỗi truy vấn Customer: " . $err->getMessage();
        }
    }

    public function create(Customer $c) {
        try {
            $sql = "INSERT INTO customer (booking_id, full_name, gender, birth_year, id_number, contact, payment_status, special_request)
                    VALUES ('".$c->booking_id."', '".$c->full_name."', '".$c->gender."', '".$c->birth_year."', '".$c->id_number."', '".$c->contact."', '".$c->payment_status."', '".$c->special_request."')";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi thêm Customer: " . $err->getMessage();
        }
    }

    public function update(Customer $c) {
        try {
            $sql = "UPDATE customer SET
                    booking_id='".$c->booking_id."',
                    full_name='".$c->full_name."',
                    gender='".$c->gender."',
                    birth_year='".$c->birth_year."',
                    id_number='".$c->id_number."',
                    contact='".$c->contact."',
                    payment_status='".$c->payment_status."',
                    special_request='".$c->special_request."'
                    WHERE customer_id='".$c->customer_id."'";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi cập nhật Customer: " . $err->getMessage();
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM customer WHERE customer_id = $id";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi xóa Customer: " . $err->getMessage();
        }
    }
}
?>
