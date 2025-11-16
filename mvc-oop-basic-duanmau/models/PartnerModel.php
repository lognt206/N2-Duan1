<?php
class Partner{
    public $partner_id;
    public $partner_name;
    public $service_type;
    public $contact;
    public $address;
    public $notes;
} 
class PartnerModel{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }
    public function all(){
        try{
            $sql= "SELECT * FROM `partner`";
            $data = $this->conn->query($sql)->fetchAll();
            $danhsachpartner = [];
            foreach($data as $value){
                $partner = new Partner();
                $partner->partner_id          =$value['partner_id'];
                $partner->partner_name        =$value['partner_name'];
                $partner->service_type        =$value['service_type'];
                $partner->contact             =$value['contact'];
                $partner->address             =$value['address'];
                $partner->notes               =$value['notes'];
                $danhsachpartner[]=$partner;
            }
            return $danhsachpartner;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function find($id){
        try{
            $sql = "SELECT * FROM `partner` WHERE id = :id";
            $data = $this->conn->query($sql)->fetch();
            if($data !== false){
                $partner = new Partner();
                $partner->partner_id          =$data['partner_id'];
                $partner->partner_name        =$data['partner_name'];
                $partner->service_type        =$data['service_type'];
                $partner->contact             =$data['contact'];
                $partner->address             =$data['address'];
                $partner->notes               =$data['notes'];
                return $partner;
            }
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function create(Partner $partner){
        try{
            $sql = "INSERT INTO `partner` (`partner_id`, `partner_name`, `service_type`, `contact`, `address`, `notes`)
            VALUES (NULL, '".$partner->partner_id."',
                            '".$partner->partner_name."',
                            '".$partner->service_type."',
                            '".$partner->contact."',
                            '".$partner->address."',
                            '".$partner->notes."');";
            $data = $this->conn->exec($sql);
            return $data;
        }catch (PDOException $err){
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function delete_partner($id){
        try{
            $sql = "DELETE FROM partner WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
            return false;
        }
    }
    public function update(Partner $partner){
        try{
            $id = (int)$partner->partner_id;
            $sql = "UPDATE `partner`
            SET `partner_id` = '".$partner->partner_id."',
            `partner_name` = '".$partner->partner_name."',
            `service_type` = '".$partner->service_type."',
            `contact` = '".$partner->contact."',
            `address` = '".$partner->address."',
            `notes` = '".$partner->notes."'
            WHERE `partner`.`id` = $id;";
            $data = $this->conn->exec($sql);
            return $data;    
        }catch (PDOException $err){
            echo "Lỗi : " . $err->getMessage();
        }    
    }
}
?>