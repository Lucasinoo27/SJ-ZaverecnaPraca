<?php
class Database {
    private $host = "localhost";
    private $db_name = "todo_list";
    private $username = "root";
    private $password = "";
    public $conn;

    // Konštruktor triedy, ktorý zavolá metódu initializeDatabase
    public function __construct() {
        $this->initializeDatabase();
    }

    // Metóda na získanie pripojenia k databáze
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    // Metóda na inicializáciu databázy a vytvorenie potrebných tabuliek
    private function initializeDatabase() {
        try {
            // Pripojenie k MySQL serveru
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vytvorenie databázy, ak neexistuje
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name);
            $this->conn->exec("USE " . $this->db_name);

            // Vytvorenie tabuľky users, ak neexistuje
            $this->conn->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Vytvorenie tabuľky tasks, ak neexistuje
            $this->conn->exec("CREATE TABLE IF NOT EXISTS tasks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                status ENUM('pending', 'completed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
        } catch (PDOException $exception) {
            echo "Database initialization error: " . $exception->getMessage();
        }
    }
}

// Inštancia triedy Database na inicializáciu a pripojenie
$database = new Database();
$db = $database->getConnection();
?>
