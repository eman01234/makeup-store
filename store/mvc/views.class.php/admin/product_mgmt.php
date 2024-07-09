<?php

require_once '../../db.class.php/database_conn.php';


// Function to fetch product names for dropdowns
function getProductNames($connection)
{
    $query = "SELECT productName FROM products"; // Adjust the table name
    $statement = $connection->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Function to generate product options for the dropdowns
function generateProductOptions($productNames)
{
    foreach ($productNames as $productName) {
        echo "<option value='{$productName['productName']}'>{$productName['productName']}</option>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted

    try {
        // Create a new DatabaseConnection instance
        $dbConnection = new DatabaseConnection();
        $connection = $dbConnection->getConnection();

        // Handle Add Product Form Submission
        if (isset($_POST['addProduct'])) {
            $productName = $_POST['productName'];
            $image = $_FILES['image']['name'];
            $tempImage = $_FILES['image']['tmp_name'];
            move_uploaded_file($tempImage, '../../../uploads/' . $image);
            $description = $_POST['description'];
            $productPrice = $_POST['price'];
            $stockQuantity = $_POST['stockQuantity'];
            $CategoryId = $_POST['categoryId'];
            $IsFeatured = $_POST['isFeatured'];

            $conn = new mysqli('localhost', 'root', '', 'store');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO products (productName, image, description, price, stockQuantity, categoryId, isFeatured) 
        VALUES ('$productName', '$image', '$description', $productPrice, $stockQuantity, $CategoryId, $IsFeatured)";


if ($conn->query($sql) === TRUE) {
    echo "Added successfully!";
    
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
            
        }

        elseif (isset($_POST['updateProduct'])) {
            $selectedProduct = $_POST['updateProductSelect'];
            $updatedProductName = $_POST['updatedProductName'];
            $updatedProductPrice = $_POST['updatedProductPrice'];
            $updatedProductCategory = $_POST['updatedProductCategory'];
        
            $conn = new mysqli('localhost', 'root', '', 'store');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
        
            // Use prepared statement to prevent SQL injection
            $sql = "UPDATE products
                    SET productName = ?, 
                        price = ?
                    WHERE productName = ?";
        
            $statement = $conn->prepare($sql);
        
            if (!$statement) {
                die("Error: " . $conn->error);
            }
        
            $statement->bind_param('sss', $updatedProductName, $updatedProductPrice, $selectedProduct);
        
            if ($statement->execute()) {
                echo "Updated successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        
            $statement->close();
            $conn->close();
        }
        
        
        

        // Handle Remove Product Form Submission
    elseif (isset($_POST['removeProduct'])) {
    $selectedProduct = $_POST['removeProductSelect'];
    $conn = new mysqli('localhost', 'root', '', 'store');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "DELETE FROM products WHERE productName = ?";

    $statement = $conn->prepare($sql);

    if (!$statement) {
        die("Error: " . $conn->error);
    }

    $statement->bind_param('s', $selectedProduct);

    if ($statement->execute()) {
        echo "Deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $statement->close();
    $conn->close();
}


    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="style/styles.css"> 
    <script src="js/script.js"></script>
</head>
<body>
    <?php include "../../../includes/header2.php"; ?>

    <div class="product-management-container">
        <h1>Product Management</h1>

        <!-- Add Product Form -->
        <section class="add-product-section">
            <h2>Add Product</h2>
            <form id="addProductForm" method="POST" enctype="multipart/form-data">

                <label for="productName">Product Name:</label>
                <input type="text" id="productName" name="productName" required>

                <label for="image">Image: </label>
                <input type="file" name="image" accept="image/*" required><br>

                <label for="description">Description:</label>
                <input type="text" id="decription" name="description" required>

                <label for="productPrice">Price:</label>
                <input type="text" name="price" id="price" required>
  

                <label for="stockQuantity">stockQuantity:</label>
                <input type="number" id="stockQuantity" name="stockQuantity" required><br>

                <label for="categoryId">Category Id:</label>
                <input type="text" id="categoryId" name="categoryId" required>

                <label for="isFeatured">Is Featured:</label>
                <input type="number" id="isFeatured" name="isFeatured" required>

                <button type="submit" name="addProduct">Add Product</button>
            </form>
        </section>

        <!-- Update Product Form -->
        <section class="update-product-section">
            <h2>Update Product</h2>
            <form id="updateProductForm" method="POST" enctype="multipart/form-data">
                <label for="updateProductSelect">Select Product:</label>
                <select id="updateProductSelect" name="updateProductSelect" required>
                    <?php
                    $productNames = getProductNames($connection);
                    generateProductOptions($productNames);
                    ?>
                </select>

                <label for="updatedProductName">New Product Name:</label>
                <input type="text" id="updatedProductName" name="updatedProductName" required>

                <label for="updatedProductPrice">New Price:</label>
                <input type="text" id="updatedProductPrice" name="updatedProductPrice" required>

                <button type="submit" name="updateProduct">Update Product</button>
            </form>
        </section>

        <!-- Remove Product Section -->
        <section class="remove-product-section">
            <h2>Remove Product</h2>
            <form id="removeProductForm" method="POST" enctype="multipart/form-data">
                <label for="removeProductSelect">Select Product:</label>
                <select id="removeProductSelect" name="removeProductSelect" required>
                    <?php
                    // Populate the dropdown with product names
                    generateProductOptions($productNames);
                    ?>
                </select>

                <button type="submit" name="removeProduct">Remove Product</button>
            </form>
        </section>
    </div>

   
</body>
</html>
