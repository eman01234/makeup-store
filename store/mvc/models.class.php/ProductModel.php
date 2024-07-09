<?php

class ProductModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Method to retrieve featured products for the home page
    public function getFeaturedProducts()
    {
        try {
            $query = "SELECT * FROM products WHERE isFeatured = 1 LIMIT 5";
            $statement = $this->pdo->query($query);

            $featuredProducts = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $featuredProducts;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to retrieve product categories for navigation
    public function getProductCategories()
    {
        try {
            $query = "SELECT * FROM categories";
            $statement = $this->pdo->query($query);

            $categories = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $categories;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method to retrieve products in a specific category for the product listing page
    public function getProductsInCategory($categoryId)
    {
        try {
            $query = "SELECT * FROM products WHERE categoryId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$categoryId]);

            $productsInCategory = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $productsInCategory;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    

    // Method to retrieve detailed information about a specific product for the product details page
    public function getProductDetails($productId)
    {
        try {
            $query = "SELECT * FROM products WHERE productId = ?";
            $statement = $this->pdo->prepare($query);
            $statement->execute([$productId]);

            $productDetails = $statement->fetch(PDO::FETCH_ASSOC);

            return $productDetails;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
