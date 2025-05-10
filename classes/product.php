<?php
require_once '../db.php';

class Product{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        return $this->conn->query("SELECT * FROM products");
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($name, $price){
        $stmt = $this->conn->prepare("INSERT INTO products (name, price) VALUES (?,?)");
        $stmt->bind_param('sd', $name, $price);
        return $stmt->execute();
    }

    public function update($id, $name, $price){
        $stmt = $this->conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
        $stmt->bind_param('sdi', $name, $price, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}