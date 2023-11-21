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

// Perform the query to retrieve users with is_approval = 0
try {
    $query = "SELECT * FROM `register`";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if users were found
    if ($users) {
        // User(s) found with is_approval = 0
        $returnData = msg(1, 200, 'Users with is_approved = 0 found.', ['users' => $users]);
    } else {
        // No user found with is_approval = 0
        $returnData = msg(0, 404, 'No users with is_approved = 0 found.');
    }
} catch (PDOException $e) {
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
