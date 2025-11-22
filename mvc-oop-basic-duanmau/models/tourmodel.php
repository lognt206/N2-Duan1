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
    public $supplier_ids = []; // mảng id nhà cung cấp
    public $supplier_names = []; // mảng tên nhà cung cấp
}

class TourModel {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour kèm tên danh mục và nhà cung cấp
    public function all() {
        try {
            $sql = "SELECT t.*, tc.category_name
                    FROM `tour` t
                    LEFT JOIN `tourcategory` tc ON t.category_id = tc.category_id
                    ORDER BY t.tour_id DESC";
            $data = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            // Lấy danh sách nhà cung cấp cho mỗi tour
            foreach ($data as &$tour) {
                $tour_id = $tour['tour_id'];
                $sql2 = "SELECT p.partner_id, p.partner_name 
                         FROM tour_partner tp
                         JOIN partner p ON tp.partner_id = p.partner_id
                         WHERE tp.tour_id = :tour_id";
                $stmt = $this->conn->prepare($sql2);
                $stmt->execute(['tour_id' => $tour_id]);
                $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $tour['supplier_ids'] = array_column($partners, 'partner_id');
                $tour['supplier_names'] = array_column($partners, 'partner_name');
            }

            return $data;
        } catch (PDOException $err) {
            echo "Lỗi: " . $err->getMessage();
            return [];
        }
    }

    // Lấy 1 tour theo id
    public function find($id) {
        try {
            $sql = "SELECT t.*, tc.category_name
                    FROM `tour` t
                    LEFT JOIN `tourcategory` tc ON t.category_id = tc.category_id
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

                // Lấy danh sách nhà cung cấp
                $sql2 = "SELECT p.partner_id, p.partner_name 
                         FROM tour_partner tp
                         JOIN partner p ON tp.partner_id = p.partner_id
                         WHERE tp.tour_id = :tour_id";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute(['tour_id' => $tour->tour_id]);
                $partners = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $tour->supplier_ids = array_column($partners, 'partner_id');
                $tour->supplier_names = array_column($partners, 'partner_name');

                return $tour;
            }
        } catch (PDOException $err) {
            echo "Lỗi: " . $err->getMessage();
        }
    }

    // Thêm tour
    public function create(Tour $tour) {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO `tour`(`tour_name`, `category_id`, `description`, `price`, `policy`, `status`)
                    VALUES(:tour_name, :category_id, :description, :price, :policy, :status)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name" => $tour->tour_name,
                ":category_id" => $tour->category_id,
                ":description" => $tour->description,
                ":price" => $tour->price,
                ":policy" => $tour->policy,
                ":status" => $tour->status
            ]);
            $tour_id = $this->conn->lastInsertId();

            // Thêm nhà cung cấp
            if (!empty($tour->supplier_ids)) {
                $sql2 = "INSERT INTO tour_partner(tour_id, partner_id) VALUES (:tour_id, :partner_id)";
                $stmt2 = $this->conn->prepare($sql2);
                foreach ($tour->supplier_ids as $partner_id) {
                    $stmt2->execute(['tour_id' => $tour_id, 'partner_id' => $partner_id]);
                }
            }

            $this->conn->commit();
            return $tour_id;
        } catch (PDOException $err) {
            $this->conn->rollBack();
            echo "Lỗi: " . $err->getMessage();
            return 0;
        }
    }

    // Cập nhật tour
    public function update(Tour $tour) {
        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE `tour` SET 
                        tour_name = :tour_name,
                        category_id = :category_id,
                        description = :description,
                        price = :price,
                        policy = :policy,
                        status = :status
                    WHERE tour_id = :tour_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ":tour_name" => $tour->tour_name,
                ":category_id" => $tour->category_id,
                ":description" => $tour->description,
                ":price" => $tour->price,
                ":policy" => $tour->policy,
                ":status" => $tour->status,
                ":tour_id" => $tour->tour_id
            ]);

            // Xóa các nhà cung cấp cũ
            $sql_del = "DELETE FROM tour_partner WHERE tour_id = :tour_id";
            $stmt_del = $this->conn->prepare($sql_del);
            $stmt_del->execute(['tour_id' => $tour->tour_id]);

            // Thêm lại nhà cung cấp mới
            if (!empty($tour->supplier_ids)) {
                $sql2 = "INSERT INTO tour_partner(tour_id, partner_id) VALUES (:tour_id, :partner_id)";
                $stmt2 = $this->conn->prepare($sql2);
                foreach ($tour->supplier_ids as $partner_id) {
                    $stmt2->execute(['tour_id' => $tour->tour_id, 'partner_id' => $partner_id]);
                }
            }

            $this->conn->commit();
            return $stmt->rowCount();
        } catch (PDOException $err) {
            $this->conn->rollBack();
            echo "Lỗi cập nhật: " . $err->getMessage();
            return 0;
        }
    }

    // Xóa tour
    public function delete($id) {
        try {
            $sql = "DELETE FROM tour WHERE tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $err) {
            echo "Lỗi xóa: " . $err->getMessage();
            return 0;
        }
    }
}
?>
