<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization ");
header("Access-Control-Allow-Methods:  *");

include 'dbconnect.php';
$objDb = new dbconnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "PUT") {
    $user = json_decode(file_get_contents('php://input'));
    
    $sql = "SELECT * FROM User WHERE Email = :Email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':Email', $user->Email);
    $stmt->execute();
    $ret = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ret == null) {
        $response = ['status' => 2, 'message' => 'User does not Exist'];
    } else {
        $update = "UPDATE User SET Password= :Password WHERE Email= :Email";
        $stmt1 = $conn->prepare($update);
        $stmt1->bindParam(':Password', $user->Password);
        $stmt1->bindParam(':Email', $user->Email);
    
        if ($stmt1->execute()) {
           $response = ['status' => 1, 'message' => 'Password updated successfully.'];
        } 
        else 
        {
            $response = ['status' => 0, 'message' => 'Failed to update password.'];
        }
    }
    echo json_encode($response);
}
