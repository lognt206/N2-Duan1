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
public function delete_danhmuc($category_id){        //thêm danh mục
            try{
                $sql="DELETE FROM tourcategory WHERE `tourcategory`.`category_id` = $category_id";
                $data=$this->conn->exec($sql);
                return $data;

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }

        public function find($category_id){//tìm
            try{
                $sql="SELECT * FROM `tourcategory` WHERE category_id = $category_id";
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

        public function update_danhmuc(Category $danhmuc){      
            try{
                $category_id = (int)$danhmuc->category_id;
                $sql="UPDATE `tourcategory` SET `category_id` = '".$danhmuc->category_id."','".$danhmuc->category_name."','".$danhmuc->status."' WHERE `tourcategory`.`category_id` = $category_id;";
                $data=$this->conn->exec($sql);
                return $data;

            }catch (PDOException $err) {
            echo "Lỗi truy vấn sản phẩm: " . $err->getMessage();
        }
        }
   
}
?>