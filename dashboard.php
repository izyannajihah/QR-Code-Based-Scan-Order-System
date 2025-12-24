<?php
session_start();
include 'db.php';

// Check who is logged in
if (isset($_SESSION['admin_id'])) {
    $role = "Admin";
} elseif (isset($_SESSION['staff_id'])) {
    $role = "Staff";
} else {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sate Famili Dashboard</title>
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: inline-block;
            width: 350px;
        }
        h2 { color: #4e7050; margin-bottom: 20px; }
        .btn {
            padding: 12px 30px;
            font-size: 16px;
            background: #4e7050;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            width: 80%;
        }
        .btn:hover {
            background: #3e5940;
            transform: scale(1.05);
        }
        .logout-btn {
            padding: 12px 30px;
            font-size: 16px;
            background: transparent;
            color: #ff9999;
            border: 2px solid #ff9999;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
            transition: 0.3s;
            width: 80%;
        }
        .logout-btn:hover {
            background: #ff9999;
            color: #fff;
            transform: scale(1.05);
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="images/logo.png" class="logo" alt="Sate Famili Logo">
    <h2>Welcome, <?php echo $role; ?></h2>

    <?php if ($role == "Admin") { ?>
        <a href="manage_staff.php" class="btn">Manage Staff</a><br>
        <a href="manage_menu.php" class="btn">Manage Menu</a><br>
        <a href="admin_order.php" class="btn">Manage Orders</a><br>
        <a href="view_reports.php" class="btn">View Reports</a><br>
    <?php } else { ?>
        <a href="staff_order.php" class="btn">View & Update Orders</a><br>
    <?php } ?>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
