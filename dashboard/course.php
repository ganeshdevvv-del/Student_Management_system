
<?php
require __DIR__ . '/../connection/db.php';

// to add  new course
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $courseCode = trim($_POST['course_code']);
    $courseName = trim($_POST['course_name']);
    $description = trim($_POST['description']);

    $sql = "INSERT INTO course (course_code, course_name, description)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $courseCode, $courseName, $description);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: course.php?saved=1");
        exit;
    } else {
        $error = "Unable to add course. Check whether the course code already exists.";
    }
}

// Removing the course..........
if (isset($_GET['delete'])) {

    $courseCode = $_GET['delete'];

    $sql = "DELETE FROM course WHERE course_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $courseCode);

    mysqli_stmt_execute($stmt);

    header("Location: course.php");
    exit;
}

// fetch the course courses
$result = mysqli_query($conn, "SELECT * FROM course ORDER BY course_name");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2 class="mb-4">Course Management</h2>

    <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Course added successfully.</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
<!-- --------------------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Add Course Form -->
    <div class="card p-4 mb-4">

        <h4 class="mb-3">Add New Course</h4>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Course Code</label>
                <input type="text" name="course_code" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Course</button>

        </form>

    </div>
<!-- ----------------------------------------------------------------------------------------------------------------- -->
    <!-- Course List -->
    <div class="card p-4">

        <h4 class="mb-3">Available Courses</h4>

        <table class="table table-bordered table-hover">

            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($course = mysqli_fetch_assoc($result)): ?>

                    <tr>
                        <td><?= htmlspecialchars($course['course_code']) ?></td>

                        <td><?= htmlspecialchars($course['course_name']) ?></td>

                        <td><?= htmlspecialchars($course['description'] ?? '') ?></td>

                        <td>
                            <a
                                href="course.php?delete=<?= urlencode($course['course_code']) ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this course?')">
                                Delete
                            </a>
                        </td>
                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
