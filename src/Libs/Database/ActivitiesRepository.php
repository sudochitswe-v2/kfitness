<?php

namespace K\Kfitness\Libs\Database;

use K\Kfitness\Libs\Database\MySQL;

class ActivitiesRepository
{
    private $db = null;
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
        $sql = "INSERT INTO activities (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
}
