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

// Handle the request to fetch all feedbacks
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query to fetch all feedbacks from the Feedback table
        $query = "SELECT * FROM Feedback";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($feedbacks) {
            $returnData = msg(1, 200, 'Feedbacks fetched successfully.', ['feedbacks' => $feedbacks]);
        } else {
            $returnData = msg(1, 200, 'No feedbacks found.');
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
