<?php

require __DIR__ . '/../connection/db.php';

// Add course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $courseCode = trim($_POST['course_code']);
    $courseName = trim($_POST['course_name']);
    $description = "Learn and Gain knowledge";

    $sql = "INSERT INTO course (course_code, course_name, description)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $courseCode, $courseName, $description);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: course.php?saved=1");
        exit;
    } else {
        $error = "Unable to add course. Course code may already exist.";
    }
}

// Delete course
if (isset($_GET['delete'])) {

    $courseCode = $_GET['delete'];

    $sql = "DELETE FROM course WHERE course_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $courseCode);

    mysqli_stmt_execute($stmt);

    header("Location: course.php");
    exit;
}

// Fetch courses
$result = mysqli_query($conn, "SELECT * FROM course ORDER BY course_name");

$courseCount = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6f8;
        }
        .sidebar {
            min-height: 100vh;
            background: white;
        }
        .sidebar a {
            color: #626875;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background: #fff1e6;
            color: #d58b53;
        }
        .directory-banner {
            background: linear-gradient(110deg, #d6a168, #5c9c7c);
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- nav bar.................... -->
        <aside class="col-md-2 sidebar p-3">

            <h4 class="mb-4">🎓 Student</h4>

            <nav class="nav flex-column gap-2">
                <a href="admin.php" class="p-2 rounded">Admin Dashboard</a>
                <a href="student.php" class="p-2 rounded">Student Directory</a>
                <a href="course.php" class="p-2 rounded active">Courses</a>
                <a href="enrollment.php" class="p-2 rounded">Enrollments</a>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="col-md-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <p class="text-secondary mb-1">Administration</p>
                    <h3>Course Management</h3>
                </div>
            </div>

            <!-- Banner and content -->
            <div class="directory-banner text-white rounded-4 p-4 mb-4">
                <h4>Manage your courses</h4>
                <p class="mb-0">View and manage all available courses.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <p class="text-secondary mb-1">Total Courses</p>
                    <h3><?= $courseCount ?></h3>
                </div>
            </div>

            <!-- Add Course -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body">

                    <h5 class="mb-3">Add New Course</h5>

                    <form method="POST">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Course Code</label>
                                <input type="text" name="course_code" class="form-control"
                                    placeholder="Enter course code" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Course Name</label>
                                <input type="text" name="course_name" class="form-control"
                                    placeholder="Enter course name" required>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-3">
                            + Add Course
                        </button>

                    </form>

                </div>
            </div>

            <!-- display table -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5>All Courses</h5>
                    <div class="table-responsive">

                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <!-- display course -->
                            <tbody>
                                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['course_code']) ?></td>
                                        <td><?= htmlspecialchars($course['course_name']) ?></td>
                                        <td>
                                            <a href="course.php?delete=<?= urlencode($course['course_code']) ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this course?')">
                                                Remove
                                            </a>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </main>

    </div>
</div>

</body>
</html>