
<?php

require __DIR__ . '/../connection/db.php';

// Get students and courses
$students = mysqli_query($conn, "SELECT * FROM students");
$courses = mysqli_query($conn, "SELECT * FROM course");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = $_POST['student_id'];
    $course_codes = $_POST['course_codes'] ?? [];

    # Delete old enrollments
    $sql = "DELETE FROM enrollment WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);

    // update new selected courses
    foreach ($course_codes as $code) {
        $sql = "INSERT INTO enrollment (student_id, course_code) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $student_id, $code);
        mysqli_stmt_execute($stmt);
    }

    header("Location: enrollment.php");
    exit;
}

// Get student enrollment details
$sql = "SELECT s.id, s.reg_num, s.first_name, s.last_name, s.email, c.course_name
        FROM students s
        LEFT JOIN enrollment e ON s.id = e.student_id
        LEFT JOIN course c ON e.course_code = c.course_code
        ORDER BY s.id";

$result = mysqli_query($conn, $sql);

$enrollments = [];

while ($row = mysqli_fetch_assoc($result)) {

    $id = $row['id'];

    $enrollments[$id]['reg_num'] = $row['reg_num'];
    $enrollments[$id]['name'] = $row['first_name'] . ' ' . $row['last_name'];
    $enrollments[$id]['email'] = $row['email'];

    if ($row['course_name'] != NULL) {
        $enrollments[$id]['courses'][] = $row['course_name'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

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

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-md-2 sidebar p-3">
                <h4 class="mb-4">🎓 Student</h4>
                <nav class="nav flex-column gap-2">
                    <a href="admin.php" class="p-2 rounded">▦  Admin Dashboard</a>
                    <a href="student.php" class="p-2 rounded">♙ Student profile</a>
                    <a href="course.php" class="p-2 rounded">▤ Courses</a>
                    <a href="enrollment.php" class="p-2 rounded active">Enroll Student</a>
                </nav>
            </aside>
            <main class="col-md-10 p-4">
                <p class="text-secondary mb-1">Administration</p>
                <h3 class="mb-4">Course Management</h3>

                <!-- Banner -->
                <div class="directory-banner text-white rounded-4 p-4 mb-4">
                    <h4>Manage your courses</h4>
                    <p class="mb-0">View and manage all available courses.</p>
                </div>
<!-- ------------------------------------------------------------------------------------------- -->
                <!-- Enrollment Form displaying.... -->

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Student Enrollment</h5>
                        <form method="POST">
                            <label class="form-label">Select Student</label>
                            <select name="student_id" class="form-select mb-3" required>
                                <option value="">Choose Student</option>
                                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= $student['reg_num'] ?> -
                                        <?= $student['first_name'] . ' ' . $student['last_name'] ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                            <label class="form-label">Select Courses</label>

                            <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                                <div class="form-check">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        name="course_codes[]"
                                        value="<?= $course['course_code'] ?>">

                                    <label class="form-check-label">
                                        <?= $course['course_name'] ?>
                                        (<?= $course['course_code'] ?>)
                                    </label>
                                </div>
                            <?php endwhile; ?>
                            <button type="submit" class="btn btn-primary btn-sm mt-3">
                                Save Enrollment
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Enrollment Table -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Student Enrollments</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">

                                <thead>
                                    <tr>
                                        <th>Reg Number</th>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Courses</th>
                                    </tr>
                                </thead>

                                <tbody>
            <?php foreach ($enrollments as $student): ?>
                     <tr>
                        <td><?= $student['reg_num'] ?></td>
                        <td><?= $student['name'] ?></td>
                         <td><?= $student['email'] ?></td>
                    <td>
                         <?= implode(', ', $student['courses'] ?? []) ?: 'No courses' ?>
                     </td>
                      </tr>
                         <?php endforeach; ?>
                                    
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