<?php
// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FROM REQUEST
// $data = json_decode(file_get_contents("php://input"));
$returnData = [];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");


// Check if the required field (Course_id) is provided
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// if (isset($data->Course_id)) {
//     $Course_id = $data->Course_id;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // You may perform additional validation and sanitation of the data here

    // Perform the query to fetch exams based on matching Course_id
    try {
        $query = "SELECT * FROM `Exam` AS e JOIN `manage_students` AS ms ON e.Course_id = ms.course_id";

        $stmt = $conn->prepare($query);
        // $stmt->bindValue(':Course_id', $Course_id, PDO::PARAM_INT);
        $stmt->execute();
        $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        

        if ($exams) {
            $returnData = msg(1, 200, 'Exams fetched successfully.', ['exams' => $exams]);
        } else {
            $returnData = msg(0, 404, 'No exams found for the provided Course_id.');
        }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request. Missing Course_id.');
}
// }
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
