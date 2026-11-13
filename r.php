<?php

require __DIR__.'../connection/db.php';

$first_name = $_POST['first_name'];
$last_name =trim($_POST["last_name"]??"");
$email = trim($_POST["email"]??"");
$password = trim($_POST['password']??"");
$phone = trim($_POST["phone"]??"");
$gender = trim ($_POST["gender"]??"");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<div></div>
<!-- new session to be followed -->
    <h2>User Registration</h2>
    <div class="continer-fluid ">

    </div>

    <form action="" method="POST">

        <label>First Name:</label>
        <input type="text" name="first_name" maxlength="150" required>
        <br><br>

        <div>construct section
            <select name="" id="">builld</select>
            <option value="value">element</option>

        </div>

        <div>
            <input type="text" name="select-course" class="bg-primary w-10 
            h-9 "  required >
        </div>

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
        <div></div>
        <br><br>

        <label>Gender:</label>
        <select name="gender">
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>

        </select>
        <br><br>
        <div>
            <select id=""></select>
        </div>

        <div>
            <h2></h2>
            <label for="conv">set course</label>
            <input type="text"">
            <select name="select auto " id="" disabled="disabled"></select>
            <option value="set_time">set TIme</option>
            <option value="get_time">get Time </option>
            <div></div>
        </div>
         

        <div class="bg-primary ">
            <section></section>
        </div>

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