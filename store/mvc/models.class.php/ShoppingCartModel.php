<?php

class ShoppingCartModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to retrieve items in the shopping cart for the shopping cart/checkout page
    public function getShoppingCartItems($customerId)
    {
        try {
            $query = "SELECT ci.cartItemId, p.productId, p.productName, p.price, ci.quantity
                      FROM cart_items ci
                      JOIN products p ON ci.productId = p.productId
                      WHERE ci.cartId = (SELECT cartId FROM shopping_carts WHERE customerId = ?)";

            $statement = $this->pdo->prepare($query);
            $statement->execute([$customerId]);

            $cartItems = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $cartItems;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to add a product to the shopping cart
    public function addToShoppingCart($customerId, $productId, $quantity)
    {
        try {
            // Check if the product is already in the cart
            $existingCartItemQuery = "SELECT * FROM cart_items 
                                      WHERE cartId = (SELECT cartId FROM shopping_carts WHERE customerId = ?)
                                      AND productId = ?";
            $existingCartItemStatement = $this->pdo->prepare($existingCartItemQuery);
            $existingCartItemStatement->execute([$customerId, $productId]);

            $existingCartItem = $existingCartItemStatement->fetch(PDO::FETCH_ASSOC);

            if ($existingCartItem) {
                // Product already in the cart, update quantity
                $updatedQuantity = $existingCartItem['quantity'] + $quantity;

                $updateQuery = "UPDATE cart_items 
                                SET quantity = ? 
                                WHERE cartItemId = ?";
                $updateStatement = $this->pdo->prepare($updateQuery);
                $updateStatement->execute([$updatedQuantity, $existingCartItem['cartItemId']]);
            } else {
                // Product not in the cart, add a new entry
                $insertQuery = "INSERT INTO cart_items (cartId, productId, quantity) 
                                VALUES ((SELECT cartId FROM shopping_carts WHERE customerId = ?), ?, ?)";
                $insertStatement = $this->pdo->prepare($insertQuery);
                $insertStatement->execute([$customerId, $productId, $quantity]);
            }

            return true; // Adding to cart successful
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to remove a product from the shopping cart
    public function removeFromShoppingCart($cartItemId)
    {
        try {
            $query = "DELETE FROM cart_items WHERE cartItemId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$cartItemId]);

            return true; // Removal from cart successful
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to update the quantity of a product in the shopping cart
    public function updateCartItemQuantity($cartItemId, $quantity)
    {
        try {
            $query = "UPDATE cart_items SET quantity = ? WHERE cartItemId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$quantity, $cartItemId]);

            return true; // Update successful
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
