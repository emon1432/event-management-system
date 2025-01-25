<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validation
    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    }

    if (empty($errors)) {
        // Check credentials
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = executeQuery($query, [$email]);
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                header('Location: ../dashboard/index.php');
                exit;
            } else {
                $errors[] = "Invalid password.";
            }
        } else {
            $errors[] = "No user found with this email.";
        }
    }
}
?>

<h2>Login</h2>
<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php include '../includes/footer.php'; ?>