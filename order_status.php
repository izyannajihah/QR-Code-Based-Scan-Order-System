<?php
session_start();
include 'db.php';

if (!isset($_GET['order_id'])) {
    die("Order ID missing.");
}

$order_id = $_GET['order_id'];

// Fetch order info
$order = $conn->query("SELECT * FROM `order` WHERE order_id = $order_id")->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$table_num = $order['table_num'];
$total_price = $order['total_price'];
$order_status = $order['order_status'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            min-height: 100vh;
            text-align: center;
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 400px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        .status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
        }
        .Preparing { background: #ffc865; }
        .Ready { background: #6dc8f8; }
        .Completed { background: #85c23c; }
    </style>
</head>
<body>

<div class="box">
    <h2>Order Details</h2>
    <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
    <p><strong>Table Number:</strong> <?php echo $table_num; ?></p>
    <p><strong>Total Paid:</strong> RM <?php echo number_format($total_price,2); ?></p>

    <div class="status <?php echo $order_status; ?>">
        <?php echo $order_status; ?>
    </div>

    <h3 style="margin-top:30px;">Ordered Items:</h3>
    <table width="100%" cellpadding="10" cellspacing="0" style="margin-top:10px; background:#fff; border-radius:15px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        <tr style="background:#d4f4dd; color:#4e7050;">
            <th>Item</th><th>Qty</th><th>Price (RM)</th><th>Subtotal (RM)</th>
        </tr>
        <?php
        $items = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
        $grand_total = 0;
        while($row = $items->fetch_assoc()) {
            $subtotal = $row['quantity'] * $row['price'];
            $grand_total += $subtotal;

            echo "<tr>";
            echo "<td>".$row['item_name']."</td>";
            echo "<td>".$row['quantity']."</td>";
            echo "<td>".number_format($row['price'],2)."</td>";
            echo "<td>".number_format($subtotal,2)."</td>";
            echo "</tr>";
        }
        ?>
        <tr style="font-weight:bold; background:#e6f5e9;">
            <td colspan="3" align="right">Total:</td>
            <td>RM <?php echo number_format($grand_total,2); ?></td>
        </tr>
    </table>

    <p style="margin-top:20px;">Please wait while we process your order. <br>You can refresh this page to check the status.</p>

</div>

</body>
</html>
