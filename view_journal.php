<?php
require('connection.php');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $journalID = intval($_GET['id']);

    // Increment view count
    $incrementViews = $conn->prepare("UPDATE journal SET views = views + 1 WHERE journalID = ?");
    $incrementViews->bind_param("i", $journalID);
    $incrementViews->execute();
    $incrementViews->close();

    // Fetch the journal entry
    $stmt = $conn->prepare("SELECT * FROM journal WHERE journalID = ?");
    $stmt->bind_param("i", $journalID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {
        die("Journal not found.");
    }

    $stmt->close();
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-container {
            display: flex;
            gap: 20px;
        }

        .content {
            flex: 3;
        }

        .sidebar {
            flex: 1;
        }

        .stat-box {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1.5rem;
        }

        .abstract-section {
            border-top: 2px solid #eaeaea;
            padding-top: 15px;
            margin-top: 15px;
        }

        .btn-success {
            font-size: 1rem;
            padding: 10px 20px;
        }

        .recommendations {
            list-style-type: none;
            padding: 0;
        }

        .recommendations li {
            margin-bottom: 10px;
        }

        .recommendations a {
            text-decoration: none;
            color: #007bff;
        }

        .recommendations a:hover {
            text-decoration: underline;
        }

        .stats-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stats-section .stat-box {
            flex: 1;
            margin: 0 10px;
        }

        .more-like-this {
            margin-top: 20px;
            border-top: 2px solid #eaeaea;
            padding-top: 15px;
        }

        h5.more-title {
            font-weight: bold;
            color: #333;
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
    <div class="container mt-5">
        <div class="main-container">
            <!-- Content Area -->
            <div class="content">
                <h2 class="text-success"><?php echo htmlspecialchars($row['title']); ?></h2>

                <div class="stats-section">
                    <div class="stat-box">
                        <h3>Views</h3>
                        <p><?php echo htmlspecialchars($row['views']); ?></p>
                    </div>
                    <div class="stat-box">
                        <h3>Downloads</h3>
                        <p>
                            <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) AS dl FROM click WHERE journalID = ? GROUP BY journalID");
                            $stmt->bind_param("i", $journalID);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($rowDl = $result->fetch_assoc()) {
                                echo $rowDl['dl'];
                            } else {
                                echo "0";
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Keywords: <?php echo htmlspecialchars($row['keyword']); ?></h5>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="abstract-section">
                            <h5>Abstract:</h5>
                            <p><?php echo htmlspecialchars($row['abstract']); ?></p>
                        </div>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                        <p class="text-muted">Created: <?php echo htmlspecialchars($row['created']); ?></p>
                        <a href="download_pdf.php?id=<?php echo $row['journalID']; ?>" class="btn btn-success">Download PDF</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="more-like-this">
                    <h5 class="more-title">More Like This</h5>
                    <ul class="recommendations">
                        <li><a href="#">Leveraging AI for Enhanced Power Systems Control</a></li>
                        <li><a href="#">Navigating Deep Reinforcement Learning for Power Systems</a></li>
                        <li><a href="#">Introduction to Smart Grids and AI</a></li>
                        <li><a href="#">Optimization Models for Renewable Energy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
