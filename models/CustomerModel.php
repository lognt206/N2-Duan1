<?php 
class Customer {
    public $customer_id;
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
    public function allcustomer() {
    $sql = "SELECT customer_id, full_name, gender, birth_year, id_number, contact, payment_status, special_request FROM customer";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Lấy khách hàng chưa có booking trùng lịch
    public function availableCustomers($departure_id = null, $booking_date = null): array {
        try {
            $sql = "SELECT * FROM customer c
                    WHERE c.customer_id NOT IN (
                        SELECT b.customer_id 
                        FROM booking b
                        WHERE (:departure_id IS NULL OR b.departure_id = :departure_id)
                          AND (:booking_date IS NULL OR b.booking_date = :booking_date)
                    )";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':departure_id' => $departure_id,
                ':booking_date' => $booking_date
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            throw new Exception("Lỗi lấy khách hàng chưa có booking: " . $err->getMessage());
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
            $sql = "INSERT INTO customer 
                (full_name, gender, birth_year, id_number, contact, payment_status, special_request)
                VALUES 
                (:full_name, :gender, :birth_year, :id_number, :contact, :payment_status, :special_request)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
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

    public function count(): int {
        $sql = "SELECT COUNT(*) as total FROM customer";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
    
// Tìm khách theo contact (số điện thoại / thông tin liên lạc)
public function findByContact(string $contact): ?array {
    $sql = "SELECT * FROM customer WHERE contact = :contact LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':contact' => $contact
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}


}
?>
