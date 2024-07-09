<?php

class OrderModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to place an order and initiate the checkout process
    public function placeOrder($customerId, $cartItems)
    {
        try {
            // Begin a transaction
            $this->pdo->beginTransaction();

            // Step 1: Insert into orders table
            $orderDate = date('Y-m-d H:i:s');
            $totalAmount = 0;

            foreach ($cartItems as $cartItem) {
                $totalAmount += $cartItem['price'] * $cartItem['quantity'];
            }

            $insertOrderQuery = "INSERT INTO orders (customerId, orderDate, totalAmount, orderStatus) 
                                VALUES (?, ?, ?, 'Pending')";
            $insertOrderStatement = $this->pdo->prepare($insertOrderQuery);
            $insertOrderStatement->execute([$customerId, $orderDate, $totalAmount]);

            // Step 2: Get the orderId of the newly inserted order
            $orderId = $this->pdo->lastInsertId();

            // Step 3: Insert into order_items table
            foreach ($cartItems as $cartItem) {
                $insertOrderItemQuery = "INSERT INTO order_items (orderId, productId, quantity) 
                                         VALUES (?, ?, ?)";
                $insertOrderItemStatement = $this->pdo->prepare($insertOrderItemQuery);
                $insertOrderItemStatement->execute($orderId, $cartItem['productId'], $cartItem['quantity']);
            }

            // Step 4: Clear the shopping cart (delete cart items)
            $clearCartQuery = "DELETE FROM cart_items 
                               WHERE cartId = (SELECT cartId FROM shopping_carts WHERE customerId = ?)";
            $clearCartStatement = $this->pdo->prepare($clearCartQuery);
            $clearCartStatement->execute([$customerId]);

            // Commit the transaction
            $this->pdo->commit();

            return true; // Order placement successful
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $this->pdo->rollBack();

            die("Error: " . $e->getMessage());
        }
    }

    // Method to retrieve the order history for a customer
    public function getOrderHistory($customerId)
    {
        try {
            $query = "SELECT * FROM orders WHERE customerId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$customerId]);

            $orderHistory = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $orderHistory;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to retrieve detailed information about a specific order
    public function getOrderDetails($orderId)
    {
        try {
            $query = "SELECT oi.orderItemId, p.productId, p.productName, p.price, oi.quantity
                      FROM order_items oi
                      JOIN products p ON oi.productId = p.productId
                      WHERE oi.orderId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$orderId]);

            $orderDetails = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $orderDetails;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
