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

// Handle score fetch request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query to fetch all scores from the student_score table
        $query = "SELECT * FROM student_score";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($scores) {
            $returnData = msg(1, 200, 'Scores fetched successfully.', ['scores' => $scores]);
        } else {
            $returnData = msg(1, 200, 'No scores found.');
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
