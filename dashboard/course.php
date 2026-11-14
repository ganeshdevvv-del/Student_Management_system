
<?php

require __DIR__ . '/../connection/db.php';

// add new course
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $courseCode = trim($_POST['course_code']);
    $courseName = trim($_POST['course_name']);
    $description = "Learn and Gain more knowledge";
    $fees = trim($_POST['course_fees']);
    $start = trim($_POST['course_start']);
    $end = trim($_POST['course_end']);

    $duration = $start . "-" . $end;

    $sql = "INSERT INTO course (course_code, course_name, description, duration, fees)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssd", $courseCode, $courseName, $description, $duration, $fees);
    if (mysqli_stmt_execute($stmt))
         {
        header("Location: course.php?saved=1");
        exit;
    }
     else {
        $error = "Unable to add course.";
        exit;
    }
}
// Delete Course
if (isset($_GET['delete'])) {
    $courseCode = $_GET['delete'];
    $sql = "DELETE FROM course WHERE course_code = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $courseCode);
    mysqli_stmt_execute($stmt);
    header("Location: course.php");
    exit;
}

// Fetch Courses
$result = mysqli_query($conn, "SELECT * FROM course ORDER BY course_code ASC");
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

            <!-- nav bar -->
            <aside class="col-md-2 sidebar p-3">
                <h4 class="mb-4">🎓 Student</h4>
                <nav class="nav flex-column gap-2">
                    <a href="admin.php" class="p-2 rounded">▦  Admin Dashboard</a>
                    <a href="student.php" class="p-2 rounded">♙ Student profile</a>
                    <a href="course.php" class="p-2 rounded active">Course Directory</a>
                    <a href="enrollment.php" class="p-2 rounded">☑ Enrollments</a>
                </nav>
            </aside>

            <!-- Main Content & Banner........... -->
            <main class="col-md-10 p-4">
                <div clas="mb-4">
                    <p class="text-secondary mb-1">Administration</p>
                    <h3>Course Management</h3>
                </div>
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
                <?php if (isset($_GET['saved'])): ?>
                    <div class="alert alert-success">
                        Course added successfully!
                    </div>
                <?php endif; ?>

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

                                <div class="col-md-6">
                                    <label class="form-label">Course Fees</label>
                                    <input type="number" name="course_fees" class="form-control"
                                        placeholder="Enter course amount" min="0" step="0.01" required>
                                </div>

                                <div class="col-12">
                                    <h6 class="mb-0">Course Duration</h6>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Start At</label>
                                    <input type="text" name="course_start" class="form-control"
                                        placeholder="Jan" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">End At</label>
                                    <input type="text" name="course_end" class="form-control"
                                        placeholder="Dec" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm mt-3">
                                + Add Course
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Display course -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5>All Courses</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Name</th>
                                        <th>Course Fees</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['course_code']) ?></td>
                                        <td><?= htmlspecialchars($course['course_name']) ?></td>
                                        <td><?= htmlspecialchars($course['fees']) ?></td>
                                        <td><?= htmlspecialchars($course['duration']) ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editCourseModal"
                                                        data-code="<?= htmlspecialchars($course['course_code']) ?>"
                                                        data-name="<?= htmlspecialchars($course['course_name']) ?>"
                                                        data-fees="<?= htmlspecialchars($course['fees']) ?>"
                                                        data-duration="<?= htmlspecialchars($course['duration']) ?>">
                                                    Edit
                                                </button>

                                                <a href="course.php?delete=<?= urlencode($course['course_code']) ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this course?')">
                                                    Remove
                                                </a>
                                            </div>
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

    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="edit.php" method="POST">
                    <div class="modal-header">
                        <h5>Edit Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit_course">
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" id="editCourseCode" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" id="editCourseName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course Fees</label>
                            <input type="number" name="course_fees" id="editCourseFees" class="form-control" min="0" step="0.01" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Start At</label>
                                <input type="text" name="course_start" id="editCourseStart" class="form-control" placeholder="Jan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End At</label>
                                <input type="text" name="course_end" id="editCourseEnd" class="form-control" placeholder="Dec" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editCourseModal = document.getElementById('editCourseModal');

        editCourseModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const code = button.dataset.code;
            const name = button.dataset.name;
            const fees = button.dataset.fees;
            const duration = button.dataset.duration || '';
            const [start, end] = duration.split('-');

            document.getElementById('editCourseCode').value = code;
            document.getElementById('editCourseName').value = name;
            document.getElementById('editCourseFees').value = fees;
            document.getElementById('editCourseStart').value = start || '';
            document.getElementById('editCourseEnd').value = end || '';
        });
    </script>
</body>
</html>
