<?php
class CustomerGroup {
    public $group_id;
    public $group_name;
}

class CustomerGroupModel {
    public PDO $conn;

    public function __construct() {
        $this->conn = connectDB(); // kết nối database
    }

    // Lấy toàn bộ nhóm khách
    public function all(): array {
        try {
            $sql = "SELECT * FROM customer_group ORDER BY group_name ASC";
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            throw new Exception("Lỗi lấy nhóm khách: " . $err->getMessage());
        }
    }

    // Tìm nhóm theo ID
    public function find(int $id): ?array {
        try {
            $sql = "SELECT * FROM customer_group WHERE group_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $err) {
            throw new Exception("Lỗi tìm nhóm khách: " . $err->getMessage());
        }
    }

    // Thêm nhóm khách
    public function create(CustomerGroup $group): int {
        try {
            $sql = "INSERT INTO customer_group (group_name) VALUES (:group_name)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'group_name' => $group->group_name ?? null
            ]);
            return (int)$this->conn->lastInsertId();
        } catch (PDOException $err) {
            throw new Exception("Lỗi tạo nhóm khách: " . $err->getMessage());
        }
    }

    // Cập nhật nhóm khách
    public function update(CustomerGroup $group): bool {
        try {
            $sql = "UPDATE customer_group SET group_name = :group_name WHERE group_id = :group_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                'group_name' => $group->group_name ?? null,
                'group_id'   => $group->group_id
            ]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi cập nhật nhóm khách: " . $err->getMessage());
        }
    }

    // Xóa nhóm khách
    public function delete(int $id): bool {
        try {
            $sql = "DELETE FROM customer_group WHERE group_id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi xóa nhóm khách: " . $err->getMessage());
        }
    }
}
?>
