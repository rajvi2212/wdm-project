<?php
// Replace these variables with your actual database credentials
require './classes/database.php';

// Create a connection to the database
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Check if the required fields are provided
if (isset($_GET['validation_token'])) {
    $validation_token = $_GET['validation_token'];
    echo "Validation Token: " . $validation_token; 

    try {
        // Perform the query to retrieve a user with the validation token
        $query = "SELECT * FROM `register` WHERE validation_token = :validation_token";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':validation_token', $validation_token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update the user's email verification status
            $query = "UPDATE `register` SET is_verified = 1 WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            echo "Email verified successfully. You can now log in.";
        } else {
            echo "Invalid or expired verification token.";
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    echo "Token not provided in the URL.";
}

// Close the database connection
$conn = null;
?>
