<?php
require_once '../layout/koneksi.php';
require_once '../layout/function.php';
require_once 'User.php';
require_once 'booking.php';
require_once 'layanan.php';
require_once 'order.php';
// Create an instance of the User class
$UserObj = new User($con);
$layananObj = new layanan($con);
$bookingObj = new booking($con);
$orderObj = new order($con);
// Get the request method
$method = $_SERVER['REQUEST_METHOD'];
// Get the requested endpoint
$endpoint = $_SERVER['PATH_INFO'];
// Set the response content type
header('Content-Type: application/json');
// Process the request
switch ($method) {
    case 'GET':
        if ($endpoint === '/User') {
            // Get all employees
            $User = $UserObj->getAllUser();
            echo json_encode($User);
        } 
        if ($endpoint === '/UserLogin') {
            // Get all employees
            $User = $UserObj->getAllUserLogin();
            echo json_encode($User);
        }
        elseif ($endpoint === '/layanan') {
            // Get all employees
            $layanan = $layananObj->getAllLayanan();
            echo json_encode($layanan);
        } 
        elseif ($endpoint === '/booking') {
            // Get all employees
            $booking = $bookingObj->getAllBooking();
            echo json_encode($booking);
        }
        elseif ($endpoint === '/bookingNoBookingPrice') {
            // Get all employees
            $booking = $bookingObj->getAllBookingWithNullPrice();
            echo json_encode($booking);
        }
        elseif ($endpoint === '/order') {
            // Get all employees
            $order = $orderObj->getAllOrder();
            echo json_encode($order);
        }
        elseif (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {
            // Get employee by ID
            $UserId = $matches[1];
            $User = $UserObj->getUserById($UserId);
            echo json_encode($User);
        }
        elseif (preg_match('/^\/layanan\/(\d+)$/', $endpoint, $matches)) {
            // Get employee by ID
            $layananId = $matches[1];
            $layanan = $layananObj->getLayananById($layananId);
            echo json_encode($layanan);
        }
        elseif (preg_match('/^\/BookingOrder\/(\d+)$/', $endpoint, $matches)) {
            // Get employee by ID
            $Id = $matches[1];
            $order = $orderObj->getOrderByIdBooking($Id);
            echo json_encode($order);
        }
        elseif (preg_match('/^\/booking\/(\d+)$/', $endpoint, $matches)) {
            $tanggal = $matches[1];
            $bookedTimes = $bookingObj->getBookedTimes($tanggal);
        
            // Periksa apakah terjadi kesalahan
            if (isset($bookedTimes['error'])) {
                // Tanggapan jika terjadi kesalahan
                echo json_encode($bookedTimes);
            } else {
                // Tanggapan jika berhasil
                echo json_encode($bookedTimes);
            }
        }
        elseif (preg_match('/^\/bookingNoRange/', $endpoint, $matches)) {
            $bookedTimes = $bookingObj->getAllBookedHistory();
        
            // Periksa apakah terjadi kesalahan
            if (isset($bookedTimes['error'])) {
                // Tanggapan jika terjadi kesalahan
                echo json_encode($bookedTimes);
            } else {
                // Tanggapan jika berhasil
                echo json_encode($bookedTimes);
            }
        }
        elseif (preg_match('/^\/bookingRange\/(\d+)\/(\d+)$/', $endpoint, $matches)) {
            list($tahunAwal, $bulanAwal, $tanggalAwal) = sscanf($matches[1], "%4d%2d%2d");
            $tanggal_formatAwal = "$tahunAwal-$bulanAwal-$tanggalAwal";

            list($tahunAkhir, $bulanAkhir, $tanggalAkhir) = sscanf($matches[2], "%4d%2d%2d");
            $tanggal_formatAkhir = "$tahunAkhir-$bulanAkhir-$tanggalAkhir";

            $bookedTimes = $bookingObj->getBookedHistoryByRange($tanggal_formatAwal, $tanggal_formatAkhir);
        
            // Periksa apakah terjadi kesalahan
            if (isset($bookedTimes['error'])) {
                // Tanggapan jika terjadi kesalahan
                echo json_encode($bookedTimes);
            } else {
                // Tanggapan jika berhasil
                echo json_encode($bookedTimes);
            }
        }
        
        break;
    case 'POST':
        if ($endpoint === '/User') {
            // Add new employee
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $UserObj->addUser($data);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/booking') {
            // Add new employee
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $bookingObj->addBooking($data);
            echo json_encode(['success' => $result]);
        }
        elseif ($endpoint === '/layanan') {
            $data = json_decode(file_get_contents('php://input'), true);
            $gambarInfo = $_FILES['gambar']; 
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
            // Add new employee
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $bookingObj->addBookingAdmin($data);
            echo json_encode(['success' => $result]);
        }
        break;
    case 'PUT':
        if (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {
            // Update employee by ID
            $UserId = $matches[1];
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $UserObj->updateUser($UserId, $data);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/bookingUpdate\/(\d+)$/', $endpoint, $matches)) {
            // Update employee by ID
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
            // Delete employee by ID
            $UserId = $matches[1];
            $result = $UserObj->deleteUser($UserId);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/booking\/(\d+)$/', $endpoint, $matches)) {
            // Delete employee by ID
            $bookingId = $matches[1];
            $result = $bookingObj->deleteBooking($bookingId);
            echo json_encode(['success' => $result]);
        }
        elseif (preg_match('/^\/layanan\/(\d+)$/', $endpoint, $matches)) {
            // Delete employee by ID
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