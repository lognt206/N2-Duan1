<?php
class Partner {
    public $partner_id;
    public $partner_name;
    public $service_type_id;
    public $service_type_name; // Thêm thuộc tính để lưu tên loại dịch vụ
    public $contact;
    public $address;
    public $notes;
} 

class PartnerModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả partner kèm tên service type
    public function all() {
        try {
            $sql = "SELECT p.*, s.name AS service_type_name
                    FROM partner p
                    LEFT JOIN service_type s ON p.service_type_id = s.id";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            $danhsachpartner = [];
            foreach($data as $value){
                $partner = new Partner();
                $partner->partner_id        = $value['partner_id'];
                $partner->partner_name      = $value['partner_name'];
                $partner->service_type_id   = $value['service_type_id'];
                $partner->service_type_name = $value['service_type_name']; // Gán tên loại dịch vụ
                $partner->contact           = $value['contact'];
                $partner->address           = $value['address'];
                $partner->notes             = $value['notes'];
                $danhsachpartner[] = $partner;
            }
            return $danhsachpartner;
        } catch (PDOException $err) {
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function find($id) {
        try {
            $sql = "SELECT p.*, s.name AS service_type_name
                    FROM partner p
                    LEFT JOIN service_type s ON p.service_type_id = s.id
                    WHERE p.partner_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if($data !== false){
                $partner = new Partner();
                $partner->partner_id        = $data['partner_id'];
                $partner->partner_name      = $data['partner_name'];
                $partner->service_type_id   = $data['service_type_id'];
                $partner->service_type_name = $data['service_type_name'];
                $partner->contact           = $data['contact'];
                $partner->address           = $data['address'];
                $partner->notes             = $data['notes'];
                return $partner;
            }
            return null;
        } catch (PDOException $err) {
            echo "Lỗi: ". $err->getMessage();
        }
    }

    public function create(Partner $partner) {
        try {
            $sql = "INSERT INTO partner (`partner_name`, `service_type_id`, `contact`, `address`, `notes`)
                    VALUES (:partner_name, :service_type_id, :contact, :address, :notes)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':partner_name', $partner->partner_name);
            $stmt->bindParam(':service_type_id', $partner->service_type_id);
            $stmt->bindParam(':contact', $partner->contact);
            $stmt->bindParam(':address', $partner->address);
            $stmt->bindParam(':notes', $partner->notes);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo "Lỗi: " . $err->getMessage();
        }
    }

    public function update(Partner $partner) {
        try {
            $sql = "UPDATE partner
                    SET partner_name = :partner_name,
                        service_type_id = :service_type_id,
                        contact = :contact,
                        address = :address,
                        notes = :notes
                    WHERE partner_id = :partner_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':partner_name', $partner->partner_name);
            $stmt->bindParam(':service_type_id', $partner->service_type_id);
            $stmt->bindParam(':contact', $partner->contact);
            $stmt->bindParam(':address', $partner->address);
            $stmt->bindParam(':notes', $partner->notes);
            $stmt->bindParam(':partner_id', $partner->partner_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo "Lỗi: " . $err->getMessage();
        }
    }

    public function delete_partner($id) {
        try {
            $sql = "DELETE FROM partner WHERE partner_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $err) {
            echo "Lỗi: " . $err->getMessage();
        }
    }
}
?>
