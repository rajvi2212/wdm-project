<?php
require './classes/database.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->course_id) && isset($data->student_email)) {
        $course_id = $data->course_id;
        $student_email = $data->student_email;

        if (!empty($course_id) && !empty($student_email)) {
            try {
                $query = "UPDATE manage_students
                          SET isapproved = 0
                          WHERE course_id = :course_id AND student_email = :student_email";

                $stmt = $conn->prepare($query);
                $stmt->bindParam(':course_id', $course_id);
                $stmt->bindParam(':student_email', $student_email);
                $stmt->execute();

                $rowCount = $stmt->rowCount();

                if ($rowCount > 0) {
                    $returnData = msg(1, 200, 'Course drop request processed successfully for course with course_id: ' . $course_id);
                } else {
                    $returnData = msg(0, 200, 'No records were updated. Course drop request might be invalid.');
                }
            } catch (PDOException $e) {
                $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
            }
        } else {
            $returnData = msg(0, 400, 'Missing course_id or student_email parameter in the request.');
        }
    } else {
        $returnData = msg(0, 400, 'Missing or invalid parameters in the request.');
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected POST.');
}

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
