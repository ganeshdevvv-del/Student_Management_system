<?php
require __DIR__ . '/../connection/db.php';

$result = mysqli_query($conn, 'SELECT * FROM course ORDER BY id ASC');

if (!$result) {
    http_response_code(500);
    exit('Unable to load course details: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <h1 class="h3 mb-0">Course Details</h1>
            <a class="btn btn-outline-primary" href="admin.php">Back to dashboard</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php $courseColumns = mysqli_fetch_fields($result); ?>
                                <?php if ($courseColumns): ?>
                                    <?php foreach ($courseColumns as $column): ?>
                                        <th scope="col"><?= htmlspecialchars($column->name, ENT_QUOTES, 'UTF-8') ?></th>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <th scope="col">Course</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <?php foreach ($course as $value): ?>
                                            <td><?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="1" class="text-center text-secondary py-4">No courses found.</td>
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
