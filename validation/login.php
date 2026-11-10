
<?php

$conn = require 'db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors[] = "Email is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {

        // Check user
        $sql = "SELECT id, first_name, password FROM students WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            session_start();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];

            header('Location: ../dashboard/admin.php');
            exit;

        } else {
            $errors[] = "Invalid email or password.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <h2>Welcome Back</h2>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <p style="color:red;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Email:</label>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($email) ?>"
            placeholder="Enter your email"
            required
        >
        <br><br>

        <label>Password:</label>
        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >
        <br><br>

        <button type="submit">Login</button>

    </form>

</body>
</html>