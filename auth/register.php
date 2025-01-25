<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $errors = [];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = executeQuery($query, [$username, $email, $hashed_password]);

        if ($stmt->affected_rows > 0) {
            header('Location: login.php');
            exit;
        } else {
            $errors[] = "Failed to register. Please try again.";
        }
    }
}
?>

<h2>Register</h2>
<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit">Register</button>
</form>
<?php include '../includes/footer.php'; ?>