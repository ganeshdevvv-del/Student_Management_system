
<?php

require __DIR__ . '/../connection/db.php';

$id = $_POST['id'];
$action = $_POST['action'];

// Delet student
if ($action === 'remove') {

    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: student.php");
    exit();
}

// Update student
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$gender = $_POST['gender'];
$dob = $_POST['dob'] ?: null;
$address = trim($_POST['address']);

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
    $first_name,
    $last_name,
    $email,
    $phone,
    $gender,
    $dob,
    $address,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: student.php?updated=1");
} else {
    header("Location: student.php?error=1");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

exit();

?>
