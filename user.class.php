<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;

    // Konštruktor triedy, ktorý prijíma pripojenie k databáze
    public function __construct($db) {
        $this->conn = $db;
    }

    // Metóda na kontrolu, či používateľské meno už existuje
    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));

        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // Metóda na registráciu užívateľa
    public function register() {
        if ($this->usernameExists()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password";
        $stmt = $this->conn->prepare($query);

        // Vyčistenie vstupov
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Viazanie parametrov
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        // Vykonanie dopytu
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metóda na prihlásenie užívateľa
    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);

        // Vyčistenie vstupov
        $this->username = htmlspecialchars(strip_tags($this->username));

        // Viazanie parametru
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        // Overenie užívateľského mena a hesla
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                return true;
            }
        }
        return false;
    }
}
?>
