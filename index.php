<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    // Check if customer exists
    $check = $conn->prepare("SELECT customer_id FROM customer WHERE name=? AND phone_number=?");
    $check->bind_param("ss", $name, $phone);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->bind_result($customer_id);
        $check->fetch();
    } else {
        // Insert new customer
        $insert = $conn->prepare("INSERT INTO customer (name, phone_number) VALUES (?, ?)");
        $insert->bind_param("ss", $name, $phone);
        $insert->execute();
        $customer_id = $insert->insert_id;
    }

    // Set session
    $_SESSION['customer_id'] = $customer_id;

    // Redirect to menu
    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sate Famili - Home</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #d4f4dd, #e6f5e9);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h1 {
            color: #4e7050;
            font-size: 28px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="tel"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .button {
            padding: 12px 30px;
            font-size: 18px;
            background: #4e7050;
            color: #fff;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background 0.3s, transform 0.3s;
        }

        .button:hover {
            background: #3e5940;
            transform: scale(1.05);
        }

        .admin-hidden-btn {
            display: none;
            padding: 8px 16px;
            font-size: 12px;
            background: #999;
            color: #fff;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background 0.3s, transform 0.3s;
            margin-top: 10px;
        }

        .admin-hidden-btn:hover {
            background: #555;
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.png" alt="Sate Famili Logo" class="logo">
        <h1>Sate Famili Sdn Bhd</h1>

        <form method="post" action="">
            <input type="text" name="name" placeholder="Enter Your Name" required><br>
            <input type="tel" name="phone" placeholder="Enter Your Phone Number" required><br>
            <button type="submit" name="login" class="button">Click to Order</button>
        </form>

        <a href="admin_login.php" id="adminBtn" class="admin-hidden-btn">Admin</a>

        <script>
        // Secret keyboard shortcut: A + D + M (ADMIN)
        let keys = [];
        document.addEventListener('keydown', function(e) {
            keys.push(e.key.toUpperCase());
            if (keys.slice(-3).join('') === "ADM") {
                document.getElementById('adminBtn').style.display = 'inline-flex';
            }
        });
        </script>
    </div>

</body>
</html>
