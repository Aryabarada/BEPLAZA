<?php
require_once '../layout/koneksi.php';
require_once '../layout/function.php';
require_once 'user.php';
require_once 'booking.php';
require_once 'layanan.php';
require_once 'order.php';

$UserObj = new User($con);
$layananObj = new layanan($con);
$bookingObj = new booking($con);
$orderObj = new order($con);

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_SERVER['PATH_INFO'];

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

switch ($method) {
    case 'GET':
        if ($endpoint === '/User') {
            $User = $UserObj->getAllUser();
            echo json_encode($User);
        } 
        if ($endpoint === '/UserLogin') {
            $User = $UserObj->getAllUserLogin();
            echo json_encode($User);
        }
        elseif ($endpoint === '/layanan') {
            $layanan = $layananObj->getAllLayanan();
            echo json_encode($layanan);
        } 
        elseif ($endpoint === '/booking') {
            $booking = $bookingObj->getAllBooking();
            echo json_encode($booking);
        }
        elseif ($endpoint === '/bookingNoBookingPrice') {
            $booking = $bookingObj->getAllBookingWithNullPrice();
            echo json_encode($booking);
        }
        elseif ($endpoint === '/order') {
            $order = $orderObj->getAllOrder();
            echo json_encode($order);
        }
        elseif (preg_match('/^\/UserBookingHistory\/(\d+)$/', $endpoint, $matches)) {
            $UserId = $matches[1];
            $booking = $bookingObj->getBookingByUserID($UserId);
            echo json_encode($booking);
        }
        elseif (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {
            $UserId = $matches[1];
            $User = $UserObj->getUserById($UserId);
            echo json_encode($User);
        }
        elseif (preg_match('/^\/layanan\/(\d+)$/', $endpoint, $matches)) {
            $layananId = $matches[1];
            $layanan = $layananObj->getLayananById($layananId);              
            echo json_encode($layanan);
        }
        elseif (preg_match('/^\/BookingOrder\/(\d+)$/', $endpoint, $matches)) {
            $Id = $matches[1];
            $order = $orderObj->getOrderByIdBooking($Id);
            echo json_encode($order);
        }
        elseif (preg_match('/^\/booking\/(\d+)$/', $endpoint, $matches)) {
            $tanggal = $matches[1];
            $bookedTimes = $bookingObj->getBookedTimes($tanggal);
            if (isset($bookedTimes['error'])) {
                echo json_encode($bookedTimes);
            } else {
                echo json_encode($bookedTimes);
            }
        }
        elseif (preg_match('/^\/bookingNoRange/', $endpoint, $matches)) {
            $bookedTimes = $bookingObj->getAllBookedHistory();
            if (isset($bookedTimes['error'])) {
                echo json_encode($bookedTimes);
            } else {
                echo json_encode($bookedTimes);
            }
        }
        elseif (preg_match('/^\/bookingRange\/(\d+)\/(\d+)$/', $endpoint, $matches)) {
            list($tahunAwal, $bulanAwal, $tanggalAwal) = sscanf($matches[1], "%4d%2d%2d");
            $tanggal_formatAwal = "$tahunAwal-$bulanAwal-$tanggalAwal";
            list($tahunAkhir, $bulanAkhir, $tanggalAkhir) = sscanf($matches[2], "%4d%2d%2d");
            $tanggal_formatAkhir = "$tahunAkhir-$bulanAkhir-$tanggalAkhir";
            $bookedTimes = $bookingObj->getBookedHistoryByRange($tanggal_formatAwal, $tanggal_formatAkhir);
            if (isset($bookedTimes['error'])) {
                echo json_encode($bookedTimes);
            } else {
                echo json_encode($bookedTimes);
            }
        }
        break;

    case 'POST':
        if ($endpoint === '/User') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $UserObj->addUser($data);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/booking') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $bookingObj->addBooking($data);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/layanan') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $layananObj->addLayanan($data);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/gambarlayanan') {
            $gambarInfo = $_FILES['gambar']; 
            $result = $layananObj->addGambarLayanan($gambarInfo);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/gambarlayanan\/(\d+)$/', $endpoint, $matches)) {
            $layananId = $matches[1];
            $gambarInfo = $_FILES['gambar']; 
            $result = $layananObj->updateGambarLayanan($layananId, $gambarInfo);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/booking-admin') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $bookingObj->addBookingAdmin($data);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'PUT':
        if (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {
            $UserId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $UserObj->updateUser($UserId, $data);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/bookingUpdate\/(\d+)$/', $endpoint, $matches)) {
            $UserId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $bookingObj->updateBooking($UserId, $data);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/layanan\/(\d+)$/', $endpoint, $matches)) {
            $layananId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $layananObj->updateLayanan($layananId, $data);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'DELETE':
        if (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {
            $UserId = $matches[1];
            $result = $UserObj->deleteUser($UserId);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/booking\/(\d+)$/', $endpoint, $matches)) {
            $bookingId = $matches[1];
            $result = $bookingObj->deleteBooking($bookingId);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/layanan\/(\d+)$/', $endpoint, $matches)) {
            $layananId = $matches[1];
            $deleteResult = $layananObj->deleteLayanan($layananId);
            if ($deleteResult === true) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $deleteResult]);
            }
        }
        break;
}
