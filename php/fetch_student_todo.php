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

// Check if the required fields are provided
if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // Perform the SQL query to fetch Course_name and Exam_date
    $query = "SELECT Course.Course_name, Exam.Exam_date
              FROM Course
              JOIN Exam ON Course.Course_id = Exam.Course_id
              WHERE Exam.Exam_id = :exam_id";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':exam_id', $exam_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $returnData = msg(1, 200, 'Course name and Exam date fetched successfully.', $result);
    } else {
        $returnData = msg(0, 404, 'Exam not found.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing exam_id.');
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
 