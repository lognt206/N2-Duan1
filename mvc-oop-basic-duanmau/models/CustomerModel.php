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
    public function all(){
        try {
            $sql = "SELECT * FROM `customer`";
            $data = $this->conn->query($sql)->fetchAll();
            $danhsachcustomer = [];
            foreach($data as $value){
                $customer = new Customer();
                $customer->customer_id        = $value['customer_id'];
                $customer->booking_id         = $value['booking_id'];
                $customer->full_name          = $value['full_name'];
                $customer->gender             = $value['gender'];
                $customer->birth_year         = $value['birth_year'];
                $customer->contact            = $value['contact'];
                $customer->payment_status     = $value['payment_status'];
                $customer->special_request    = $value['special_request'];
                $danhsachcustomer[]=$customer;
            }
            return $danhsachcustomer;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function find($id){
        try {
            $sql = "SELECT * FROM `customer` WHERE id = $id";
            $data = $this->conn->query($sql)->fetch();
            if($data !== false){
                $customer = new Customer();
                $customer->customer_id        = $data['customer_id'];
                $customer->booking_id         = $data['booking_id'];
                $customer->full_name          = $data['full_name'];
                $customer->gender             = $data['gender'];
                $customer->birth_year         = $data['birth_year'];
                $customer->contact            = $data['contact'];
                $customer->payment_status     = $data['payment_status'];
                $customer->special_request    = $data['special_request'];
                return $customer;
            }
            
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }
    public function create(Customer $customer){
        try{
            $sql= "INSERT INTO `customer` (`customer_id`, `booking_id`, `full_name`, `gender`, `birth_year`, `contact`, `payment_status`, `special_request`)
            VALUES (NULL, '".$customer->customer_id."',
                        '".$customer->booking_id."',
                        '".$customer->full_name."',
                        '".$customer->gender."', 
                        '".$customer->birth_year."',
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
            $sql = "DELETE FROM custommer WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt ->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
            return false;
        }
    }
    public function update(Customer $customer){
        try{
            $id = (int)$customer->customer_id;
            $sql="UPDATE `customer`
             SET `customer_id` = '".$customer->customer_id."',
                 `booking_id` = '".$customer->booking_id."',
                 `full_name` = '".$customer->full_name."',
                 `gender` = '".$customer->gender."',
                 `birth_year` = '".$customer->birth_year."',
                 `contact` = '".$customer->contact."',
                 `payment_status` = '".$customer->payment_status."',
                 `special_request` = '".$customer->special_request."'
                 WHERE `customer`.`id` = $id;";
                $data=$this->conn->exec($sql);
                return $data;
            }catch (PDOException $err){
                echo "Lỗi : " . $err->getMessage();
            }
    }
}
?>