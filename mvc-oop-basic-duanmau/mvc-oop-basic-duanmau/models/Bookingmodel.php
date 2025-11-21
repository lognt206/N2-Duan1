<?php
require_once './commons/env.php';

class Booking {
    public $booking_id;
    public $tour_id;
    public $user_id;
    public $booking_date;
    public $num_people;
    public $booking_type;
    public $status;
    public $notes;
}

class BookingModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function all() {
        try {
            $sql = "SELECT * FROM booking ORDER BY booking_id DESC";
            $data = $this->conn->query($sql)->fetchAll();
            $list = [];
            foreach ($data as $row) {
                $b = new Booking();
                $b->booking_id = $row['booking_id'];
                $b->tour_id = $row['tour_id'];
                $b->user_id = $row['user_id'];
                $b->booking_date = $row['booking_date'];
                $b->num_people = $row['num_people'];
                $b->booking_type = $row['booking_type'];
                $b->status = $row['status'];
                $b->notes = $row['notes'];
                $list[] = $b;
            }
            return $list;
        } catch (PDOException $err) {
            echo "Lỗi truy vấn Booking: " . $err->getMessage();
        }
    }

    public function find($id) {
        try {
            $sql = "SELECT * FROM booking WHERE booking_id = $id";
            $row = $this->conn->query($sql)->fetch();
            if ($row !== false) {
                $b = new Booking();
                $b->booking_id = $row['booking_id'];
                $b->tour_id = $row['tour_id'];
                $b->user_id = $row['user_id'];
                $b->booking_date = $row['booking_date'];
                $b->num_people = $row['num_people'];
                $b->booking_type = $row['booking_type'];
                $b->status = $row['status'];
                $b->notes = $row['notes'];
                return $b;
            }
        } catch (PDOException $err) {
            echo "Lỗi truy vấn Booking: " . $err->getMessage();
        }
    }

    public function create(Booking $b) {
        try {
            $sql = "INSERT INTO booking (tour_id, user_id, booking_date, num_people, booking_type, status, notes)
                    VALUES ('".$b->tour_id."', '".$b->user_id."', '".$b->booking_date."', '".$b->num_people."', '".$b->booking_type."', '".$b->status."', '".$b->notes."')";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi thêm Booking: " . $err->getMessage();
        }
    }

    public function update(Booking $b) {
        try {
            $sql = "UPDATE booking SET
                    tour_id='".$b->tour_id."',
                    user_id='".$b->user_id."',
                    booking_date='".$b->booking_date."',
                    num_people='".$b->num_people."',
                    booking_type='".$b->booking_type."',
                    status='".$b->status."',
                    notes='".$b->notes."'
                    WHERE booking_id='".$b->booking_id."'";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi cập nhật Booking: " . $err->getMessage();
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM booking WHERE booking_id = $id";
            return $this->conn->exec($sql);
        } catch (PDOException $err) {
            echo "Lỗi xóa Booking: " . $err->getMessage();
        }
    }
}
?>
