<?php
$rettArr = [];

// Create connection
$servername = "localhost";
$username = "adminn";
$password = "C5kgXx((@@";
$dbname = "jackpot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    $rettArr['error'] = true;
    echo json_encode($rettArr);
}




require_once 'SimPay.class.php';


function pre($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

define('API_KEY', 'a954f630');
define('API_SECRET', '92b318d51bfdefc7e750f23087c6254e');
define('API_VERSION', '1');

try {

    $api = new SimPay(API_KEY, API_SECRET, API_VERSION);
    $api->getStatus(array(
        'service_id' => '1240',        // identyfikator usl�ugi premium sms
        'number' => $_POST['number'],      // numer na ktory wyslano sms
        'code' => $_POST['code'],    // kod wprowadzony przez klienta
    ));

    if ($api->check()) {
        $buyer = $_POST['steamid'];
        $numbo = $_POST['number'];

        $sql = "SELECT coins FROM SMSCODES WHERE numbo='$numbo'";
        $user = "SELECT * FROM users WHERE steamId64='$buyer'";
        $result = $conn->query($sql);
        $result_user = $conn->query($user);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
               $coins = $row["coins"];
            }
        } else {
            $rettArr['error'] = true;
            echo json_encode($rettArr);
        }
        if ($result_user->num_rows > 0) {
            while($row = $result_user->fetch_assoc()) {
                $user_coins = $row["coins"];
            }
        } else {
            $rettArr['error'] = true;
            echo json_encode($rettArr);
        }

        $user_coins = $user_coins + $coins;
        $update = "UPDATE users SET coins='$user_coins' WHERE steamId64='$buyer'";
        $updateUser = $conn->query($update);


        if($updateUser == TRUE) {
            $rettArr['buyed'] = true;
            $rettArr['error'] = false;
            $rettArr['coins'] = $coins;
            $sql = "INSERT INTO SIMPAY SET buyer='$buyer', coins='$coins'";
            $conn->query($sql);
            echo json_encode($rettArr);
        } else {
            $rettArr['error'] = true;
            echo json_encode($rettArr);
        }
        $conn->close();


    } else if ($api->error()) {
        $rettArr['buyed'] = false;
        echo json_encode($rettArr);
    } else {
        print_r($api->showStatus());
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}