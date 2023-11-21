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
if (isset($data->email) && isset($data->password)) {
    $email = $data->email;
    $password = $data->password;

    // You may perform additional validation and sanitation of the data here

    // Perform the login process and verify the user credentials in the database
    try {
        $query = "SELECT * FROM `register` WHERE `email` = :email AND `password` = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user['is_approved'] == "1") {
                // User found and approved by admin, return the user role
                $returnData = msg(1, 200, 'Login successful.', ['role' => $user['role']]);
            } else {
                // User found but not approved by admin
                $returnData = msg(0, 401, 'User not approved by admin.');
            }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing email or password.');
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
