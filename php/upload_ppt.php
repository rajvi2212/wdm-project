<?php

$uploadDirectory = 'axd3080.uta.cloud/wdm_backend/ppt/'; // The directory where files will be stored
$maxFileSize = 5 * 1024 * 1024; // 5 MB 
$allowedFileTypes = ['pdf', 'ppt', 'pptx']; // Allowed file extensions

$returnData = [];

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the file was uploaded without errors
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Get file information
        $uploadedFile = $_FILES['file'];
        $fileName = $uploadedFile['name'];
        $fileSize = $uploadedFile['size'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check if the file type is allowed
        if (in_array($fileType, $allowedFileTypes)) {
            // Check if the file size is within the limit
            if ($fileSize <= $maxFileSize) {
                // Generate a unique file name (you may choose your own method)
                $newFileName = uniqid() . '.' . $fileType;

                // Move the uploaded file to the upload directory
                if (move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $newFileName)) {
                    $returnData = ['success' => true, 'message' => 'File uploaded successfully.'];
                } else {
                    $returnData = ['success' => false, 'message' => 'Failed to move the uploaded file.'];
                }
            } else {
                $returnData = ['success' => false, 'message' => 'File size exceeds the maximum limit.'];
            }
        } else {
            $returnData = ['success' => false, 'message' => 'Invalid file type. Allowed file types: PDF, PPT, PPTX.'];
        }
    } else {
        $returnData = ['success' => false, 'message' => 'File upload error.'];
    }
} else {
    $returnData = ['success' => false, 'message' => 'Invalid request method. Use POST.'];
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($returnData);
?>
