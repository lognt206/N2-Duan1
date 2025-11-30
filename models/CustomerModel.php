<?php 
class Customer {
    public $customer_id;
    public $group_id;
    public $full_name;
    public $gender;
    public $birth_year;
    public $id_number;
    public $contact;
    public $payment_status;
    public $special_request;
}

class CustomerModel {
    public PDO $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy toàn bộ khách hàng
    public function allcustomer(): array {
        try {
            $sql = "SELECT c.*, cg.group_name 
                    FROM customer c
                    LEFT JOIN customer_group cg ON c.group_id = cg.group_id";
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            throw new Exception("Lỗi lấy khách hàng: " . $err->getMessage());
        }
    }

    // Tìm theo ID
    public function find_customer(int $id): ?array {
        try {
            $sql = "SELECT * FROM customer WHERE customer_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $err) {
            throw new Exception("Lỗi tìm khách hàng: " . $err->getMessage());
        }
    }

    // Thêm khách hàng
    public function create_customer(Customer $customer): int {
    try {
        if (empty($customer->group_id)) {
            throw new Exception("Nhóm khách bắt buộc phải chọn!");
        }

        $sql = "INSERT INTO customer 
            (group_id, full_name, gender, birth_year, id_number, contact, payment_status, special_request)
            VALUES 
            (:group_id, :full_name, :gender, :birth_year, :id_number, :contact, :payment_status, :special_request)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            'group_id'        => $customer->group_id,
            'full_name'       => $customer->full_name ?? null,
            'gender'          => $customer->gender ?? null,
            'birth_year'      => $customer->birth_year ?? null,
            'id_number'       => $customer->id_number ?? null,
            'contact'         => $customer->contact ?? null,
            'payment_status'  => $customer->payment_status ?? 0,
            'special_request' => $customer->special_request ?? null
        ]);

        return (int)$this->conn->lastInsertId();

    } catch (PDOException $err) {
        throw new Exception("Lỗi tạo khách hàng: " . $err->getMessage());
    }
}


    // Xóa khách hàng
    public function delete_customer(int $id): bool {
        try {
            $sql = "DELETE FROM customer WHERE customer_id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi xóa khách hàng: " . $err->getMessage());
        }
    }

    // Cập nhật khách hàng
    public function update_customer(Customer $customer): bool {
        try {
            $sql = "UPDATE customer SET 
                        group_id = :group_id,
                        full_name = :full_name,
                        gender = :gender,
                        birth_year = :birth_year,
                        id_number = :id_number,
                        contact = :contact,
                        payment_status = :payment_status,
                        special_request = :special_request
                    WHERE customer_id = :customer_id";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                'group_id'        => $customer->group_id,
                'full_name'       => $customer->full_name ?? null,
                'gender'          => $customer->gender ?? null,
                'birth_year'      => $customer->birth_year ?? null,
                'id_number'       => $customer->id_number ?? null,
                'contact'         => $customer->contact ?? null,
                'payment_status'  => $customer->payment_status ?? 0,
                'special_request' => $customer->special_request ?? null,
                'customer_id'     => $customer->customer_id
            ]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi cập nhật khách hàng: " . $err->getMessage());
        }
    }
public function count() {
    $sql = "SELECT COUNT(*) as total FROM customer";
    $stmt = $this->conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}



}
?>
