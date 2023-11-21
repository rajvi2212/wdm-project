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

    if (isset($data->policy_id) && isset($data->policy_name) && isset($data->policy_desc)) {
        $policy_id = $data->policy_id;
        $policy_name = $data->policy_name;
        $policy_desc = $data->policy_desc;

        try {
            // Query to update the policy_name and policy_desc for a specific policy
            $updateQuery = "UPDATE manage_policy SET policy_name = :policy_name, policy_desc = :policy_desc WHERE policy_id = :policy_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':policy_name', $policy_name);
            $stmt->bindParam(':policy_desc', $policy_desc);
            $stmt->bindParam(':policy_id', $policy_id);
            $stmt->execute();

            $returnData = msg(1, 200, 'Policy updated successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing policy_id, policy_name, or policy_desc parameter in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected PUT.');
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
