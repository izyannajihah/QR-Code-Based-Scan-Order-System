<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sate Famili - Menu</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .top-bar img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .top-bar h1 {
            color: #4e7050;
            margin: 0;
            font-size: 28px;
        }

        .order-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-info input, .order-info select {
            padding: 10px;
            margin: 5px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .menu-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .menu-item {
            background: #f9f9f9;
            width: 200px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            padding: 15px;
            transition: 0.3s;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }

        .menu-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }

        .menu-item h3 {
            color: #4e7050;
            margin: 10px 0 5px;
            font-size: 18px;
        }

        .menu-item p {
            margin: 0 0 10px;
            color: #555;
            font-size: 14px;
        }

        .menu-item input[type="number"] {
            width: 60px;
            padding: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .submit-btn {
            display: block;
            margin: 30px auto 0;
            background: #4e7050;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #3e5940;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">

    <div class="top-bar">
        <img src="images/logo.png" alt="Sate Famili Logo">
        <h1>Menu Sate Famili</h1>
    </div>

    <form method="post" action="cart.php">
        <div class="order-info">
            <input type="number" name="table_num" placeholder="Table Number" required min="1">

            <select name="location" required>
                <option value="Meru" selected>Meru</option>
                <option value="Mutiara Damansara">Mutiara Damansara</option>
            </select>

            <input type="date" name="dine_date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="menu-list">
            <?php
            $result = $conn->query("SELECT * FROM menuitem WHERE availability_status='Available'");

            while($row = $result->fetch_assoc()) {
                $imageName = str_replace(' ', '', strtolower($row['name']));
                echo '<div class="menu-item">';
                echo '<img src="images/'.$imageName.'.jpg" alt="'.$row['name'].'">';
                echo '<h3>'.$row['name'].'</h3>';
                echo '<p style="font-size:13px; color:#777;">'.$row['description'].'</p>';
                echo '<p>RM '.$row['price'].'</p>';
                echo '<input type="number" name="item_'.$row['item_id'].'" value="0" min="0">';
                echo '</div>';
            }
            ?>
        </div>

        <button type="submit" class="submit-btn">Place Order</button>
    </form>

</div>

</body>
</html>
