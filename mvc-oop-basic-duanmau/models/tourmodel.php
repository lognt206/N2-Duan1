<?php
class Tour {
public $tour_id;
public $tour_name;
public $tour_type;
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
                $sql="SELECT * FROM `tour`";
                $data=$this->conn->query($sql)->fetchAll();
                $danhsachtour=[];
                foreach($data as $value){
                    $tour = new Tour();
                    $tour->tour_id        =$value['tour_id'];
                    $tour->tour_name        = $value['tour_name'];
                    $tour->tour_type       = $value['tour_type'];
                    $tour->description       = $value['description'];
                    $tour->price = $value['price'];
                   $tour->policy = $value['policy'];
                    $tour->supplier         = $value['supplier'];
                    $tour->status    = $value['status'];
                   $danhsachtour[]=$tour;
                }
                return $danhsachtour;

            }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
        }


        public function find($id){
            try{
                $sql="SELECT * FROM `tour` WHERE id = $id";
                $data=$this->conn->query($sql)->fetchAll();
                if($data !== false){
                   $tour = new Tour();
                    $tour->tour_id        =$value['tour_id'];
                    $tour->tour_name        = $value['tour_name'];
                    $tour->tour_type       = $value['tour_type'];
                    $tour->description       = $value['description'];
                    $tour->price         = $value['price'];
                   $tour->policy        = $value['policy'];
                    $tour->supplier       = $value['supplier'];
                    $tour->status       = $value['status'];
                    return $tour;
                }
            }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
        }
public function create(Tour $tour){        
            try{
                $sql="INSERT INTO `tour`(`tour_id`, `tour_name`, `category_id`, `description`, `price`, `policy`, `supplier`, `status`) VALUES
                  (NULL,'".$tour->tour_name."','".$tour->category_id."','".$tour->description."','".$tour->price."','".$tour->policy."','".$tour->supplier."','".$tour->status."');";
                 
                $data=$this->conn->exec($sql);

                return $data;
            }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
        }
public function update(Tour $tour){                 
            try{
                $id=(int)$tour->id;
                $sql="UPDATE `tour` SET  `tour_name` = '".$tour->tour_name."',`tour_type` = '".$tour->tour_type."', `description` = '".$tour->description."', `price` = '".$tour->price."', `descripsion` = '".$tour->descripsion."', `hot` = '".$tour->hot."', `discount` = '".$tour->discount."', `quantity` = '".$tour->quantity."' WHERE `product`.`id` = $id;";
                $data=$this->conn->exec($sql);
                return $data;

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }







}
?>