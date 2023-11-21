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
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are provided
if (	
    isset($data->Course_id) &&
    isset($data->instructor_mail) &&
    isset($data->Exam_date) &&
    isset($data->Max_score)
) {
    $Course_id = $data->Course_id;
    $instructor_mail = $data->instructor_mail;
    $Exam_date = $data->Exam_date;
    $Max_score = $data->Max_score;

    // You may perform additional validation and sanitation of the data here

    // Perform the insert query to add the exam to the Exam table
    try {
        $query = "INSERT INTO `Exam` (Course_id, instructor_mail, Exam_date, Max_score) VALUES (:Course_id, :instructor_mail, :Exam_date, :Max_score)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':Course_id', $Course_id, PDO::PARAM_STR);
        $stmt->bindValue(':instructor_mail', $instructor_mail, PDO::PARAM_STR);
        $stmt->bindValue(':Exam_date', $Exam_date, PDO::PARAM_STR);
        $stmt->bindValue(':Max_score', $Max_score, PDO::PARAM_INT);
        $stmt->execute();
        $returnData = msg(1, 201, 'Exam added to the table successfully.');
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
