
<?php

require __DIR__ . '/connection/db.php';

$id = 1;

$error = '';

// Update Profile
if (isset($_POST['update_profile'])) {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];

    $sql = "UPDATE students SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?,
            gender = ?,
            dob = ?,
            address = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssi",
        $first,
        $last,
        $email,
        $phone,
        $gender,
        $dob,
        $address,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: stddashboard.php");
    exit;
}

// Change Password
if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $sql = "SELECT password FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);

    if (password_verify($current, $student['password']) && $new == $confirm) {

        $password = password_hash($new, PASSWORD_DEFAULT);

        $sql = "UPDATE students SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "si", $password, $id);
        mysqli_stmt_execute($stmt);

        header("Location: stddashboard.php");
        exit;

    } else {
        $error = "Password does not match";
    }
}

// Get Student Details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

// Get Enrolled Courses
$sql = "SELECT c.course_code, c.course_name, c.description,
               c.duration, e.enrolled_at
        FROM enrollment e
        JOIN course c ON c.course_code = e.course_code
        WHERE e.student_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$courses = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">

    <div class="container">

        <span class="navbar-brand fw-bold">Student Dashboard</span>

        <a href="validation/login.php" class="btn btn-light btn-sm">
            Logout
        </a>

    </div>

</nav>

<div class="container py-5">

    <h2 class="fw-bold">
        Welcome, <?= $student['first_name'] ?>
    </h2>

    <p class="text-muted">Manage your profile and enrolled courses.</p>

    <p class="text-danger"><?= $error ?></p>

    <div class="row g-4">

        <!-- Profile -->

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">My Profile</h5>

                    <div class="mb-3">
                        <span class="text-muted">Registration Number</span>
                        <h6><?= $student['reg_num'] ?></h6>
                    </div>

                    <form method="POST">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name"
                                    value="<?= $student['first_name'] ?>"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name"
                                    value="<?= $student['last_name'] ?>"
                                    class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                value="<?= $student['email'] ?>"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                value="<?= $student['phone'] ?>"
                                class="form-control">
                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>

                                <select name="gender" class="form-select">

                                    <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>

                                    <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>

                                    <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob"
                                    value="<?= $student['dob'] ?>"
                                    class="form-control">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>

                            <textarea name="address" class="form-control" rows="3"><?= $student['address'] ?></textarea>
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-primary">
                            Update Profile
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Change Password -->

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">Change Password</h5>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password"
                                class="form-control" required>
                        </div>

                        <button type="submit" name="change_password" class="btn btn-primary">
                            Change Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Enrolled Courses -->

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">My Enrolled Courses</h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Description</th>
                                    <th>Duration</th>
                                    <th>Enrolled Date</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php while ($course = mysqli_fetch_assoc($courses)): ?>

                                    <tr>

                                        <td><?= $course['course_code'] ?></td>
                                        <td><?= $course['course_name'] ?></td>
                                        <td><?= $course['description'] ?></td>
                                        <td><?= $course['duration'] ?></td>
                                        <td><?= $course['enrolled_at'] ?></td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
