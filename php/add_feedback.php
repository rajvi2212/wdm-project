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

// Handle the request to add feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->sender) && isset($data->Message)) {
        $sender = $data->sender;
        $message = $data->Message;

        try {
            // Query to insert a new feedback into the Feedback table
            $insertQuery = "INSERT INTO Feedback (sender, Message) VALUES (:sender, :Message)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':sender', $sender);
            $stmt->bindParam(':Message', $message);
            $stmt->execute();

            $returnData = msg(1, 201, 'Feedback added successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing sender or Message parameter in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected POST.');
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
