
<?php

require __DIR__ . '/../connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $status = $_POST['status'] ?? '';

    if ($first_name === '' || $email === '' || $password === '') {
        echo 'Please fill required fields.';
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email.';
    } 
    elseif ($password !== $confirm_password) {
        echo 'Passwords do not match.';
    } 
    else {
        $sql = 'SELECT id FROM students WHERE email = ?';
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            echo 'Registration failed.';
        } 
        else {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                echo 'Email already exists.';
            } else {
                $dob = $dob === '' ? null : $dob;
                // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO students (first_name, last_name, email, password, phone, gender, dob, address, status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

                $insertStmt = mysqli_prepare($conn, $sql);

                if (!$insertStmt) {
                    echo 'Registration failed.';
                } else {
                    mysqli_stmt_bind_param(
                        $insertStmt,
                        'sssssssss',
                        $first_name,
                        $last_name,
                        $email,
                        $hashedPassword,
                        $phone,
                        $gender,
                        $dob,
                        $address,
                        $status
                    );

                    if (mysqli_stmt_execute($insertStmt)) {
                        echo 'Registration successful!';
                    } else {
                        echo 'Registration failed.';
                    }

                    mysqli_stmt_close($insertStmt);
                }
            }

            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>

    <h2>User Registration</h2>

    <form action="" method="POST">

        <label>First Name:</label>
        <input type="text" name="first_name" maxlength="150" required>
        <br><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" maxlength="150">
        <br><br>

        <label>Email:</label>
        <input type="email" name="email" maxlength="150" required>
        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>
        <br><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        <br><br>

        <label>Phone:</label>
        <input type="tel" name="phone" maxlength="20">
        <br><br>

        <label>Gender:</label>
        <select name="gender">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <br><br>

        <label>Date of Birth:</label>
        <input type="date" name="dob">
        <br><br>

        <label>Address:</label>
        <textarea name="address" maxlength="500"></textarea>
        <br><br>

        <label>Status:</label>
        <select name="status" required>
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <br><br>

        <button type="submit">Register</button>
        <button type="reset">Reset</button>

    </form>
</body>
</html>