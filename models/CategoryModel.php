<?php
class Category {
    public $category_id;
    public $category_name;
    public $status;
    
}
class CategoryModel{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
public function all(){
            try{
                $sql="SELECT * FROM `tourcategory`";
                $data=$this->conn->query($sql)->fetchAll();
                $danhsachCategory=[];
                foreach($data as $value){
                    $Category = new Category();
                    $Category->category_id          =$value['category_id'];
                    $Category->category_name       =$value['category_name'];
                    $Category->status      =$value['status'];
                    
                    $danhsachCategory[]=$Category;
                }
                return $danhsachCategory;

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }
        // Thêm danh mục
    public function create_danhmuc(Category $category) {
        try{
        $sql = " INSERT INTO `tourcategory`(`category_id`, `category_name`, `status`) VALUES (NULL,'".$category->category_name."','".$category->status."')";
       $data=$this->conn->exec($sql);
          return $data;
        }catch (PDOException $err) {
            echo "Lỗi : " . $err->getMessage();
        }
    }

// xóa danh mục 
public function delete($id)
{
    try {
        $sql = "DELETE FROM tourcategory WHERE category_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}




        public function isUsed($category_id) {
    try {
        $sql = "SELECT COUNT(*) AS total FROM `tour` WHERE category_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', (int)$category_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (isset($row['total']) && (int)$row['total'] > 0);
    } catch (PDOException $e) {
        // nếu muốn debug: echo $e->getMessage();
        return false; // coi như không được dùng (an toàn hơn)
    }
}
        public function find($id){//tìm
            try{
                $sql="SELECT * FROM `tourcategory` WHERE category_id = $id";
                $data=$this->conn->query($sql)->fetch();
                if($data !== false){
                    $danhmuc = new Category();
                    $danhmuc->category_id          = $data['category_id'];
                    $danhmuc->category_name        = $data['category_name'];
                    $danhmuc->status        = $data['status'];

                    return $danhmuc;
                }

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }
public function update_danhmuc(Category $danhmuc) {
    $sql = "UPDATE tourcategory SET category_name = :name, status = :status WHERE category_id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        'name' => $danhmuc->category_name,
        'status' => $danhmuc->status,
        'id' => $danhmuc->category_id
    ]);
}
   
}
?>