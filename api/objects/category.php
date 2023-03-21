<?php

class Category
{

    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        try {
            $query = "SELECT 
            id, name, description 
        FROM 
            " . $this->table_name . " 
        ORDER BY 
            name";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
        return null;
    }
}
