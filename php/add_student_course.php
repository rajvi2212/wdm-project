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
if (
    isset($data->student_name) &&
    isset($data->student_email) &&
    isset($data->course_name) &&
    isset($data->program_name)
) {
    $student_name = $data->student_name;
    $student_email = $data->student_email;
    $course_name = $data->course_name;
    $program_name = $data->program_name;

    // You may perform additional validation and sanitation of the data here

    // Perform the select query to fetch course_id based on course_name
    try {
        $query = "SELECT course_id FROM `Course` WHERE course_name = :course_name";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':course_name', $course_name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Course ID found
            $courseId = $result['course_id'];

            // INSERT query to add a student to the course
            $insertQuery = "INSERT INTO `manage_students` (course_id, student_name, student_email, course_name, program_name) 
                            VALUES (:course_id, :student_name, :student_email, :course_name, :program_name)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
            $insertStmt->bindValue(':student_name', $student_name, PDO::PARAM_STR);
            $insertStmt->bindValue(':student_email', $student_email, PDO::PARAM_STR);
            $insertStmt->bindValue(':course_name', $course_name, PDO::PARAM_STR);
            $insertStmt->bindValue(':program_name', $program_name, PDO::PARAM_STR);
            $insertStmt->execute();

            $returnData = msg(1, 201, 'Student added to the course successfully.');
        } else {
            // Course not found
            $returnData = msg(0, 404, 'Course not found.');
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