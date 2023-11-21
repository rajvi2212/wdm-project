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
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

// Handle course_name and instructor_mail fetch request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Email to search for
        $email = "vidhidesai.ict17@gmail.com";

        // Query to fetch course_name and instructor_mail for the given email
        $query = "SELECT * FROM manage_students WHERE student_email = :email";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $courseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($courseData) {
            $returnData = msg(1, 200, 'Course data fetched successfully.', ['course_data' => $courseData]);
        } else {
            $returnData = msg(1, 200, 'No course data found for the provided email.');
        }
    } catch (PDOException $e) {
        $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
    }
} else {
    $returnData = msg(0, 400, 'Invalid request method. Expected GET.');
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
