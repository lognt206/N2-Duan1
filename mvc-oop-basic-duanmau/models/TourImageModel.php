<?php

class TourImage {
    public $image_id;
    public $tour_id;
    public $image_path;
}

class TourImageModel {
    public $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả ảnh của 1 tour
    public function getImagesByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tour_images WHERE tour_id = :tour_id");
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm ảnh cho tour (có thể thêm nhiều ảnh cùng lúc)
    public function addImages($tour_id, $images = []) {
        if (empty($images)) return 0;

        $stmt = $this->conn->prepare("INSERT INTO tour_images(tour_id, image_path) VALUES(:tour_id, :image_path)");
        $count = 0;
        foreach ($images as $img) {
            $stmt->execute(['tour_id' => $tour_id, 'image_path' => $img]);
            $count++;
        }
        return $count;
    }

    // Xóa 1 ảnh theo image_id
    public function deleteImage($image_id) {
        $stmt = $this->conn->prepare("DELETE FROM tour_images WHERE image_id = :image_id");
        $stmt->execute(['image_id' => $image_id]);
        return $stmt->rowCount();
    }

    // Xóa tất cả ảnh của 1 tour
    public function deleteByTour($tour_id) {
        $stmt = $this->conn->prepare("DELETE FROM tour_images WHERE tour_id = :tour_id");
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->rowCount();
    }

    // Cập nhật gallery mới (xóa cũ và thêm mới)
    public function updateImages($tour_id, $images = []) {
        $this->deleteByTour($tour_id);
        return $this->addImages($tour_id, $images);
    }
}
?>
