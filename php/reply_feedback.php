<?php
// Include your database configuration
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Initialize the return data
$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->Feedback_id) && isset($data->reply_msg)) {
        $Feedback_id = $data->Feedback_id;
        $reply_msg = $data->reply_msg;

        try {
            // Query to update the reply_msg for a specific feedback
            $updateQuery = "UPDATE Feedback SET reply_msg = :reply_msg WHERE Feedback_id = :Feedback_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':reply_msg', $reply_msg);
            $stmt->bindParam(':Feedback_id', $Feedback_id);
            $stmt->execute();

            $returnData = msg(1, 200, 'Reply message updated successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing Feedback_id or reply_msg parameter in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected PUT.');
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
