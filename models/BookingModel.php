<?php
class BookingModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả booking kèm số lượng khách
    public function all()
    {
        $sql = "SELECT 
                    b.booking_id,
                    b.booking_date,
                    b.num_people,
                    b.booking_type,
                    b.status,
                    b.notes,
                    t.tour_name,
                    g.full_name AS guide_name,
                    d.departure_date,
                    d.return_date
                FROM booking b
                JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                LEFT JOIN departure d ON b.departure_id = d.departure_id
                ORDER BY b.booking_id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Chi tiết booking + khách hàng + đối tác
   // Chi tiết booking + khách hàng + đối tác + tổng tiền
public function getBookingFullDetail(int $id): ?array {
    try {
        $sql = "SELECT 
                    b.*, 
                    t.tour_name, 
                    t.price AS tour_price,

                    d.departure_date, 
                    d.return_date, 
                    d.meeting_point, 

                    -- ⭐ FULL thông tin hướng dẫn viên
                    g.guide_id,
                    g.full_name AS guide_name,
                    g.photo AS guide_photo,
                    g.birth_date AS guide_birth,
                    g.contact AS guide_contact,
                    g.certificate AS guide_certificate,
                    g.languages AS guide_languages,
                    g.experience AS guide_experience,
                    g.health_condition AS guide_health,
                    g.rating AS guide_rating,
                    g.category AS guide_category,

                    -- ⭐ TÍNH TỔNG TIỀN BOOKING
                    CASE 
                        WHEN b.status = 1 THEN b.num_people * t.price
                        WHEN b.status = 2 THEN b.num_people * t.price * 0.5
                        ELSE 0
                    END AS total_amount

                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN departure d ON b.departure_id = d.departure_id
                LEFT JOIN tourguide g ON b.guide_id = g.guide_id
                WHERE b.booking_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) return null;

        // 🔥 Gom thông tin hướng dẫn viên thành 1 object giống View đang dùng
        $booking['guide'] = [
            'guide_id'        => $booking['guide_id'],
            'full_name'       => $booking['guide_name'],
            'photo'           => $booking['guide_photo'],
            'birth_date'      => $booking['guide_birth'],
            'contact'         => $booking['guide_contact'],
            'certificate'     => $booking['guide_certificate'],
            'languages'       => $booking['guide_languages'],
            'experience'      => $booking['guide_experience'],
            'health_condition'=> $booking['guide_health'],
            'rating'          => $booking['guide_rating'],
            'category'        => $booking['guide_category'],
        ];

        /* ---------------------------
           ⭐ LẤY DANH SÁCH KHÁCH HÀNG
        ---------------------------- */
        $booking['customers'] = [];
        if (!empty($booking['customer_ids'])) {
            $customer_ids = json_decode($booking['customer_ids'], true);

            if (is_array($customer_ids) && count($customer_ids) > 0) {
                $placeholders = implode(',', array_fill(0, count($customer_ids), '?'));
                $sql2 = "SELECT * FROM customer WHERE customer_id IN ($placeholders)";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute($customer_ids);
                $booking['customers'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        /* ---------------------------
           ⭐ LẤY ĐỐI TÁC CỦA TOUR
        ---------------------------- */
        $sql3 = "SELECT p.* 
                 FROM tour_partner tp
                 JOIN partner p ON tp.partner_id = p.partner_id
                 WHERE tp.tour_id = :tour_id";
        $stmt3 = $this->conn->prepare($sql3);
        $stmt3->execute(['tour_id' => $booking['tour_id']]);
        $booking['partners'] = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        return $booking;

    } catch (PDOException $err) {
        throw new Exception("Lỗi lấy chi tiết booking: " . $err->getMessage());
    }
}



    // Thêm booking
    public function insert($data)
    {
        try {
            $customer_ids_json = !empty($data['customer_ids']) ? json_encode($data['customer_ids'], JSON_NUMERIC_CHECK) : '[]';
            $num_people = count($data['customer_ids'] ?? []);

            $sql = "INSERT INTO booking 
                    (tour_id, guide_id, departure_id, booking_date, num_people, customer_ids, booking_type, status, notes)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                $data['tour_id'],
                !empty($data['guide_id']) ? $data['guide_id'] : null,
                !empty($data['departure_id']) ? $data['departure_id'] : null,
                $data['booking_date'] ?? null,
                $num_people,
                $customer_ids_json,
                $data['booking_type'] ?? 1,
                $data['status'] ?? 1,
                $data['notes'] ?? null
            ]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi thêm booking: " . $err->getMessage());
        }
    }

    // Cập nhật booking
   public function update($data)
{
    try {
        // Lấy trạng thái hiện tại
        $sqlCheck = "SELECT status FROM booking WHERE booking_id = :booking_id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute(['booking_id' => $data['booking_id']]);
        $current = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$current) {
            throw new Exception("Booking không tồn tại!");
        }

        // Chỉ cho phép sửa khi trạng thái = 0 (chờ xác nhận)
        if ((int)$current['status'] !== 0) {
            throw new Exception("Chỉ có booking đang chờ xác nhận mới được sửa!");
        }

        // Chuẩn bị dữ liệu
        $customer_ids_json = !empty($data['customer_ids']) ? json_encode($data['customer_ids'], JSON_NUMERIC_CHECK) : '[]';
        $num_people = count($data['customer_ids'] ?? []);

        // Chỉ cập nhật các cột tồn tại trong bảng booking
        $sql = "UPDATE booking SET 
                    tour_id = ?, 
                    guide_id = ?, 
                    departure_id = ?, 
                    booking_date = ?, 
                    num_people = ?, 
                    booking_type = ?, 
                    status = ?, 
                    notes = ?, 
                    customer_ids = ? 
                WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            !empty($data['guide_id']) ? $data['guide_id'] : null,
            !empty($data['departure_id']) ? $data['departure_id'] : null,
            $data['booking_date'] ?? null,
            $num_people,
            $data['booking_type'] ?? 1,
            $data['status'] ?? 0,
            $data['notes'] ?? null,
            $customer_ids_json,
            $data['booking_id']
        ]);
    } catch (PDOException $err) {
        throw new Exception("Lỗi cập nhật booking: " . $err->getMessage());
    }
}
    // Xóa booking
    public function delete($id)
    {
        $sql = "DELETE FROM booking WHERE booking_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

// Lọc booking theo keyword + status
public function filter($keyword = '', $status = '') {
    $sql = "SELECT 
                b.booking_id,
                b.booking_date,
                b.num_people,
                b.booking_type,
                b.status,
                b.notes,
                t.tour_name,
                g.full_name AS guide_name,
                d.departure_date,
                d.return_date
            FROM booking b
            JOIN tour t ON b.tour_id = t.tour_id
            LEFT JOIN tourguide g ON b.guide_id = g.guide_id
            LEFT JOIN departure d ON b.departure_id = d.departure_id
            WHERE 1 ";

    $params = [];

    // Tìm kiếm theo từ khóa
    if (!empty($keyword)) {
        $sql .= " AND (t.tour_name LIKE :kw OR g.full_name LIKE :kw)";
        $params['kw'] = '%' . $keyword . '%';
    }

    // Lọc theo trạng thái
    if ($status !== '' && $status !== null) {
        $sql .= " AND b.status = :status";
        $params['status'] = $status;
    }

    $sql .= " ORDER BY b.booking_id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Kiểm tra khách có booking trùng
    public function customerHasBooking($customer_id, $departure_id)
    {
        $sql = "SELECT COUNT(*) FROM booking 
                WHERE JSON_CONTAINS(customer_ids, :customer_id, '$') 
                AND departure_id = :departure_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':customer_id' => json_encode($customer_id),
            ':departure_id' => $departure_id
        ]);
        return $stmt->fetchColumn() > 0;
    }

  public function updateStatus(int $booking_id, int $status = 1): bool {
        try {
            $sql = "UPDATE booking SET status = :status WHERE booking_id = :booking_id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':booking_id' => $booking_id
            ]);
        } catch (PDOException $err) {
            throw new Exception("Lỗi cập nhật trạng thái booking: " . $err->getMessage());
        }
    }

// Các hàm thống kê
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM booking";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

   public function sumRevenue() { 
    $sql = "SELECT SUM(
                    CASE 
                        WHEN b.status = 1 THEN b.num_people * t.price
                        WHEN b.status = 2 THEN b.num_people * t.price * 0.5
                        ELSE 0
                    END
                ) AS totalRevenue
            FROM booking b
            JOIN tour t ON b.tour_id = t.tour_id";
    $stmt = $this->conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['totalRevenue'] ?? 0;
}


    public function sumRevenueByTour($tour_id) {
    $sql = "SELECT SUM(
                    CASE 
                        WHEN b.status = 1 THEN b.num_people * t.price
                        WHEN b.status = 2 THEN b.num_people * t.price * 0.5
                        ELSE 0
                    END
                ) AS totalRevenue
            FROM booking b
            JOIN tour t ON b.tour_id = t.tour_id
            WHERE b.tour_id = :tour_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['tour_id' => $tour_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['totalRevenue'] ?? 0;
}

// Đếm số tour đã hoàn thành của 1 hướng dẫn viên
public function countCompletedToursByGuide($guide_id) {
    try {
        $sql = "SELECT COUNT(DISTINCT b.tour_id) AS total
                FROM booking b
                WHERE b.guide_id = :guide_id
                  AND b.status = 1"; // status = 1 => hoàn thành
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['guide_id' => $guide_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    } catch (PDOException $err) {
        echo "Lỗi: " . $err->getMessage();
        return 0;
    }
}

public function checkCustomerConflict($customer_id, $start, $end)
{
    try {
        // customer_ids của bạn lưu dạng số -> ép về số
        $cid = intval($customer_id);

        $sql = "
            SELECT b.booking_id, d.departure_date, d.return_date
            FROM booking b
            JOIN departure d ON b.departure_id = d.departure_id
            WHERE JSON_CONTAINS(b.customer_ids, :cid) = 1
              AND (
                    d.departure_date <= :end
                AND d.return_date >= :start
              )
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':cid'  => $cid,      // không có dấu nháy -> đúng với kiểu số
            ':start'=> $start,
            ':end'  => $end
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        throw new Exception("Lỗi kiểm tra lịch khách hàng: " . $e->getMessage());
    }
}



}
