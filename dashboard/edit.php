
<?php

require __DIR__ . '/../connection/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: student.php');
    exit;
}

$action = $_POST['action'] ?? '';
if ($action === 'remove') {
    $id = $_POST['id'] ?? null;
    if ($id !== null) {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: student.php");
    exit;
}

#update course.php
if ($action === 'edit_course') {
    $courseCode = trim($_POST['course_code'] ?? '');
    $courseName = trim($_POST['course_name'] ?? '');
    $fees = trim($_POST['course_fees'] ?? '');
    $start = trim($_POST['course_start'] ?? '');
    $end = trim($_POST['course_end'] ?? '');

    if ($courseCode !== '' && $courseName !== '' && $fees !== '' && $start !== '' && $end !== '') {
        $duration = $start . '-' . $end;
        $sql = "UPDATE course SET course_name = ?, duration = ?, fees = ? WHERE course_code = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sdss", $courseName, $fees, $duration, $courseCode);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: course.php?updated=1");
        } 
        else {
            header("Location: course.php?error=1");
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
    }
    header("Location: course.php?error=1");
    exit;
}

$id = $_POST['id'] ?? null;
if ($id === null) {
    header("Location: student.php");

}

// Updating the student.php
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? null;
$address = trim($_POST['address'] ?? '');

$sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, gender = ?, dob = ?, address = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssssssi", $first_name, $last_name, $email, $phone, $gender, $dob, $address, $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: student.php?updated=1");
} 
else {
    header("Location: student.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
