<?php

require_once '../../db.class.php/database_conn.php';

// Function to fetch featured products
function getFeaturedProducts($connection)
{
    $query = "SELECT * FROM products WHERE isFeatured = 1";
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Function to display products
function displayProducts($products)
{
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<img src='images/{$product['image']}' alt='{$product['productName']}'>";
        echo "<h3>{$product['productName']}</h3>";
        echo "<p>{$product['description']}</p>";
        echo "<p>Price: {$product['price']}</p>";
        echo "<button>Add to Cart</button>";
        echo "</div>";
    }
}

// Create a new DatabaseConnection instance
$dbConnection = new DatabaseConnection();
$connection = $dbConnection->getConnection();

// Get featured products
$featuredProducts = getFeaturedProducts($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style/style.css"> 
</head>
<body>
<?php include "../../../includes/header.php"; ?>

    <main>




        <section id="featured-products">
            <h2>Featured Products</h2>
            <?php
            // Display featured products
            displayProducts($featuredProducts);
            ?>
        </section>
    </main>

    <?php include "../../../includes/footer.php"; ?>
</body>
</html>

                