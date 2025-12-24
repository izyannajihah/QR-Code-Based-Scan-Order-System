<?php
session_start();
include 'db.php';

$error = '';

if (isset($_POST['login'])) {
    $user_id = $_POST['user_id'];

    // Check Admin
    $check_admin = $conn->query("SELECT * FROM admin WHERE admin_id = '$user_id'");
    if ($check_admin->num_rows > 0) {
        $_SESSION['admin_id'] = $user_id;
        header("Location: dashboard.php");
        exit();
    }

    // Check Staff
    $check_staff = $conn->query("SELECT * FROM staff WHERE staff_id = '$user_id'");
    if ($check_staff->num_rows > 0) {
        $_SESSION['staff_id'] = $user_id;
        header("Location: dashboard.php");
        exit();
    }

    $error = "Invalid ID. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Staff Login</title>
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .btn {
            padding: 12px 30px;
            font-size: 16px;
            background: #4e7050;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }
        .btn:hover {
            background: #3e5940;
            transform: scale(1.05);
        }
        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="images/logo.png" class="logo" alt="Sate Famili Logo">
    <h2>Admin / Staff Login</h2>

    <form method="post" action="">
        <input type="text" name="user_id" placeholder="Enter Your ID" required>
        <button type="submit" name="login" class="btn">Login</button>

        <?php if($error != ''): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
