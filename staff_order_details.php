<?php
session_start();
include 'db.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    die("Order ID missing.");
}

$order_id = $_GET['order_id'];

// Fetch order
$order = $conn->query("SELECT * FROM `order` WHERE order_id = $order_id")->fetch_assoc();

// Fetch order items
$items = $conn->query("SELECT od.*, m.name FROM order_details od JOIN menuitem m ON od.item_id = m.item_id WHERE od.order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {  
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px; 
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 600px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; font-size: 14px; }
        .back-btn {
            padding: 12px 30px; margin-top: 30px; background: transparent; color: #4e7050; border: 2px solid #4e7050; border-radius: 30px; text-decoration: none; transition: 0.3s; display: inline-block;
        }
        .back-btn:hover { background: #4e7050; color: #fff; transform: scale(1.05); }
    </style>
</head>
<body>

<div class="box">
    <h2>Order Details (ID: <?= $order_id ?>)</h2>

    <p><b>Table:</b> <?= $order['table_num'] ?></p>
    <p><b>Total:</b> RM <?= number_format($order['total_price'],2) ?></p>
    <p><b>Status:</b> <?= $order['order_status'] ?></p>
    <p><b>Payment:</b> <?= $order['payment_status'] ?></p>

    <h3>Items Ordered:</h3>

    <table>
        <tr><th>Item</th><th>Qty</th><th>Price (RM)</th><th>Subtotal (RM)</th></tr>
        <?php while($item = $items->fetch_assoc()): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'],2) ?></td>
                <td><?= number_format($item['quantity']*$item['price'],2) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="staff_order.php" class="back-btn">← Back to Orders</a>
</div>

</body>
</html>
