<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .welcome-container {
            margin-top: 100px;
            text-align: center;
        }

        .card {
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 welcome-container">
                <div class="card">
                    <div class="card-header">
                        <h3>Welcome,
                            <?php
                            if (isset($username)) {
                                echo htmlspecialchars($username);
                            } else {
                                echo "Event Management System";
                            }
                            ?>
                        </h3>
                    </div>
                    <?php
                    if (!isset($username)) : ?>
                        <div class="card-body">
                            <p class="lead">If you have an account, please login to continue.</p>
                            <div class="d-grid gap-2">
                                <a href="auth/login.php" class="btn btn-custom">Login</a>
                            </div>
                            <p class="mt-3">Don't have an account? <a href="auth/register.php">Register here</a></p>
                        </div>
                    <?php else : ?>
                        <div class="card-body">
                            <p class="lead">You are successfully logged in to the Event Management System.</p>
                            <p>From here, you can manage events, view attendee lists, and more.</p>
                            <div class="d-grid gap-2">
                                <a href="events/list.php" class="btn btn-custom">Go to Event Dashboard</a>
                                <a href="auth/logout.php" class="btn btn-danger">Logout</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>