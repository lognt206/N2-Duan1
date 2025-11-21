<?php
require_once __DIR__ . "/../models/BookingModel.php";

class BookingController
{
    public $BookingModel;

    public function __construct()
    {
        $this->BookingModel = new BookingModel();
    }

    public function index()
    {
        $bookings = $this->BookingModel->all(); // đổi tên biến
        $thanhcong = "";
        $loi = "";
        include "views/admin/booking/noidung.php";
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking = new Booking();
            $booking->tour_id      = $_POST['tour_id'];
            $booking->customer_id      = $_POST['customer_id'];
            $booking->booking_date = $_POST['booking_date'];
            $booking->num_people   = $_POST['num_people'];
            $booking->booking_type = $_POST['booking_type'];
            $booking->status       = $_POST['status'];
            $booking->notes        = $_POST['notes'];

            $this->BookingModel->create($booking);
            header("Location: ?act=booking");
            exit;
        }
        include "views/admin/booking/create.php";
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo "Không tìm thấy booking!";
            exit;
        }

        $booking = $this->BookingModel->find($id);
        if (!$booking) {
            echo "Booking không tồn tại!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $booking->tour_id      = $_POST['tour_id'];
            $booking->customer_id      = $_POST['customer_id'];
            $booking->booking_date = $_POST['booking_date'];
            $booking->num_people   = $_POST['num_people'];
            $booking->booking_type = $_POST['booking_type'];
            $booking->status       = $_POST['status'];
            $booking->notes        = $_POST['notes'];

            $this->BookingModel->update($booking);
            header("Location: ?act=booking");
            exit;
        }

        include "views/admin/booking/update.php";
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->BookingModel->delete($id);
        }
        header("Location: ?act=booking");
        exit;
    }
}
