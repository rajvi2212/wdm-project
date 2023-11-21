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
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are provided
if (
    $data->PE_id && $data->coordinator_name && $data->coordinator_mail && $data->co_program
) {
    $PE_id = $data->PE_id;
    $coordinator_name = $data->coordinator_name;
    $coordinator_mail = $data->coordinator_mail;
    $co_program = $data->co_program;

    // You may perform additional validation and sanitation of the data here

    // Insert the coordinator into the ProgCoordinator table
    try {
        $query = "INSERT INTO `ProgCoordinator` (PE_id, coordinator_name, coordinator_mail, co_program) 
                  VALUES (:PE_id, :coordinator_name, :coordinator_mail, :co_program)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':PE_id', $PE_id, PDO::PARAM_INT);
        $stmt->bindValue(':coordinator_name', $coordinator_name, PDO::PARAM_STR);
        $stmt->bindValue(':coordinator_mail', $coordinator_mail, PDO::PARAM_STR);
        $stmt->bindValue(':co_program', $co_program, PDO::PARAM_STR);
        $stmt->execute();

        $returnData = msg(1, 201, 'Coordinator added successfully.');
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing required fields.');
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
