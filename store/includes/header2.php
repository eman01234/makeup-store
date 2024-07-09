<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRETTY PALETTE STORE</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color:  #f0b2bd;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        .header_text h1 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .header_text p {
            margin: 5px 0;
            color: #555;
        }

        .slogan p {
            margin: 0;
            color: #777;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            background-color:  #f0b2b3;
        }

        nav ul li {
            margin: 0;
            padding: 15px;
            text-align: center;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
        }

        nav ul li a:hover {
            background-color: #0056b3;
        }
    </style>
    
    <script src="script.js"></script>
    
</head>

<body >
    <header>
        <div class="logo">
        <img src="../images/logo.png" alt="my logo">
        </div>

        <div class="header_text">
            <h1>PRETTY PALETTE STORE</h1>
            <p id="log">Welcome to our Beauty Store</p>
        </div>
        <div class="slogan">
            <p>Empowering Your Inner Glow</p>
        </div>
    </header>

    <nav>
        <ul>
           <li><a href="dashboard.php">Dashboard </a></li>
       
            <li><a href="customer_mgmt.php">Customer Managemet</a></li>
            <li><a href="order_mgmt.php">Order Management</a></li>
            <li><a href="product_mgmt.php">Product Management</a></li>
            
        </ul>
    </nav>

