<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit();
}

$table_num = isset($_POST['table_num']) ? $_POST['table_num'] : null;

if ($table_num === null) {
    die("Error: Table number is required. Please go back and enter it.");
}

$total_price = 0;
$order_items = [];

// Calculate total and collect ordered items
foreach ($_POST as $key => $value) {
    if (strpos($key, 'item_') === 0 && $value > 0) {
        $item_id = str_replace('item_', '', $key);
        $quantity = $value;

        $item = $conn->query("SELECT * FROM menuitem WHERE item_id = $item_id")->fetch_assoc();
        $item_name = $item['name'];
        $item_price = $item['price'];

        $subtotal = $item_price * $quantity;
        $total_price += $subtotal;

        $order_items[] = [
            'item_id' => $item_id,
            'name' => $item_name,
            'quantity' => $quantity,
            'price' => $item_price,
            'subtotal' => $subtotal
        ];
    }
}

// Calculate tax (6%)
$tax = $total_price * 0.06;
$grand_total = $total_price + $tax;

// Insert into order table
$stmt = $conn->prepare("INSERT INTO `order` (table_num, total_price, payment_status, order_status) VALUES (?, ?, 'Pending', 'Preparing')");
$stmt->bind_param("id", $table_num, $grand_total);
$stmt->execute();
$order_id = $stmt->insert_id;

foreach ($order_items as $item) {
    $stmt_detail = $conn->prepare("INSERT INTO order_details (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_detail->bind_param("iiid", $order_id, $item['item_id'], $item['quantity'], $item['price']);
    $stmt_detail->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Cart</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            min-height: 100vh;
            text-align: center;
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: inline-block; width: 90%; max-width: 600px; }
        h2 { color: #4e7050; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: center; }
        .total-row { font-weight: bold; }
        .button-group { margin-top: 20px; }
        .btn {
            padding: 12px 30px;
            font-size: 16px;
            background: #4e7050;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s, transform 0.3s;
        }
        .btn:hover { background: #3e5940; transform: scale(1.05); }
    </style>
</head>
<body>

<div class="box">
    <h2>Your Order Cart</h2>
    <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
    <p><strong>Table Number:</strong> <?php echo $table_num; ?></p>

    <table>
        <tr>
            <th>Item</th><th>Qty</th><th>Price (RM)</th><th>Subtotal (RM)</th>
        </tr>
        <?php foreach ($order_items as $item): ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price'],2); ?></td>
            <td><?php echo number_format($item['subtotal'],2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td colspan="3" align="right">Subtotal:</td>
            <td>RM <?php echo number_format($total_price,2); ?></td>
        </tr>
        <tr class="total-row">
            <td colspan="3" align="right">Tax (6% SST):</td>
            <td>RM <?php echo number_format($tax,2); ?></td>
        </tr>
        <tr class="total-row">
            <td colspan="3" align="right">Total:</td>
            <td>RM <?php echo number_format($grand_total,2); ?></td>
        </tr>
    </table>

    <div class="button-group">
        <a href="menu.php" class="btn">← Back to Menu</a>
        <a href="checkout.php?order_id=<?php echo $order_id; ?>" class="btn">Check Out</a>
    </div>
</div>

</body>
</html>
