
<?php

session_start();

require __DIR__ . '/../connection/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $table = ($role == 'admin') ? 'admin' : 'students';

    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // echo "executing.............";

    if ($user && (password_verify($password, $user['password']) || $password == $user['password'])) {

        session_regenerate_id(true);
        $_SESSION['user_role'] = $role;

        if ($role == 'admin') {
            unset($_SESSION['student_id']);
            header("Location: ../dashboard/admin.php");
        } 
        else {
            $_SESSION['student_id'] = (int) $user['id'];
            header("Location: ../stddashboard.php");
        }
        exit;
    } 
    else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <main class="container login-container">
        <h2>Welcome Back</h2>
        <p><?php echo $error; ?></p>

        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Login As:</label>
                    <select name="role" required>
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-actions">
                    <button type="submit">Login</button>
                </div>
            </div>
        </form>

    </main>

</body>
</html>
