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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->policy_id)) {
        $policy_id = $data->policy_id;

        try {
            // Query to delete a policy from the manage_policy table
            $deleteQuery = "DELETE FROM manage_policy WHERE policy_id = :policy_id";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bindParam(':policy_id', $policy_id);
            $stmt->execute();

            $returnData = msg(1, 200, 'Policy deleted successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing policy_id parameter in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected DELETE.');
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
