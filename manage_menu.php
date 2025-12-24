<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Add Menu Item
if (isset($_POST['add_menu'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $conn->query("INSERT INTO menuitem (name, description, price, availability_status) 
                  VALUES ('$name', '$description', '$price', '$availability')");
    header("Location: manage_menu.php");
    exit();
}

// Update Menu Item
if (isset($_POST['update_menu'])) {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $conn->query("UPDATE menuitem SET name='$name', description='$description', price='$price', availability_status='$availability' WHERE item_id='$item_id'");
    header("Location: manage_menu.php");
    exit();
}

// Delete Menu Item
if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];
    $conn->query("DELETE FROM menuitem WHERE item_id='$item_id'");
    header("Location: manage_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
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
        input[type="text"], input[type="number"], textarea, select {
            padding: 8px; width: 90%; border-radius: 10px; border: 1px solid #ccc;
        }
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
    <h2>Manage Menu</h2>

    <!-- Add Menu Form -->
    <form method="post">
        <h3>Add New Menu Item</h3>
        <input type="text" name="name" placeholder="Menu Name" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="number" name="price" placeholder="Price (RM)" step="0.01" required><br><br>
        <select name="availability">
            <option value="Available">Available</option>
            <option value="Not Available">Not Available</option>
        </select><br><br>
        <button type="submit" name="add_menu" class="btn">Add Menu</button>
    </form>

    <h3 style="margin-top:40px;">Menu List</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th></tr>
        <?php
        $result = $conn->query("SELECT * FROM menuitem");
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<form method='post'>";
            echo "<td><input type='text' name='item_id' value='".$row['item_id']."' readonly></td>";
            echo "<td><input type='text' name='name' value='".$row['name']."'></td>";
            echo "<td><textarea name='description'>".$row['description']."</textarea></td>";
            echo "<td><input type='number' step='0.01' name='price' value='".$row['price']."'></td>";
            echo "<td>
                    <select name='availability'>
                        <option value='Available' ".($row['availability_status']=='Available'?'selected':'').">Available</option>
                        <option value='Not Available' ".($row['availability_status']=='Not Available'?'selected':'').">Not Available</option>
                    </select>
                  </td>";
            echo "<td>
                    <div style='display:flex; gap:10px; justify-content:center;'>
                        <button type='submit' name='update_menu' class='btn'>Update</button> 
                        <a href='manage_menu.php?delete=".$row['item_id']."' class='btn delete-btn' style='text-decoration:none;'>Delete</a>
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
