<?php
// Specify the path to the PowerPoint file on the server
$file_path = 'axd3080.uta.cloud/wdm_backend/ppt/3rdEd_Chapter.pptx'; // Replace with the actual file path

if (file_exists($file_path)) {
    // Set the appropriate headers to trigger a download
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation'); // Set the content type for PPTX files
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Content-Length: ' . filesize($file_path));

    // Read and output the file content
    readfile($file_path);
    exit;
} else {
    // Handle the case where the file does not exist
    echo "The file does not exist.";
}
?>
