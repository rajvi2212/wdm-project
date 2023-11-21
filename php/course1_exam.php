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
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

// Handle exam fetch request with Course_id = 1
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query to fetch exams with Course_id = 1 from the Exam table
        $query = "SELECT * FROM Exam WHERE Course_id = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($exams) {
            $returnData = msg(1, 200, 'Exams with Course_id = 1 fetched successfully.', ['exams' => $exams]);
        } else {
            $returnData = msg(1, 200, 'No exams found with Course_id = 1.');
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
