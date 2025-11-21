<?php
class Tour {
    public $tour_id;
    public $tour_name;
    public $category_id;
    public $description;
    public $price;
    public $policy;
    public $supplier; // lưu partner_id
    public $status;
    public $category_name; // thêm để hiển thị tên danh mục
    public $supplier_name; // thêm để hiển thị tên nhà cung cấp
}

class TourModel {
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour kèm tên danh mục và nhà cung cấp
    public function all(){
        try{
            $sql = "SELECT t.*, 
                           tc.category_name, 
                           p.partner_name AS supplier_name 
                    FROM `tour` t
                    LEFT JOIN `tourcategory` tc ON t.category_id = tc.category_id
                    LEFT JOIN `partner` p ON t.supplier = p.partner_id
                    ORDER BY t.tour_id DESC";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
            return [];
        }
    }

    // Lấy 1 tour theo id
    public function find($id){
        try{
            $sql="SELECT t.*, 
                         tc.category_name, 
                         p.partner_name AS supplier_name
                  FROM `tour` t
                  LEFT JOIN `tourcategory` tc ON t.category_id = tc.category_id
                  LEFT JOIN `partner` p ON t.supplier = p.partner_id
                  WHERE t.tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id'=>$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if($data !== false){
                $tour = new Tour();
                $tour->tour_id       = $data['tour_id'];
                $tour->tour_name     = $data['tour_name'];
                $tour->category_id   = $data['category_id'];
                $tour->description   = $data['description'];
                $tour->price         = $data['price'];
                $tour->policy        = $data['policy'];
                $tour->supplier      = $data['supplier'];
                $tour->status        = $data['status'];
                $tour->category_name = $data['category_name'];
                $tour->supplier_name = $data['supplier_name'];
                return $tour;
            }
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
    }

    // Thêm tour
    public function create(Tour $tour){        
        try{
            $sql="INSERT INTO `tour`(`tour_name`, `category_id`, `description`, `price`, `policy`, `supplier`, `status`)
                 VALUES(:tour_name, :category_id, :description, :price, :policy, :supplier, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name"  => $tour->tour_name,
                ":category_id"=> $tour->category_id,
                ":description"=> $tour->description,
                ":price"      => $tour->price,
                ":policy"     => $tour->policy,
                ":supplier"   => $tour->supplier,
                ":status"     => $tour->status
            ]);
            return $stmt->rowCount();
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
    }

    // Cập nhật tour
    public function update(Tour $tour){                 
        try{
            $id=(int)$tour->tour_id;
            $sql="UPDATE `tour` SET 
                    `tour_name` = :tour_name, 
                    `category_id` = :category_id,
                    `description` = :description,
                    `price` = :price,
                    `policy` = :policy,
                    `supplier` = :supplier,
                    `status` = :status
                  WHERE `tour_id` = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name"  => $tour->tour_name,
                ":category_id"=> $tour->category_id,
                ":description"=> $tour->description,
                ":price"      => $tour->price,
                ":policy"     => $tour->policy,
                ":supplier"   => $tour->supplier,
                ":status"     => $tour->status,
                ":id"         => $id
            ]);
            return $stmt->rowCount();
        }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
    }

    // Xóa tour
    public function delete($id){
        $sql = "DELETE FROM tour WHERE tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
?>
