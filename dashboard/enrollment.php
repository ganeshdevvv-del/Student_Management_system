
<?php

require __DIR__ . '/../connection/db.php';

// Get students
$students = mysqli_query($conn, "SELECT * FROM students");

// Get courses
$courses = mysqli_query($conn, "SELECT * FROM course");

// Save enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = $_POST['student_id'];
    $course_codes = $_POST['course_codes'];

    // Delete old enrollments
    mysqli_query($conn, "DELETE FROM enrollment WHERE student_id = $student_id");

    // Insert selected courses
    foreach ($course_codes as $course_code) {

        $sql = "INSERT INTO enrollment (student_id, course_code) VALUES (?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "is", $student_id, $course_code);

        mysqli_stmt_execute($stmt);
    }

    header("Location: enrollment.php");
    exit;
}

// Get enrollment details
$sql = "SELECT 
            s.reg_num,
            s.first_name,
            s.last_name,
            s.email,
            c.course_name
        FROM students s
        LEFT JOIN enrollment e ON s.id = e.student_id
        LEFT JOIN course c ON e.course_code = c.course_code
        ORDER BY s.id";

$result = mysqli_query($conn, $sql);

$enrollments = [];

while ($row = mysqli_fetch_assoc($result)) {

    $id = $row['reg_num'];

    $enrollments[$id]['reg_num'] = $row['reg_num'];
    $enrollments[$id]['name'] = $row['first_name'] . ' ' . $row['last_name'];
    $enrollments[$id]['email'] = $row['email'];

    if ($row['course_name']) {
        $enrollments[$id]['courses'][] = $row['course_name'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Enrollment</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <h2 class="mb-4">Student Enrollment</h2>

    <!-- Form  creation...........-->

    <div class="card p-4 mb-4">

        <form method="POST">
            <label class="form-label">Select Student</label>
            <select name="student_id" class="form-select mb-3">
                <option value="">Choose Student</option>
                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                    <option value="<?= $student['id'] ?>">
                        <?= $student['reg_num'] ?> -
                        <?= $student['first_name'] . ' ' . $student['last_name'] ?>
                    </option>
                <?php endwhile; ?>

            </select>

            <label class="form-label">Select Courses</label>

            <?php 
            while ($course = mysqli_fetch_assoc($courses)): ?>

                <div class="form-check">   
                <!-- select course -->
                    <input type="checkbox" class="form-check-input" name="course_codes[]" value="<?= $course['course_code'] ?>" >

                    <label class="form-check-label">
                        <?= $course['course_name'] ?>
                        (<?= $course['course_code'] ?>)
                    </label>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="btn btn-primary mt-3">
                Save Enrollment
            </button>
        </form>

    </div>

    <!-- display enrollment -->

    <div class="card p-4">

        <h2 class="mb-3">Student Enrollments</h4>

        <table class="table table-bordered">

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

</body>
</html>
