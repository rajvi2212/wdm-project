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

try {
    // Perform the query to retrieve all programs from the ProgramObjectives table
    $query = "SELECT * FROM `ProgramObjectives`";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if programs were found
    if ($programs) {
        // Programs found
        $returnData = msg(1, 200, 'Programs retrieved successfully.', ['programs' => $programs]);
    } else {
        // No programs found
        $returnData = msg(0, 404, 'No programs found in the database.');
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
