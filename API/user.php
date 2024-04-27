<?php
class User
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function getAllUser()
    {
        $query = "SELECT userID, username, no_telp, userRole FROM user";
        $result = mysqli_query($this->con, $query);
        $user = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $user[] = $row;
        }
        return $user;
    } 
    public function getAllUserLogin()
    {
        $query = "SELECT * FROM user";
        $result = mysqli_query($this->con, $query);
        $user = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $user[] = $row;
        }
        return $user;
    }
    public function getUserById($UserID)
    {
        $query = "SELECT * FROM user WHERE UserID = $UserID";
        $result = mysqli_query($this->con, $query);
        $user = mysqli_fetch_assoc($result);
        return $user;
    }
    public function addUser($data)
    {
        $username = $data['username'];
        $no_telp = $data['no_telp'];
        $password = md5($data['password']);
        $role = 'pelanggan';
        $query = "INSERT INTO user (username, no_telp, userRole, password) 
                  VALUES ('$username', '$no_telp', '$role', '$password')";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function updateUser($UserID, $data)
    {
        $username = $data['username'];
        $no_telp = $data['no_telp'];
        $password = md5($data['password']);
        $role = 'pelanggan';
        $query = "UPDATE user SET username = '$username', no_telp = '$no_telp', password = '$password' WHERE UserID = $UserID";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteUser($UserID)
    {
        $query = "DELETE FROM user WHERE UserID = $UserID";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}