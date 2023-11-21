<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight requests (OPTIONS)
    header("HTTP/1.1 200 OK");
    exit();
}

function msg($success, $status, $message, $extra = []) {
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ], $extra);
}

require './classes/database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// Include PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Create a new PHPMailer instance
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// GET DATA FROM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

$f_name = $data->columns->f_name;
$l_name = $data->columns->l_name;
$role = $data->columns->role;
$email = $data->columns->email;
$password = $data->columns->password;

// Generate a unique validation token
$validation_token = md5(uniqid());

// IF REQUEST METHOD IS NOT POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $returnData = msg(0, 404, 'Page Not Found!');
} else {
    // Check if the required parameters are provided
    if (!isset($data->tableName) || !isset($data->columns)) {
        $returnData = msg(0, 400, 'Invalid request. Missing table name or column data.');
    } else {
        $tableName = trim($data->tableName);
        $columns = $data->columns;

        // Add a new column is_approved and set its value based on the role
        if ($columns->role === 'admin') {
            $columns->is_approved = 1;
        } else {
            $columns->is_approved = 0;
        }

        // Add a new column validation_token and set its value
        $columns->validation_token = $validation_token;

        // Create the INSERT query dynamically
        $insert_query = "INSERT INTO `$tableName` (";
        $placeholders = '';
        $columnNames = [];

        foreach ($columns as $column => $value) {
            $columnNames[] = $column;
            $placeholders .= ':' . $column . ',';
        }

        $insert_query .= implode(',', $columnNames) . ') VALUES (' . rtrim($placeholders, ',') . ')';
        $insert_stmt = $conn->prepare($insert_query);

        // DATA BINDING
        foreach ($columns as $column => $value) {
            $insert_stmt->bindValue(':' . $column, trim($value), PDO::PARAM_STR);
        }

        try {
            $insert_stmt->execute();
            $returnData = msg(1, 201, 'Record inserted successfully.');

            $mail = new PHPMailer();

            // Set SMTP settings (replace these with your actual SMTP credentials)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vidhi.desai788@gmail.com';
            $mail->Password = 'vtxwfxpmmykavguo';
            $mail->SMTPSecure = 'tls'; // Use 'tls' or 'ssl' based on your server configuration
            $mail->Port = 587; // Use the appropriate SMTP port

            // Set the sender (your email address)
            $mail->setFrom('vidhi.desai788@gmail.com', 'GradEdge Application');

            // Add the recipient's email address
            $mail->addAddress($email, 'Recipient Name');

            // Set the email subject and body
            $mail->Subject = 'GradEdge Registration Email';
            $mail->isHTML(true);

            // Construct the validation link with the token
 // Construct the validation link with the token
$validation_link = 'http://axd3080.uta.cloud/wdm_backend/php/validate.php?token=' . $validation_token;
$mail->Body = 'Click <a href="' . $validation_link . '">here</a> to validate your email.';

            // Send the email
            try {
                $mail->send();
                echo 'Email sent successfully.';
            } catch (Exception $e) {
                echo 'Failed to send email: ' . $mail->ErrorInfo;
            }

        } catch (PDOException $e) {
            $returnData = msg(0, 500, 'Database error: ' . $e->getMessage());
            echo json_encode(['error' => 'Email could not be sent.']);
        }
    }
}

echo json_encode($returnData);
