<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require('connection.php');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $title = $_POST['title'];
    $keyword = $_POST['keyword'];
    $description = $_POST['description'];
    $abstract = $_POST['abstract'];
    $status = $_POST['status'];
    $author = $_POST['author'];

    // Validate PDF upload
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        // Get PDF file content
        $fileTmpPath = $_FILES['pdf']['tmp_name'];
        $fileContent = file_get_contents($fileTmpPath); // Convert to blob for database storage

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO journal (title, keyword, description, abstract, status, pdf, author, created, updated) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        if (!$stmt) {
            die("Database statement preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ssssssi", $title, $keyword, $description, $abstract, $status, $fileContent, $author);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Journal content uploaded successfully!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Database error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger text-center'>Error uploading PDF: " . $_FILES['pdf']['error'] . "</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center'>Invalid request method.</div>";
}

$conn->close();
?>