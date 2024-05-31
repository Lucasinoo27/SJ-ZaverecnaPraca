<?php
class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $status;
    public $created_at;

    // Konštruktor triedy, ktorý prijíma pripojenie k databáze
    public function __construct($db) {
        $this->conn = $db;
    }

    // Metóda na načítanie úloh pre konkrétneho užívateľa
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    // Metóda na vytvorenie novej úlohy
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET user_id=:user_id, title=:title, description=:description, status=:status";
        $stmt = $this->conn->prepare($query);

        // Vyčistenie vstupov
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Viazanie parametrov
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":user_id", $this->user_id);

        // Vykonanie dopytu
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metóda na aktualizáciu existujúcej úlohy
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET title = :title, description = :description, status = :status WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Vyčistenie vstupov
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        // Viazanie parametrov
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        // Vykonanie dopytu
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Metóda na zmazanie úlohy
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);

        // Vyčistenie vstupov
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);

        // Vykonanie dopytu
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
