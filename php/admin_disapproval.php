<?php
// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FROM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// Check if the required fields are provided
if (isset($data->email) && ($data->action)) {
    $email = $data->email;
    $action = $data->action;

    // You may perform additional validation and sanitation of the data here

    // Disapprove the user by changing `is_approved` to 0
    try {
        $query = "UPDATE `register` SET `is_approved` = '0' WHERE `email` = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $returnData = msg(1, 200, 'User disapproved successfully.');
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing email.');
}

// Close the database connection
$conn = null;

echo json_encode($returnData);

function msg($success, $status, $message, $extra = []) {
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}
?>
