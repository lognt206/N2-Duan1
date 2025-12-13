<?php

class Tourlog
{
    public $log_id;
    public $departure_id;
    public $log_date;
    public $content;        // PHẢN HỒI KHÁCH HÀNG
    public $photo;
    public $guide_review;   // ĐÁNH GIÁ HDV
}

class TourlogModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /* -------------------------------------------------
        LẤY DANH SÁCH NHẬT KÝ THEO DEPARTURE
    --------------------------------------------------*/
    public function find_Tour_log($departure_id)
    {
        try {
            $sql = "SELECT * FROM tourlog 
                    WHERE departure_id = :departure_id
                    ORDER BY log_date DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':departure_id' => (int)$departure_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Lỗi find_Tour_log: " . $err->getMessage());
            return [];
        }
    }

    /* -------------------------------------------------
        LẤY CHI TIẾT 1 NHẬT KÝ (DÙNG CHO EDIT)
    --------------------------------------------------*/
    public function getTourLogById($log_id)
    {
        try {
            $sql = "SELECT tl.*, b.booking_id
                    FROM tourlog tl
                    JOIN booking b ON tl.departure_id = b.departure_id
                    WHERE tl.log_id = :log_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Lỗi getTourLogById: " . $err->getMessage());
            return null;
        }
    }

    /* -------------------------------------------------
        TẠO NHẬT KÝ TOUR MỚI
        - content: phản hồi khách hàng
        - guide_review: đánh giá HDV
    --------------------------------------------------*/
    public function create_tourlog(array $data): bool
    {
        try {
            $sql = "INSERT INTO tourlog
                    (departure_id, log_date, content, photo, guide_review)
                    VALUES (:departure_id, :log_date, :content, :photo, :guide_review)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':departure_id' => (int)$data['departure_id'],
                ':log_date'     => $data['log_date'],
                ':content'      => $data['content'] ?? null,
                ':photo'        => $data['photo'] ?? null,
                ':guide_review' => $data['guide_review'] ?? null
            ]);

            return true;
        } catch (PDOException $err) {
            error_log("Lỗi create_tourlog: " . $err->getMessage());
            return false;
        }
    }

    /* -------------------------------------------------
        CẬP NHẬT NHẬT KÝ TOUR
    --------------------------------------------------*/
    public function updateTourLog(
        int $log_id,
        ?string $content,
        ?string $guide_review,
        ?string $photo_path = null
    ): bool {
        try {
            $sql = "UPDATE tourlog SET
                        content = :content,
                        guide_review = :guide_review";

            if ($photo_path) {
                $sql .= ", photo = :photo";
            }

            $sql .= " WHERE log_id = :log_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':guide_review', $guide_review, PDO::PARAM_STR);
            $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT);

            if ($photo_path) {
                $stmt->bindParam(':photo', $photo_path, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Lỗi updateTourLog: " . $err->getMessage());
            return false;
        }
    }

    /* -------------------------------------------------
        XÓA NỘI DUNG NHẬT KÝ (KHÔNG XÓA RECORD)
    --------------------------------------------------*/
    public function deleteTourLogById($log_id)
{
    $sql = "DELETE FROM tourlog WHERE log_id = :log_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute(['log_id' => $log_id]);
}


}
