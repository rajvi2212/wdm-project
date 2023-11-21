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

// Handle the request to add a guideline
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->progcord_email) && isset($data->guideline)) {
        $progcord_email = $data->progcord_email;
        $guideline = $data->guideline;

        try {
            // Query to insert a new guideline into the guidelines table
            $insertQuery = "INSERT INTO guidelines (progcord_email, guideline) VALUES (:progcord_email, :guideline)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':progcord_email', $progcord_email);
            $stmt->bindParam(':guideline', $guideline);
            $stmt->execute();

            $returnData = msg(1, 201, 'Guideline added successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing progcord_email or guideline parameter in the request.');
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
