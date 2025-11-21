<?php 
class Customer{
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
class CustomerModel{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }
    public function allcustomer(){
        try {
            $sql = "SELECT * FROM `customer`";
            $data = $this->conn->query($sql)->fetchAll();
            return $data;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function find_customer($id){
        try {
                $sql= "SELECT * FROM `customer` WHERE customer_id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt ->execute(['id'=> $id]);
                $customer = $stmt->fetch();
                return $customer;
            }
        catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }
    public function create_customer(Customer $customer){
        try{
            $sql= "INSERT INTO `customer` ( `booking_id`, `full_name`, `gender`, `birth_year`, `id_number`, `contact`, `payment_status`, `special_request`)
            VALUES ('".$customer->booking_id."',
                        '".$customer->full_name."',
                        '".$customer->gender."', 
                        '".$customer->birth_year."',
                        '".$customer->id_number."',
                        '".$customer->contact."',
                        '".$customer->payment_status."',
                        '".$customer->special_request."' );";
                 $data = $this->conn->exec($sql);
                 return $data;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
        }
    }
    public function delete_customer($id){
        try{
            $sql = "DELETE FROM customer WHERE customer_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id'=>$id]);
            return true;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
            return false;
        }
    }
    public function update_customer(Customer $customer){
        try{
            $id = (int)$customer->customer_id;
            $sql="UPDATE `customer`
            SET  `booking_id`           = '".$customer->booking_id."',
                 `full_name`            = '".$customer->full_name."',
                 `gender`               = '".$customer->gender."',
                 `birth_year`           = '".$customer->birth_year."',
                 `id_number`           = '".$customer->id_number."',
                 `contact`              = '".$customer->contact."',
                 `payment_status`       = '".$customer->payment_status."',
                 `special_request`      = ".($customer->special_request !== null ? "'".$customer->special_request."'" : "NULL")."
                 WHERE `customer`.`customer_id` = $id;";
                $data=$this->conn->exec($sql);
                return $data;
            }catch (PDOException $err){
                echo "Lỗi : " . $err->getMessage();
            }
    }
}
?>