<?php

class CustomerModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to register a new customer
    public function registerCustomer($firstName, $lastName, $email, $password, $address, $phoneNumber)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO customers (firstName, lastName, email, password, address, phoneNumber) 
                      VALUES (?, ?, ?, ?, ?, ?)";

            $statement = $this->pdo->prepare($query);
            $statement->execute([$firstName, $lastName, $email, $hashedPassword, $address, $phoneNumber]);

            return true; // Registration successful
        } catch (PDOException $e) {
            // Handle the exception (e.g., log it, show an error message)
            die("Error: " . $e->getMessage());
        }
    }

    // Method to authenticate and log in a customer
    public function loginCustomer($email, $password)
    {
        try {
            $query = "SELECT * FROM customers WHERE email = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$email]);

            $customer = $statement->fetch(PDO::FETCH_ASSOC);

            if ($customer && password_verify($password, $customer['password'])) {
                // Password is correct, return customer information
                return $customer;
            }

            return null; // Authentication failed
        } catch (PDOException $e) {
            // Handle the exception (e.g., log it, show an error message)
            die("Error: " . $e->getMessage());
        }
    }

    // Method to update customer profile information
    public function updateCustomerProfile($customerId, $firstName, $lastName, $address, $phoneNumber)
    {
        try {
            $query = "UPDATE customers 
                      SET firstName = ?, lastName = ?, address = ?, phoneNumber = ? 
                      WHERE customerId = ?";

            $statement = $this->pdo->prepare($query);
            $statement->execute([$firstName, $lastName, $address, $phoneNumber, $customerId]);

            return true; // Update successful
        } catch (PDOException $e) {
            // Handle the exception (e.g., log it, show an error message)
            die("Error: " . $e->getMessage());
        }
    }

    // Method to retrieve customer information by ID
    public function getCustomerById($customerId)
    {
        try {
            $query = "SELECT * FROM customers WHERE customerId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$customerId]);

            $customer = $statement->fetch(PDO::FETCH_ASSOC);

            return $customer;
        } catch (PDOException $e) {
            // Handle the exception (e.g., log it, show an error message)
            die("Error: " . $e->getMessage());
        }
    }
}
