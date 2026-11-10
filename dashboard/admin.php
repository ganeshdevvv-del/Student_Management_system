<?php
$conn = require __DIR__ . '/../validation/db.php';
$result = mysqli_query(
    $conn,
    'SELECT (SELECT COUNT(*) FROM students) AS total_students,
            (SELECT COUNT(*) FROM course) AS total_courses'
);

if (!$result) {
    http_response_code(500);
    exit('Unable to load dashboard data: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}

                $stats = mysqli_fetch_assoc($result);
?>




<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f6f8; color: #252a34; }
        .sidebar { min-height: 100vh; background: #fff; }
        .sidebar a { color: #626875; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { background: #fff1e6; color: #d58b53; }
        .banner { background: linear-gradient(110deg, #d6a168, #5c9c7c); }
        .stat-icon { width: 40px; height: 40px; }
        @media (max-width: 767px) { .sidebar { min-height: auto; } }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-2 sidebar p-3">
            <h4 class="mb-4">🎓 Student</h4>
            <nav class="nav flex-column gap-2">
                <a class="active rounded p-2" href="admin.php">▦ &nbsp; Admin Dashboard</a>
                <a class="rounded p-2" href="student.php">♙ &nbsp; Student profile</a>
                <a class="rounded p-2" href="course.php">▤ &nbsp; Courses</a>
                <a class="rounded p-2" href="#">⚙ &nbsp; Settings</a>
            </nav>
        </aside>

        <main class="col-md-10 p-3 p-lg-4">
            <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">Dashboard</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <input class="form-control rounded-pill" type="search" placeholder="Search..." aria-label="Search">
                    <span class="text-nowrap">🔔 &nbsp; Student</span>
                </div>
            </header>

            <section class="banner text-white rounded-4 p-4 p-lg-5 mb-4">
                <p class="mb-2">Academic Year 2026-2030</p>
                <h2 class="h4">Welcome to the Student Dashboard</h2>
                <p class="mb-3">View your courses, attendance and academic progress.</p>
                <a class="btn btn-light rounded-pill px-4" href="#overview">View overview</a>
            </section>

            <section id="overview" class="row g-3">
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="stat-icon bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center mb-4">♙</div>
                            <p class="text-secondary mb-1">Total Students</p>
                            <h3 class="h4 mb-0"><?= (int) $stats['total_students'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body">
                            <div class="stat-icon bg-success-subtle rounded-circle d-flex align-items-center justify-content-center mb-4">▤</div>
                            <p class="text-secondary mb-1">Courses</p>

                            <h3 class="h4 mb-0"><?= (int) $stats['total_courses'] ?></h3>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
</body>
</html>
