<?php
class Tour {
public $tour_id;
public $tour_name;
public $category_id ;
public $description;
public $price;
public $policy;
public $supplier;
public $status;

}
class TourModel {
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function all(){
        try{
            $sql = "SELECT t.*, tc.category_name FROM `tour` t
            LEFT JOIN `tourcategory` tc ON t.category_id = tc.category_id";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
            return [];
        }
    }

        public function find($id){
            try{
                $sql="SELECT * FROM `tour` WHERE tour_id = $id";
                $data=$this->conn->query($sql)->fetch();
                if($data !== false){
                   $tour = new Tour();
                    $tour->tour_id           =$data['tour_id'];
                    $tour->tour_name         = $data['tour_name'];
                    $tour->category_id       = $data['category_id'];
                    $tour->description       = $data['description'];
                    $tour->price             = $data['price'];
                    $tour->policy            = $data['policy'];
                    $tour->supplier          = $data['supplier'];
                    $tour->status            = $data['status'];
                    return $tour;
                }
            }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
        }
public function create(Tour $tour){        
            try{
                $sql="INSERT INTO `tour`(`tour_name`, `category_id`, `description`, `price`, `policy`, `supplier`, `status`)
                 VALUES(:tour_name, :category_id, :description, :price, :policy, :supplier, :status)";
                  
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ":tour_name"    => $tour->tour_name,
                    ":category_id"    => $tour->category_id,
                    ":description"    => $tour->description,
                    ":price"    => $tour->price,
                    ":policy"    => $tour->policy,
                    ":supplier"    => $tour->supplier,
                    ":status"    => $tour->status
                ]);
                return $stmt->rowCount();
            }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
        }
public function update(Tour $tour){                 
            try{
                $id=(int)$tour->tour_id;
                $sql="UPDATE `tour` SET `tour_name` = '".$tour->tour_name."', `category_id` = '".$tour->category_id."',`description` = '".$tour->description."',`price` = '".$tour->price."',`policy` = '".$tour->policy."',`supplier` = '".$tour->supplier."',`status` = '".$tour->status."' WHERE `tour`.`tour_id` = $id;";
                $data=$this->conn->exec($sql);
                return $data;

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }
public function delete($id){
    $sql = "DELETE FROM tour WHERE tour_id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->rowCount();
}
}
?>