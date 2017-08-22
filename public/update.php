<?php

$servername = "localhost";
$username = "adminn";
$password = "C5kgXx((@@";
$dbname = "jackpot";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$transID = $_POST['userOrderId'];
//$transID = 'AjPVJuFGsV9CTbC6fpyP';
$sql = "SELECT * FROM g2a WHERE orderID='$transID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $buyer = $row['buyer'];
        $coins = $row['coins'];
        $status = $row['status'];
    }
}
if($status == 0) {
    if($_POST['status'] == 'pending') {
        $sql = "UPDATE g2a SET status='1' WHERE orderID='$transID'";
        $conn->query($sql);
        echo 'done';
    }
}
if ($status == 1) {
    if($_POST['status'] == 'complete') {
        $sql = "UPDATE g2a SET status='2' WHERE orderID='$transID'";
        $conn->query($sql);
        $sqlo = "SELECT * FROM users WHERE steamId64='$buyer'";
        $result = $conn->query($sqlo);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ucoins = $row['coins'];
                $ucoins = $ucoins + $coins;
                $sqlr = "UPDATE users SET coins='$ucoins' WHERE steamId64='$buyer'";
                $conn->query($sqlr);
            }
        }
    }
    if ($_POST['status'] == 'rejected') {
        $sql = "UPDATE g2a SET status='3' WHERE orderID='$transID'";
        $conn->query($sql);
        echo 'done';
    }
}

$conn->close();


$file = 'people.txt';
$current = file_get_contents($file);
$current .= json_encode($_POST);
file_put_contents($file, $current);