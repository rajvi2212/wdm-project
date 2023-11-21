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

// Handle the request to fetch records with isapproved = 0
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query to fetch records with isapproved = 0 from the manage_students table
        $query = "SELECT * FROM manage_students WHERE isapproved = 0";

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($records) {
            $returnData = msg(1, 200, 'Records with isapproved = 0 fetched successfully.', ['records' => $records]);
        } else {
            $returnData = msg(1, 200, 'No records found with isapproved = 0.');
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
