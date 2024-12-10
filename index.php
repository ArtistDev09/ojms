<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f3f6f5;
        }
        .form-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #005f3a;
            border-color: #005f3a;
        }
        .btn-primary:hover {
            background-color: #00724b;
            border-color: #00724b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">User Registration</h2>
            <form id="userForm">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
                <div class="mb-3">
                    <label for="affiliation" class="form-label">Affiliation</label>
                    <input type="text" class="form-control" id="affiliation" name="affiliation" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
       const form = document.getElementById('userForm');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const formData = new FormData(form);

    try {
        const response = await fetch('submit.php', {
            method: 'POST',
            body: formData
        });

        const responseText = await response.text(); // Read as plain text for debugging
        try {
            const result = JSON.parse(responseText); // Attempt to parse as JSON
            if (result.success) {
                alert(result.message);
                form.reset(); // Clear the form
                window.location.href = "login.php";

            } else {
                alert(`Error: ${result.message}`);
            }
        } catch (error) {
            console.error('Invalid JSON response:', responseText);
            alert('An unexpected error occurred.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to submit the form.');
    }
});

    </script>
</body>
</html>
