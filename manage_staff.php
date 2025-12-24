<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Add Staff
if (isset($_POST['add_staff'])) {
    $staff_id = $_POST['staff_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone_number = $_POST['phone_number'];

    $conn->query("INSERT INTO staff (staff_id, name, age, phone_number) VALUES ('$staff_id', '$name', '$age', '$phone_number')");
    header("Location: manage_staff.php");
    exit();
}

// Handle Update Staff
if (isset($_POST['update_staff'])) {
    $staff_id = $_POST['staff_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone_number = $_POST['phone_number'];

    $conn->query("UPDATE staff SET name='$name', age='$age', phone_number='$phone_number' WHERE staff_id='$staff_id'");
    header("Location: manage_staff.php");
    exit();
}

// Handle Delete Staff
if (isset($_GET['delete'])) {
    $staff_id = $_GET['delete'];
    $conn->query("DELETE FROM staff WHERE staff_id='$staff_id'");
    header("Location: manage_staff.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px;
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 700px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; }
        input[type="text"] { padding: 8px; width: 90%; border-radius: 10px; border: 1px solid #ccc; }
        .btn {
            padding: 10px 20px; background: #4e7050; color: #fff; border: none; border-radius: 30px; cursor: pointer;
            transition: 0.3s; margin-top: 10px;
        }
        .btn:hover { background: #3e5940; transform: scale(1.05); }
        .delete-btn { background: #d9534f; }
        .delete-btn:hover { background: #c9302c; }
        .back-btn {
            padding: 12px 30px;
            font-size: 16px;
            background: transparent;
            color: #4e7050;
            border: 2px solid #4e7050;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            margin-top: 30px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #4e7050;
            color: #fff;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Manage Staff</h2>

    <!-- Add Staff Form -->
    <form method="post">
        <h3>Add New Staff</h3>
        <input type="text" name="staff_id" placeholder="Staff ID" required><br><br>
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="text" name="age" placeholder="Age" required><br><br>
        <input type="text" name="phone_number" placeholder="Phone Number" required><br><br>
        <button type="submit" name="add_staff" class="btn">Add Staff</button>
    </form>

    <h3 style="margin-top:40px;">Staff List</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Age</th><th>Phone</th><th>Actions</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM staff");
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<form method='post'>";
            echo "<td><input type='text' name='staff_id' value='".$row['staff_id']."' readonly></td>";
            echo "<td><input type='text' name='name' value='".$row['name']."'></td>";
            echo "<td><input type='text' name='age' value='".$row['age']."'></td>";
            echo "<td><input type='text' name='phone_number' value='".$row['phone_number']."'></td>";
            echo "<td>
                    <div style='display:flex; gap:10px; justify-content:center;'>
                        <button type='submit' name='update_staff' class='btn'>Update</button> 
                        <a href='manage_staff.php?delete=".$row['staff_id']."' class='btn delete-btn' style='text-decoration:none;'>Delete</a>
                    </div>
                  </td>";
            echo "</form>";
            echo "</tr>";
        }
        ?>
    </table>
    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
