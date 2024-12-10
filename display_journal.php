<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if no session exists
    header("Location: login.php");
    exit();
}

// Database connection
require('connection.php');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the userID from the session
$userID = $_SESSION['userID'];

// Handle search functionality
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Check if a search query is provided
$searchWildcard = '%' . $searchQuery . '%';

if (empty($searchQuery)) {
    // No search query provided, fetch all journal entries
    $sql = "SELECT journalID, title, keyword, description, abstract, status, created, author 
            FROM journal";
    $stmt = $conn->prepare($sql);
} else {
    // Search query provided, filter based on search term
    $sql = "SELECT journalID, title, keyword, description, abstract, status, created, author 
            FROM journal 
            WHERE title LIKE ? OR keyword LIKE ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
}

if (!$stmt->execute()) {
    die("Query execution failed: " . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="display_journal.php">Journal Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="journal-form.php">Submit Journal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h2 class="text-center text-success mb-4">My Journal Entries</h2>

        <!-- Search Bar -->
        <form class="mb-4" method="GET" action="display_journal.php">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by title or keyword" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="btn btn-success">Search</button>
            </div>
        </form>

        <!-- Journal Entries -->
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Keywords: <?php echo htmlspecialchars($row['keyword']); ?></h6>
                                <p class="card-text">
                                    <?php echo htmlspecialchars(substr($row['description'], 0, 100)); ?>...
                                </p>
                                <small class="text-muted">Created: <?php echo htmlspecialchars($row['created']); ?></small>
                                <hr>
                                <a href="view_journal.php?id=<?php echo $row['journalID']; ?>" class="btn btn-sm btn-info">View Details</a>
                                <a href="download_pdf.php?id=<?php echo $row['journalID']; ?>" class="btn btn-sm btn-success">Download PDF</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        No journal entries found. Try a different search or <a href="journal-form.php" class="alert-link">submit a new journal</a>.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
