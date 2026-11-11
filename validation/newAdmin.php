<?php

require __DIR__ . '/../connection/db.php';

$error = '';

if($_SERVER['REQUEST_METHOD']=='POST'){

$name = $_POST['name'];
$email =$_POST['email'];
$password =$_POST['password'];

$sql = "INSERT INTO admin (name,email,password) VALUES(?,?,?)";

$stmt = mysqli_prepare($conn,$sql);

mysqli_stmt_bind_param(
    $stmt, "sss",$name,$email,$password
);

if(mysqli_stmt_execute($stmt)){
    header("Location: ../dashboard/admin.php");
    exit;
}
else{
    $error = "Faild to add ADMIN";
}

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {background: #f5f6f8; color: #252a34}
    </style>
</head>
<body class="py-5 d-flex justify-content-center align-items-center ">
    <div class="continer" style="max-width: 720px">
        <div class="card shador-sm rounded-4 border-0">
            <div class="card-body p-4">
                <h2 class="h4 mb-2">New ADMIN</h2>
                <p class="text-danger"><?php echo $error;?></p>

            <form method="POST">

                <div class="row g-3">
                    <div class="col-mb-6">
                        <label class=form-lable>Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="col-mb-6">
                        <label class=form-lable>Email</label>
                        <input type="text" name="email" class="form-control">
                    </div>
                    <div class="col-mb-6">
                        <label class=form-lable>PassWord</label>
                        <input type="text" name="password" class="form-control">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">ADD</button>
                        <a href="../dashboard/admin.php" class="btn btn-link">Back</a>
                     </div>

                </div>
            </form>
        </div>
    </div>
</div>
    
</body>
</html>