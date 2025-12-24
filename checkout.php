<?php
session_start();
include 'db.php';

if (!isset($_GET['order_id'])) {
    die("Order ID missing.");
}

$order_id = $_GET['order_id'];

// Fetch order info
$order = $conn->query("SELECT * FROM `order` WHERE order_id = $order_id")->fetch_assoc();
$total_price = $order['total_price'];

if (isset($_POST['pay'])) {
    $payment_method = $_POST['payment_method'];

    // Update order payment method & status
    $stmt = $conn->prepare("UPDATE `order` SET payment_method=?, payment_status='Paid' WHERE order_id=?");
    $stmt->bind_param("si", $payment_method, $order_id);
    $stmt->execute();

    // After payment, show success page with redirect
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Payment Successful</title>
        <style>
            body { 
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px; 
            }
            .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 400px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            h2 { color: #4e7050; }
            .btn {
                padding: 12px 30px;
                font-size: 16px;
                background: #4e7050;
                color: #fff;
                border: none;
                border-radius: 30px;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 150px;
                height: 50px;
                transition: background 0.3s, transform 0.3s;
            }
            .btn:hover {
                background: #3e5940;
                transform: scale(1.05);
            }
        </style>
        <meta http-equiv='refresh' content='5;url=order_status.php?order_id=$order_id'>
    </head>
    <body>

    <div class='box'>
        <h2>Payment Successful!</h2>
        <p><strong>Order ID:</strong> $order_id</p>
        <p><strong>Payment Method:</strong> $payment_method</p>
        <p><strong>Total Paid:</strong> RM " . number_format($total_price,2) . "</p>

        <p>You will be redirected to your order status page in 5 seconds...</p>
        <a href='order_status.php?order_id=$order_id' class='btn'>Go to Order Status Now</a>
    </div>

    </body>
    </html>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Payment</title>
    <style>
        body { 
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px; 
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 400px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        select {
            width: 80%; padding: 10px; margin: 15px 0; border-radius: 10px; border: 1px solid #ccc; font-size: 16px;
        }
        .btn {
            padding: 12px 30px;
            font-size: 16px;
            background: #4e7050;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
            height: 50px;
            transition: background 0.3s, transform 0.3s;
            box-sizing: border-box;
        }
        .btn:hover {
            background: #3e5940;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Checkout</h2>
    <p>Order ID: <?php echo $order_id; ?></p>
    <p>Total: RM <?php echo number_format($total_price,2); ?></p>

    <form method="post" action="checkout.php">
        <button type="submit" name="cash_pay" class="btn">Pay by Cash</button>
        <button type="submit" name="online_pay" class="btn">Pay by Card / E-wallet</button>
    </form>

        <div style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-top: 20px;">
            <a href="cart.php" class="btn">← Back</a>
        </div>
    </form>
</div>

</body>
</html>
