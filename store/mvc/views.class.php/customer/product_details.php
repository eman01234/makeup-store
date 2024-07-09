<?php

require_once '../../db.class.php/database_conn.php';

// Function to get product details by product ID
function getProductDetails($connection, $productId)
{
    $query = "SELECT * FROM products WHERE productId = :productId";
    $statement = $connection->prepare($query);
    $statement->bindParam(':productId', $productId, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Create a new DatabaseConnection instance
$dbConnection = new DatabaseConnection();
$connection = $dbConnection->getConnection();

// Example product ID (you might get it from a URL parameter or elsewhere)
$productId = 1;

// Fetch product details
$productDetails = getProductDetails($connection, $productId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <?php include "../../../includes/header.php"; ?>

    <div class="product-details">
        <h2>Product Details</h2>
        <?php if ($productDetails): ?>
            <p><strong>Name:</strong> <?php echo $productDetails['productName']; ?></p>
            <p><strong>Price:</strong> $<?php echo $productDetails['productPrice']; ?></p>
            <p><strong>Description:</strong> <?php echo $productDetails['productDescription']; ?></p>
            
        <?php else: ?>
            <p>Product not found</p>
        <?php endif; ?>
    </div>

    <?php include "../../../includes/footer.php"; ?>
</body>
</html>
