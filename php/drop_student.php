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
header("Access-Control-Allow-Methods: POST, DELETE"); // Allow both POST and DELETE methods
header("Content-Type: application/json; charset=UTF-8");

// Handle the request to update isapproved, delete student_name, and use student_email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->course_id) && isset($data->student_email)) {
        $course_id = $data->course_id;
        $student_email = $data->student_email;

        try {
            // Query to update isapproved from 0 to 1 for the specified course_id
            $updateQuery = "UPDATE manage_students SET isapproved = 1 WHERE course_id = :course_id And student_email = :student_email";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':student_email', $student_email);
            $stmt->execute();

            // Check if any rows were affected
            $rowsAffected = $stmt->rowCount();

            if ($rowsAffected > 0) {
                // Now, let's delete the student_name for the specified student_email
                $deleteQuery = "DELETE FROM manage_students WHERE isapproved = 1 AND course_id = :course_id AND student_email = :student_email";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bindParam(':course_id', $course_id);
                $stmt->bindParam(':student_email', $student_email);
                $stmt->execute();

                $returnData = msg(1, 200, 'isapproved updated successfully and student_name deleted.');
            } else {
                $returnData = msg(1, 200, 'No records found for the specified course_id and student_email.');
            }
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing course_id or student_email parameters in the request.');
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
