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
    // Collect login data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate required fields
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT password, role, status FROM user WHERE username = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database statement preparation failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the username exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword, $role, $status);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            if ($status !== "verified") {
                echo json_encode(['success' => false, 'message' => 'Account is not verified.']);
            } else {
              //  echo json_encode(['success' => true, 'message' => 'Login successful!', 'role' => $role, 'redirect' => 'index.php']);
              session_start();

              // Store the username and role in the session
              $_SESSION['userID'] = $username;
              $_SESSION['role'] = $role;
              echo json_encode(['success' => true, 'message' => 'Login successful!', 'redirect' => 'display_journal.php']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username not found.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
