<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                        <div id="responseMessage" class="mt-3"></div>
                    </div>
                    <center>
                    <a href="index.php">Register here</a>
                    </center><br>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const loginForm = document.getElementById('loginForm');
        const responseMessage = document.getElementById('responseMessage');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(loginForm);
            try {
                const response = await axios.post('authenticate.php', formData);
                const data = response.data;

                if (data.success) {
                    responseMessage.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    // Redirect or additional actions based on role
                    console.log('Role:', data.role);
                    window.location.href = "display_journal.php";
                } else {
                    responseMessage.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            } catch (error) {
                responseMessage.innerHTML = `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>
