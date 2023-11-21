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

// Perform the query to retrieve all students added in manage_students
try {
    $query = "SELECT * FROM `manage_students`";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if students were found
    if ($students) {
        // Students found in manage_students table
        $returnData = msg(1, 200, 'Students found.', ['students' => $students]);
    } else {
        // No students found
        $returnData = msg(0, 404, 'No students found.');
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
