<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Database configuration
require('connection.php');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $affiliation = $_POST['affiliation'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = "user";
    $status = "unverified";

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($email) || empty($username) || empty($password) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }

    // Check if username already exists
    $checkQuery = $conn->prepare("SELECT userID FROM user WHERE username = ?");
    if (!$checkQuery) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit();
    }

    $checkQuery->bind_param("s", $username);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username is already taken.']);
        $checkQuery->close();
        exit();
    }

    $checkQuery->close();

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, affiliation, email, username, password, role, status, created, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database statement preparation failed: ' . $conn->error]);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("ssssssss", $firstname, $lastname, $affiliation, $email, $username, $hashedPassword, $role, $status);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User registered successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
