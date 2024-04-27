<?php
class Order
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function getAllOrder()
    {
        $query = "SELECT * FROM order_layanan";
        $result = mysqli_query($this->con, $query);
        $order = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $order[] = $row;
        }
        return $order;
    } 
    public function getOrderByIdBooking($id)
    {
        $query = "SELECT * FROM order_layanan WHERE id_booking = $id";
        $result = mysqli_query($this->con, $query);
        $order = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $order[] = $row;
        }
        return $order;
    }
    
}