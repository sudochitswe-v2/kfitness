<?php

namespace K\Kfitness\Libs\Database;

use K\Kfitness\Libs\Database\MySQL;
use PDO;

class ActivitiesRepository
{
    private ?PDO $db = null;
    public function __construct(MySQL $sql)
    {
        $this->db = $sql->connect();
    }
    public function getAll()
    {
        $sql = "SELECT * FROM activities";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function getById($id)
    {
        $sql = "SELECT * FROM activities WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function create($data)
    {
        $sql = "INSERT INTO activities (name, calories_per_minute) VALUES (:name, :calories_per_minute)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
    public function update($data)
    {
        $sql = "UPDATE activities SET name = :name, calories_per_minute = :calories_per_minute WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
}
