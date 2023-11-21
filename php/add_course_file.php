<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'course_material' field is present
    if (isset($_POST['course_material'])) {
        $course_material = $_POST['course_material'];

        // Check if a file was uploaded
        if (isset($_POST['courseFile'])) {
            $courseFile = $_POST['courseFile'];

            // Specify the local directory where you want to save the file
            $localDirectory = '/Users/vidhidesai/Documents/my-react-app/material'; // Replace with the actual local directory path

            // Generate a unique filename for the uploaded file
            $filename = uniqid() . '_' . $file['name'];

            // Define the full local path where the file should be saved
            $localPath = $localDirectory . $filename;

            if (move_uploaded_file($file['tmp_name'], $localPath)) {
                // The file was successfully uploaded to the local directory

                // Insert the data into your database
                // $sql = "INSERT INTO CourseContent (course_material, file_name) VALUES (?, ?)";
                // $stmt = $conn->prepare($sql);

                // if ($stmt) {
                //     $stmt->bind_param("ss", $course_material, $filename);
                //     $stmt->execute();

                //     // Close the prepared statement
                //     $stmt->close();
                // } else {
                //     // Handle SQL query preparation error
                //     // You can add error handling code here if needed
                // }
                $sql = "INSERT INTO CourseContent (course_material, file_name) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("ss", $course_material, $courseFile); // Use $courseFile for file name
                    $stmt->execute();

    // Close the prepared statement
                    $stmt->close();
                    } else {
    // Handle SQL query preparation error
                    handleDatabaseError($conn);
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'File uploaded and data stored successfully.',
                ]);
            } else {
                // Error occurred while moving the file to the local directory
                echo json_encode([
                    'success' => false,
                    'message' => 'Error uploading the file to the local directory.',
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No file was uploaded.',
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required field: course_material.',
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Expected POST.',
    ]);
}

// Close the database connection
$conn = null;
?>
