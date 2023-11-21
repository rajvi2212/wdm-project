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

// Handle student score addition request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the required fields are provided
    $data = json_decode(file_get_contents("php://input"));

    if (
        isset($data->student_email) &&
        isset($data->instructor_mail) &&
        isset($data->Date) &&
        isset($data->score) &&
        isset($data->max_score)
    ) {
        $student_email = $data->student_email;
        $instructor_mail = $data->instructor_mail;
        $Date = $data->Date;
        $score = $data->score;
        $max_score = $data->max_score;

        // You may perform additional validation and sanitation of the data here

        try {
            // Perform the query to insert the student score
            $query = "INSERT INTO `student_score` (student_email, instructor_mail, Date, score, max_score) VALUES (:student_email, :instructor_mail, :Date, :score, :max_score)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':student_email', $student_email, PDO::PARAM_STR);
            $stmt->bindValue(':instructor_mail', $instructor_mail, PDO::PARAM_STR);
            $stmt->bindValue(':Date', $Date, PDO::PARAM_STR);
            $stmt->bindValue(':score', $score, PDO::PARAM_INT);
            $stmt->bindValue(':max_score', $max_score, PDO::PARAM_INT);
            $stmt->execute();

            $returnData = msg(1, 201, 'Student score added successfully.');
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
