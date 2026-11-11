
<?php

require __DIR__ . '/../connection/db.php';

$sql = "SELECT * FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

$studentCount = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Directory</title>

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

        <!-- Sidebar -->
        <aside class="col-md-2 sidebar p-3">

            <h4>Students</h4>

            <nav class="nav flex-column gap-2">
                <a href="admin.php" class="p-2 rounded">Admin Dashboard</a>
                <a href="student.php" class="p-2 rounded active">Student Directory</a>
                <a href="course.php" class="p-2 rounded">Courses</a>
                <a href="enrollment.php" class="p-2 rounded">Enrollments</a>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="col-md-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <p class="text-secondary mb-1">Administration</p>
                    <h3>Student Directory</h3>
                </div>

                <a href="../validation/register.php" class="btn btn-primary btn-sm rounded-pill px-2 py-1"> + Add Student </a>

            </div>

            <!-- Banner -->
            <div class="directory-banner text-white rounded-4 p-4 mb-4">
                <h4>Manage your student community</h4>
                <p class="mb-0">View all registered students.</p>
            </div>

            <!-- Student Count -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <p class="text-secondary mb-1">Total Students</p>
                    <h3><?= $studentCount ?></h3>
                </div>
            </div>

            <!-- Student Table -->
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <h5>All Students</h5>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Reg. Number</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>DOB</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($student = mysqli_fetch_assoc($result)): ?>

                                    <tr>
                                        <td><?= htmlspecialchars($student['reg_num']) ?></td>
                                        <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> </td>
                                        <td><?= htmlspecialchars($student['email']) ?></td>
                                        <td><?= htmlspecialchars($student['phone']) ?></td>
                                        <td><?= htmlspecialchars($student['gender']) ?></td>
                                        <td><?= htmlspecialchars($student['dob']) ?></td>
                                        <td><?= htmlspecialchars($student['address']) ?></td>

                                        <td>
                                            <div class="d-flex gap-2">

                                                <!-- Edit -->
                                                <button class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal"
                                                    data-id="<?= $student['id'] ?>"
                                                    data-first="<?= htmlspecialchars($student['first_name'], ENT_QUOTES) ?>"
                                                    data-last="<?= htmlspecialchars($student['last_name'], ENT_QUOTES) ?>"
                                                    data-email="<?= htmlspecialchars($student['email'], ENT_QUOTES) ?>"
                                                    data-phone="<?= htmlspecialchars($student['phone'], ENT_QUOTES) ?>"
                                                    data-gender="<?= htmlspecialchars($student['gender'], ENT_QUOTES) ?>"
                                                    data-dob="<?= $student['dob'] ?>"
                                                    data-address="<?= htmlspecialchars($student['address'], ENT_QUOTES) ?>">
                                                    Edit
                                                </button>

                                                <!-- Delete -->
                                                <form action="edit.php" method="POST"
                                                    onsubmit="return confirm('Delete this student?')">

                                                    <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                                    <input type="hidden" name="action" value="remove">

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Remove
                                                    </button>

                                                </form>

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


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form action="edit.php" method="POST">
                <div class="modal-header">
                    <h5>Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="action" value="edit">
                    
                    <!-- table rows -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="editFirst" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="editLast" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" >
                        </div>

                        <div class="col-md-6">
                            <label>Phone</label>
                            <input type="text" name="phone" id="editPhone" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Gender</label>
                            <select name="gender" id="editGender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" id="editDob" class="form-control">
                        </div>

                        <div class="col-12">
                            <label>Address</label>
                            <textarea name="address" id="editAddress" class="form-control"></textarea>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.getElementById('editModal').addEventListener('show.bs.modal', function(event) {

    let button = event.relatedTarget;

    document.getElementById('editId').value = button.dataset.id;
    document.getElementById('editFirst').value = button.dataset.first;
    document.getElementById('editLast').value = button.dataset.last;
    document.getElementById('editEmail').value = button.dataset.email;
    document.getElementById('editPhone').value = button.dataset.phone;
    document.getElementById('editGender').value = button.dataset.gender;
    document.getElementById('editDob').value = button.dataset.dob;
    document.getElementById('editAddress').value = button.dataset.address;

});

</script>

</body>
</html>
