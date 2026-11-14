
<?php

require __DIR__ . '/../connection/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];

    $confirm_password = $_POST['confirm_password'];

    if ($_POST['password'] != $confirm_password) {

        $error = "Passwords not match";

    } else {

        $sql = "INSERT INTO students
                (first_name, last_name, email, password, phone, gender, dob, address)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param( $stmt,"ssssssss",$first_name,$last_name,$email,$password,$phone,$gender,$dob,$address );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../dashboard/student.php");
            exit;
        } 
        else {
            $error = "Registration failed";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {background: #f5f6f8; color: #252a34}
    </style>
</head>

<body class="py-5">
<div class="container" style="max-width: 720px;">
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4">
            <h2 class="h4 mb-2">Add Student</h2>
            <p class="text-danger"><?php echo $error; ?></p>

            <form method="POST">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <a href="../dashboard/student.php" class="btn btn-link">Back</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
