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
header("Access-Control-Allow-Methods: POST"); // Allow only the POST method
header("Content-Type: application/json; charset=UTF-8");

// Handle the request to update instructor_teaching and Course_desc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->Course_id) && isset($data->instructor_teaching) && isset($data->Course_desc)) {
        $Course_id = $data->Course_id;
        $instructor_teaching = $data->instructor_teaching;
        $Course_desc = $data->Course_desc;

        try {
            // Query to update instructor_teaching and Course_desc for the specified Course_id
            $updateQuery = "UPDATE Course SET instructor_teaching = :instructor_teaching, Course_desc = :Course_desc WHERE Course_id = :Course_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':Course_id', $Course_id);
            $stmt->bindParam(':instructor_teaching', $instructor_teaching);
            $stmt->bindParam(':Course_desc', $Course_desc);
            $stmt->execute();

            // Check if any rows were affected
            $rowsAffected = $stmt->rowCount();

            if ($rowsAffected > 0) {
                $returnData = msg(1, 200, 'instructor_teaching and Course_desc updated successfully.');
            } else {
                $returnData = msg(1, 200, 'No records found for the specified Course_id.');
            }
        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
        }
    } else {
        $returnData = msg(0, 400, 'Missing Course_id, instructor_teaching, or Course_desc parameter in the request.');
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
