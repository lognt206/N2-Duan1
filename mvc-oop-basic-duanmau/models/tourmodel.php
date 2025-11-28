<?php

class Tour {
    public $tour_id;
    public $tour_name;
    public $category_id;
    public $description;
    public $price;
    public $policy;
    public $status;
    public $category_name;
    public $supplier_ids = [];
    public $supplier_names = [];
    
    public $image;        // Ảnh đại diện
}

class TourModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

   public function all() {
    $sql = "SELECT t.*, tc.category_name
            FROM tour t
            LEFT JOIN tourcategory tc ON t.category_id = tc.category_id
            ORDER BY t.tour_id DESC"; // KHÔNG GROUP BY, KHÔNG JOIN partner
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





    // Lấy 1 tour theo id
    public function find($id) {
        $sql = "SELECT t.*, tc.category_name
                FROM tour t
                LEFT JOIN tourcategory tc ON t.category_id = tc.category_id
                WHERE t.tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $tour = new Tour();
            $tour->tour_id = $data['tour_id'];
            $tour->tour_name = $data['tour_name'];
            $tour->category_id = $data['category_id'];
            $tour->description = $data['description'];
            $tour->price = $data['price'];
            $tour->policy = $data['policy'];
            $tour->status = $data['status'];
            $tour->category_name = $data['category_name'];
            $tour->image = $data['image'];

            // Lấy nhà cung cấp
            $stmt2 = $this->conn->prepare("SELECT p.partner_id, p.partner_name 
                                           FROM tour_partner tp
                                           JOIN partner p ON tp.partner_id = p.partner_id
                                           WHERE tp.tour_id = :tour_id");
            $stmt2->execute(['tour_id' => $tour->tour_id]);
            $partners = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $tour->supplier_ids = array_column($partners, 'partner_id');
            $tour->supplier_names = array_column($partners, 'partner_name');

            return $tour;
        }
        return null;
    }

    // Thêm tour
    public function create(Tour $tour) {
        try {
           $this->conn->beginTransaction();

// 1. Thêm tour
$sql = "INSERT INTO tour(tour_name, category_id, description, price, policy, status, image)
        VALUES(:tour_name, :category_id, :description, :price, :policy, :status, :image)";
$stmt = $this->conn->prepare($sql);
$stmt->execute([
    ":tour_name" => $tour->tour_name,
    ":category_id" => $tour->category_id,
    ":description" => $tour->description,
    ":price" => $tour->price,
    ":policy" => $tour->policy,
    ":status" => $tour->status,
    ":image" => $tour->image
]);

$tour_id = $this->conn->lastInsertId();

// 2. Thêm nhiều nhà cung cấp cho tour này
if (!empty($tour->supplier_ids)) {
    $sql2 = "INSERT INTO tour_partner(tour_id, partner_id) VALUES (:tour_id, :partner_id)";
    $stmt2 = $this->conn->prepare($sql2);

    // tránh trùng partner_id
    $unique_suppliers = array_unique($tour->supplier_ids);

    foreach ($unique_suppliers as $partner_id) {
        $stmt2->execute([':tour_id' => $tour_id, ':partner_id' => $partner_id]);
    }
}

$this->conn->commit();
            return $tour_id;
        } catch (PDOException $err) {
            $this->conn->rollBack();
            echo "Lỗi Tour: " . $err->getMessage();
            return 0;
        }
    }

    // Cập nhật tour
    public function update(Tour $tour) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE tour SET 
                        tour_name = :tour_name,
                        category_id = :category_id,
                        description = :description,
                        price = :price,
                        policy = :policy,
                        status = :status,
                        image = :image
                    WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name" => $tour->tour_name,
                ":category_id" => $tour->category_id,
                ":description" => $tour->description,
                ":price" => $tour->price,
                ":policy" => $tour->policy,
                ":status" => $tour->status,
                ":image" => $tour->image,
                ":tour_id" => $tour->tour_id
            ]);

            // Cập nhật nhà cung cấp
            $this->conn->prepare("DELETE FROM tour_partner WHERE tour_id=:tour_id")->execute(['tour_id'=>$tour->tour_id]);
            if (!empty($tour->supplier_ids)) {
                $sql2 = "INSERT INTO tour_partner(tour_id, partner_id) VALUES (:tour_id, :partner_id)";
                $stmt2 = $this->conn->prepare($sql2);
                foreach ($tour->supplier_ids as $partner_id) {
                    $stmt2->execute(['tour_id'=>$tour->tour_id, 'partner_id'=>$partner_id]);
                }
            }

            $this->conn->commit();
            return $stmt->rowCount();
        } catch (PDOException $err) {
            $this->conn->rollBack();
            echo "Lỗi Tour Update: " . $err->getMessage();
            return 0;
        }
    }

    // Xóa tour
    public function delete($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM tour WHERE tour_id=:id");
            $stmt->execute(['id'=>$id]);
            return $stmt->rowCount();
        } catch (PDOException $err) {
            echo "Lỗi Tour Delete: " . $err->getMessage();
            return 0;
        }
    }

public function countTours() {
    $sql = "SELECT COUNT(*) as total FROM tour";
    $stmt = $this->conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}




}
?>
