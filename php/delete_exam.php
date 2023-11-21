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
header("Access-Control-Allow-Methods: POST, DELETE, GET");
header("Content-Type: application/json; charset=UTF-8");

// Check if the required fields are provided
if (isset($data->Exam_id)) {
    $Exam_id = $data->Exam_id;

    // Perform the DELETE operation
    try {
        $query = "DELETE from `Exam` WHERE `Exam_id` = :Exam_id";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':Exam_id', $Exam_id, PDO::PARAM_STR);
        $stmt->execute();

        $returnData = msg(1, 200, 'Exam deleted successfully.');
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing Exam_id.');
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
