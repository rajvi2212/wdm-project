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

// Handle coordinator retrieval request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Perform the query to fetch all coordinators from the ProgCoordinator table
        $query = "SELECT * FROM `ProgCoordinator`";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $coordinators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($coordinators) {
            $returnData = msg(1, 200, 'Coordinators retrieved successfully.', ['coordinators' => $coordinators]);
        } else {
            $returnData = msg(0, 404, 'No coordinators found in the ProgCoordinator table.');
        }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
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
