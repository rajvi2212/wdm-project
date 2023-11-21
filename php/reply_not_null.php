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
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query to fetch all feedbacks where reply_msg is not null
        $fetchQuery = "SELECT * FROM Feedback WHERE reply_msg IS NOT NULL";
        $stmt = $conn->query($fetchQuery);

        if ($stmt) {
            $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $returnData = msg(1, 200, 'Feedbacks with reply_msg retrieved successfully.', ['feedbacks' => $feedbacks]);
        } else {
            $returnData = msg(0, 500, 'Error fetching feedbacks.');
        }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected GET.');
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
