<?php

class DatabaseConnection
{
    private $host = "localhost";

    private $dbname = "store";
    private $username = "root";
    private $password = "";
    

    protected $connection;

    public function __construct()
{
    $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Set to ERRMODE_EXCEPTION
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $this->connection = new PDO($dsn, $this->username, $this->password, $options);
    } catch (PDOException $e) {
        die("Error connecting to the database: " . $e->getMessage());
    }
}


    public function getConnection()
    {
        return $this->connection;
    }
}
