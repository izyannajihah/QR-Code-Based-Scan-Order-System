<?php
session_start();
include 'db.php';

// Check admin/staff login
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Update Order Status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $conn->query("UPDATE `order` SET order_status='$new_status' WHERE order_id='$order_id'");
    header("Location: admin_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Tracking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Poppins, sans-serif; background: linear-gradient(to right, #d4f4dd, #e6f5e9); text-align: center; padding: 30px; }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 900px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; font-size: 14px; }
        select, .btn {
            padding: 8px; border-radius: 8px; border: 1px solid #ccc; font-size: 14px;
        }
        .btn {
            background: #4e7050; color: #fff; cursor: pointer; transition: 0.3s; margin-left: 10px;
        }
        .btn:hover { background: #3e5940; transform: scale(1.05); }
        .back-btn {
            padding: 12px 30px; margin-top: 30px;
            background: transparent; color: #4e7050; border: 2px solid #4e7050; border-radius: 30px;
            text-decoration: none; transition: 0.3s; display: inline-block;
        }
        .back-btn:hover { background: #4e7050; color: #fff; transform: scale(1.05); }
    </style>
</head>
<body>

<div class="box">
    <h2>Order Tracking</h2>

    <table>
        <tr>
            <th>Order ID</th><th>Table</th><th>Total (RM)</th><th>Payment</th><th>Status</th><th>Action</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM `order` ORDER BY order_id DESC");
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['order_id']."</td>";
            echo "<td>".$row['table_num']."</td>";
            echo "<td>".number_format($row['total_price'],2)."</td>";
            echo "<td>".$row['payment_status']."</td>";
            echo "<td>".$row['order_status']."</td>";
            echo "<td>
                    <form method='post' style='display:flex; justify-content:center;'>
                        <input type='hidden' name='order_id' value='".$row['order_id']."'>
                        <select name='order_status'>
                            <option value='Preparing' ".($row['order_status']=='Preparing'?'selected':'').">Preparing</option>
                            <option value='Ready' ".($row['order_status']=='Ready'?'selected':'').">Ready</option>
                            <option value='Completed' ".($row['order_status']=='Completed'?'selected':'').">Completed</option>
                        </select>
                        <button type='submit' name='update_status' class='btn'>Update</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
