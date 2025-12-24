<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sales_report = [];
$total_sales = 0;
$type = '';

if (isset($_POST['generate'])) {
    $type = $_POST['report_type'];

    switch($type) {
        case 'Daily':
            $filter = "DATE(date_generate) = CURDATE()";
            break;
        case 'Weekly':
            $filter = "YEARWEEK(date_generate, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'Monthly':
            $filter = "MONTH(date_generate) = MONTH(CURDATE()) AND YEAR(date_generate) = YEAR(CURDATE())";
            break;
        case 'Yearly':
            $filter = "YEAR(date_generate) = YEAR(CURDATE())";
            break;
    }

    $result = $conn->query("SELECT * FROM `order` WHERE $filter");

    while($row = $result->fetch_assoc()) {
        $sales_report[] = $row;
        $total_sales += $row['total_price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report Sate Famili Sdn Bhd</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Poppins, sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            text-align: center;
            padding: 50px; 
        }
        .box { background: #fff; padding: 30px; border-radius: 15px; max-width: 900px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { color: #4e7050; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 10px; font-size: 14px; }
        .btn, select {
            padding: 10px 20px; background: #4e7050; color: #fff; border: none; border-radius: 30px; cursor: pointer; margin-top: 10px; transition: 0.3s;
        }
        .btn:hover { background: #3e5940; transform: scale(1.05); }
        .print-btn {
            background: #ff9933; margin-top: 20px;
        }
        .print-btn:hover { background: #e68a00; }
        .back-btn {
            padding: 12px 30px; margin-top: 30px; background: transparent; color: #4e7050; border: 2px solid #4e7050; border-radius: 30px; text-decoration: none; transition: 0.3s; display: inline-block;
        }
        .back-btn:hover { background: #4e7050; color: #fff; transform: scale(1.05); }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Sales Report Sate Famili Sdn Bhd</h2>

    <form method="post">
        <select name="report_type" required>
            <option value="">Select Report Type</option>
            <option value="Daily" <?=($type=='Daily'?'selected':'')?>>Daily</option>
            <option value="Weekly" <?=($type=='Weekly'?'selected':'')?>>Weekly</option>
            <option value="Monthly" <?=($type=='Monthly'?'selected':'')?>>Monthly</option>
            <option value="Yearly" <?=($type=='Yearly'?'selected':'')?>>Yearly</option>
        </select>
        <button type="submit" name="generate" class="btn">Generate Report</button>
    </form>

    <?php if (!empty($sales_report)): ?>
        <div id="report-content">
            <h3 style="margin-top:20px;"><?=$type?> Sales Report</h3>
            <table>
                <tr>
                    <th>Order ID</th><th>Table</th><th>Total (RM)</th><th>Payment Status</th><th>Order Status</th><th>Date</th>
                </tr>
                <?php foreach($sales_report as $order): ?>
                    <tr>
                        <td><?=$order['order_id']?></td>
                        <td><?=$order['table_num']?></td>
                        <td><?=number_format($order['total_price'],2)?></td>
                        <td><?=$order['payment_status']?></td>
                        <td><?=$order['order_status']?></td>
                        <td><?=$order['date_generate']?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold;">
                    <td colspan="2" align="right">TOTAL SALES:</td>
                    <td colspan="4">RM <?=number_format($total_sales,2)?></td>
                </tr>
            </table>
        </div>
        <button onclick="window.print();" class="btn print-btn no-print">Print Report</button>
    <?php elseif($type != ''): ?>
        <p style="margin-top:20px;">No data available for <?=$type?> report.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn no-print">← Back to Dashboard</a>
</div>

</body>
</html>
