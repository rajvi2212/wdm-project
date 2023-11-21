<?php
// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Initialize the return data
$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST,GET");
header("Content-Type: application/json; charset=UTF-8");

// Assuming you receive the sender's name, receiver's name, and message from your frontend


try {
    // Get the data from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract data
    $sender = $data['sender'];
    $receiver = $data['receiver'];
    $message = $data['message'];
    // Prepare the SQL statement
    $query = "INSERT INTO `messages` (sender, receiver, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$sender, $receiver, $message]);

    echo json_encode($data);

    // Return success response
    $returnData = msg(1, 200, 'Message sent successfully.');
} catch (PDOException $e) {
    // Return error response
    $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
}

// Close the database connection
$conn = null;

// Return the JSON response
echo json_encode($returnData);

function msg($success, $status, $message, $extra = []) {
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}
?>
