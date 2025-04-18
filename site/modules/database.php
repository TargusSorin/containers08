<?php

class Database {
    private $connection;

    public function __construct($path) {
        try {
            $this->connection = new PDO("sqlite:$path");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Eroare la conectarea la baza de date: " . $e->getMessage());
        }
    }

    public function Execute($sql) {
        try {
            return $this->connection->exec($sql);
        } catch (PDOException $e) {
            die("Eroare la executarea interogării: " . $e->getMessage());
        }
    }

    public function Fetch($sql) {
        try {
            $stmt = $this->connection->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Eroare la preluarea datelor: " . $e->getMessage());
        }
    }

    public function Create($table, $data) {
        try {
            $fields = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            die("Eroare la crearea înregistrării: " . $e->getMessage());
        }
    }

    public function Read($table, $id) {
        try {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Eroare la citirea înregistrării: " . $e->getMessage());
        }
    }

    public function Update($table, $id, $data) {
        try {
            $fields = [];
            foreach (array_keys($data) as $key) {
                $fields[] = "$key = :$key";
            }
            
            $fieldString = implode(", ", $fields);
            $sql = "UPDATE $table SET $fieldString WHERE id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Eroare la actualizarea înregistrării: " . $e->getMessage());
        }
    }

    public function Delete($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Eroare la ștergerea înregistrării: " . $e->getMessage());
        }
    }
    
    public function Count($table) {
        try {
            $sql = "SELECT COUNT(*) as count FROM $table";
            $stmt = $this->connection->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['count'];
        } catch (PDOException $e) {
            die("Eroare la numărarea înregistrărilor: " . $e->getMessage());
        }
    }
}