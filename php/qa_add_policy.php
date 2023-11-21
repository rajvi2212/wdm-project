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

    if (isset($data->policy_name) && isset($data->policy_desc)) {
        $policy_name = $data->policy_name;
        $policy_desc = $data->policy_desc;

        try {
            // Query to insert a new policy into the manage_policy table
            $insertQuery = "INSERT INTO manage_policy (policy_name, policy_desc) VALUES (:policy_name, :policy_desc)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':policy_name', $policy_name);
            $stmt->bindParam(':policy_desc', $policy_desc);
            $stmt->execute();

            $returnData = msg(1, 201, 'Policy added successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing policy_name or policy_desc parameter in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected POST.');
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
    