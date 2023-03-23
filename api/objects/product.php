<?php

class Product
{
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        try {
            $query = "SELECT c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM 
            " . $this->table_name . " p 
            LEFT JOIN 
                categories c 
            ON 
                p.category_id = c.id 
            ORDER BY 
                p.created DESC";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
    }

    public function create()
    {
        try {
            $query = "INSERT INTO 
            " . $this->table_name . " 
            SET 
                name=:name, 
                price=:price, 
                description=:description, 
                category_id=:category_id, 
                created=:created";

            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->created = htmlspecialchars(strip_tags($this->created));

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":category_id", $this->category_id);
            $stmt->bindParam(":created", $this->created);
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
        return false;
    }

    public function readOne()
    {
        try {
            $query = "SELECT 
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
            FROM 
            " . $this->table_name . " p 
            LEFT JOIN 
                categories c 
            ON p.category_id = c.id 
            WHERE 
                p.id = ? 
            LIMIT 
                0,1";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->name = $row['name'] ?? null;
                $this->price = $row['price'] ?? null;
                $this->description = $row['description'] ?? null;
                $this->category_id = $row['category_id'] ?? null;
                $this->category_name = $row['category_name'] ?? null;
            }
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
    }

    public function update()
    {
        try {
            $query = "UPDATE 
            " . $this->table_name . " 
            SET 
                name = :name,
                price = :price,
                description = :description,
                category_id = :category_id 
            WHERE 
                id = :id";

            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->price = htmlspecialchars(strip_tags($this->price));
            $this->description = htmlspecialchars(strip_tags($this->description));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":category_id", $this->category_id);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
        return false;
    }

    public function delete()
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(1, $this->id);

            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
        return false;
    }

    public function search($keywords)
    {
        try {
            $query = "SELECT 
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created 
                    FROM 
                        " . $this->table_name . " p 
                    LEFT JOIN 
                        categories c 
                    ON 
                        p.category_id = c.id 
                    WHERE 
                        p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? 
                    ORDER BY 
                        p.created DESC";

            $stmt = $this->conn->prepare($query);

            $keywords = htmlspecialchars(strip_tags($keywords));
            $keywords = "%{$keywords}%";

            $stmt->bindParam(1, $keywords);
            $stmt->bindParam(2, $keywords);
            $stmt->bindParam(3, $keywords);

            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            echo 'Ошибка! Сообщание: ' . $ex->getMessage();
        }
        return null;
    }

    // получение товаров с пагинацией
    public function readPaging($from_record_num, $records_per_page)
    {
        // выборка
        $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        ORDER BY p.created DESC
        LIMIT ?, ?";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // свяжем значения переменных
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // выполняем запрос
        $stmt->execute();

        // вернём значения из базы данных
        return $stmt;
    }

    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["total_rows"];
    }
}
