
<?php

session_start();

if (!isset($_SESSION['user_role'], $_SESSION['student_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: validation/login.php");
    exit;
}
require __DIR__ . '/connection/db.php';
$studentId = $_SESSION['student_id'];
$error = '';
// Fetch student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    header("Location: validation/login.php");
    exit;
}

// Update profile
if (isset($_POST['update_profile'])) {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = $_POST['address'] ?? '';

    $sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, gender = ?, dob = ?, address = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "sssssssi", $firstName, $lastName, $email, $phone, $gender, $dob, $address,$studentId);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: stddashboard.php?updated=1");
        exit;
    } else {
        $error = "Profile update failed";
    }
}

// Fetch enrolled courses
$sql = "SELECT c.*
        FROM enrollment e
        INNER JOIN course c ON e.course_code = c.course_code
        WHERE e.student_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);

$enrollmentResult = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f8;
            color: #252a34;
        }
        .sidebar {
            min-height: 100vh;
            background: white;
        }
        .sidebar a {
            color: #626875;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #fff1e6;
            color: #d58b53;
        }
        .banner {
            background: linear-gradient(110deg, #d6a168, #5c9c7c);
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="mb-4">Student Portal</h4>
                <nav class="nav flex-column gap-2">
                    <a class="rounded p-2" href="#profile">My Profile</a>
                    <a class="rounded p-2" href="#enrollments">My Courses</a>
                </nav>
            </aside>
            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 p-3 p-lg-4">
                <header class="mb-4">
                    <p class="text-secondary mb-1">Student Account</p>
                    <h1 class="h3">
                        Welcome, <?= htmlspecialchars($student['first_name']) ?>
                    </h1>
                </header>
                <!-- Banner -->
                <section class="banner text-white rounded-4 p-4 mb-4">
                    <p>Student Number: <?= htmlspecialchars($student['reg_num']) ?></p>
                    <h2 class="h4">Wellcome</h2>
                    <p>Review your personal details and courses enrolled.</p>
                </section>

                <?php if (isset($_GET['updated'])) { ?>
                    <div class="alert alert-success">
                        Profile updated successfully.
                    </div>
                <?php } ?>

                <!-- Error Message -->
                <?php if ($error != '') { ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php } ?>

                <!-- Profile -->
                <section id="profile" class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">Personal Details</h2>
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student Number</label>
                                    <input class="form-control"
                                        value="<?= htmlspecialchars($student['reg_num']) ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?= htmlspecialchars($student['email']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="<?= htmlspecialchars($student['first_name']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="<?= htmlspecialchars($student['last_name']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?= htmlspecialchars($student['phone']) ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>

                                    <select name="gender" class="form-select">
                                        <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= $student['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="<?= htmlspecialchars($student['dob'] ?? '') ?>">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" rows="3"
                                        class="form-control"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                                </div>

                            </div>

                            <button type="submit" name="update_profile"
                                class="btn btn-primary mt-4">
                                Save Profile
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Enrolled Courses -->
                <section id="enrollments" class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h2 class="h5 mb-3">My Courses</h2>
                        <p class="text-secondary">
                            Total Courses: <?= mysqli_num_rows($enrollmentResult) ?>
                        </p>
                        <?php if (mysqli_num_rows($enrollmentResult) > 0) { ?>
                            <div class="row g-3">
                                <?php while ($course = mysqli_fetch_assoc($enrollmentResult)) { ?>
                                    <div class="col-12 col-xl-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <h3 class="h6">
                                                <?= htmlspecialchars($course['course_name']) ?>
                                            </h3>
                                            <span class="badge text-bg-primary">
                                                <?= htmlspecialchars($course['course_code']) ?>
                                            </span>
                                            <p class="text-secondary mt-2">
                                                <?= htmlspecialchars($course['description'] ?? '') ?>
                                            </p>
                                            <p>
                                                Duration: <?= htmlspecialchars($course['duration'] ?? '') ?>
                                            </p>
                                            <p>
                                                Fees: <?= htmlspecialchars($course['fees'] ?? '') ?>
                                            </p>
                                        </div>
                                    </div>

                                <?php } ?>

                            </div>
                        <?php } else { ?>
                            <p class="text-secondary">
                                You are not enrolled in any courses.
                            </p>

                        <?php } ?>

                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
