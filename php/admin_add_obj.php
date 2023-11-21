<?php
// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FROM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// Check if the required fields are provided
if (isset($data->program) && isset($data->program_objective)) {
    $program = $data->program;
    $program_objective = $data->program_objective;

    // You may perform additional validation and sanitation of the data here

    // Perform the insert query to add program objectives to the ProgramObjectives table
    try {
        $query = "INSERT INTO `ProgramObjectives` (program, program_objective) VALUES (:program, :program_objective)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':program', $program, PDO::PARAM_STR);
        $stmt->bindValue(':program_objective', $program_objective, PDO::PARAM_STR);
        $stmt->execute();
        $returnData = msg(1, 201, 'Program objectives added successfully.');
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing required fields.');
}

// Close the database connection
$conn = null;

echo json_encode($returnData);

function msg($success, $status, $message, $extra = []) {
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}
?>
