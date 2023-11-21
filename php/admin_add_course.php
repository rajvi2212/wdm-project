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

// Handle course creation request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the required fields are provided
    $data = json_decode(file_get_contents("php://input"));
    
    if (
        isset($data->course_desc) &&
        isset($data->course_name) &&
        isset($data->instructor_teaching) &&
        isset($data->course_objectives) &&
        isset($data->program)
    ) {
        $course_desc = $data->course_desc;
        $course_name = $data->course_name;
        $instructor_teaching = $data->instructor_teaching;
        $course_objectives = $data->course_objectives;
        $program = $data->program;

        // You may perform additional validation and sanitation of the data here

        try {
            // Perform the query to insert the new course
            $query = "INSERT INTO `Course` (course_desc, course_name, instructor_teaching, course_objectives, program) VALUES (:course_desc, :course_name, :instructor_teaching, :course_objectives, :program)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':course_desc', $course_desc, PDO::PARAM_STR);
            $stmt->bindValue(':course_name', $course_name, PDO::PARAM_STR);
            $stmt->bindValue(':instructor_teaching', $instructor_teaching, PDO::PARAM_STR);
            $stmt->bindValue(':course_objectives', $course_objectives, PDO::PARAM_STR);
            $stmt->bindValue(':program', $program, PDO::PARAM_STR);
            $stmt->execute();

            $returnData = msg(1, 201, 'Course added successfully.');
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Invalid request. Missing required fields.');
    }
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
