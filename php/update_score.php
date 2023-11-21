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
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");

// Check if the required fields are provided
if (
    isset($data->student_email) &&
    isset($data->instructor_mail) &&
    isset($data->score)
) {
    $student_email = $data->student_email;
    $instructor_mail = $data->instructor_mail;
    $new_score = $data->score;

    // Perform the UPDATE operation
    try {
        $query = "UPDATE `student_score` SET `score` = :new_score WHERE `student_email` = :student_email AND `instructor_mail` = :instructor_mail";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':new_score', $new_score, PDO::PARAM_INT);
        $stmt->bindValue(':student_email', $student_email, PDO::PARAM_STR);
        $stmt->bindValue(':instructor_mail', $instructor_mail, PDO::PARAM_STR);
        $stmt->execute();

        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            $returnData = msg(1, 200, 'Score updated successfully.');
        } else {
            $returnData = msg(0, 404, 'Record not found. Score update failed.');
        }
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
