<?php

require __DIR__.'../connection/db.php';


$first_name = trim($_POST['first_name']??'');
$last_name = trim($_POST["last_name"]??"");
$email = trim($_POST['email']??"");
$pass = trim ($_POST["password"]??"");
$








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