<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Journal Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-success">Upload Journal Content</h2>
        <form id="journalForm" action="submit_journal.php" method="POST" enctype="multipart/form-data" class="p-4 bg-white rounded shadow-sm">
            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Journal Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Keywords -->
            <div class="mb-3">
                <label for="keyword" class="form-label">Keywords</label>
                <input type="text" class="form-control" id="keyword" name="keyword" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <!-- Abstract -->
            <div class="mb-3">
                <label for="abstract" class="form-label">Abstract</label>
                <textarea class="form-control" id="abstract" name="abstract" rows="3" required></textarea>
            </div>

            <!-- PDF Upload -->
            <div class="mb-3">
                <label for="pdf" class="form-label">Upload PDF</label>
                <input type="file" class="form-control" id="pdf" name="pdf" accept=".pdf" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <!-- Hidden Author Field -->
            <input type="hidden" name="author" value="123"> <!-- Replace with the logged-in user ID -->

            <button type="submit" class="btn btn-success w-100">Upload Journal</button>
        </form>
    </div>
</body>
</html>
