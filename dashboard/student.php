<?php
$conn = require __DIR__ . '/../validation/db.php';
$students = mysqli_query(
    $conn,
    'SELECT id, first_name, last_name, email, phone, gender, dob, address, status
     FROM students
     ORDER BY id ASC'
);

if (!$students) {
    http_response_code(500);
    exit('Unable to load student details: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <h1 class="h3 mb-0">Student Details</h1>
            <a class="btn btn-outline-primary" href="admin.php">Back to dashboard</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Date of Birth</th>
                                <th scope="col">Address</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($students) > 0): ?>
                                <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                    <tr>
                                        <td><?= (int) $student['id'] ?></td>
                                        <td><?= htmlspecialchars(trim($student['first_name'] . ' ' . $student['last_name']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['gender'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['dob'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['address'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($student['status'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-secondary py-4">No students found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
