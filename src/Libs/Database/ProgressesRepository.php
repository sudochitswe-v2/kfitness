<?php

namespace  K\Kfitness\Libs\Database;

use PDO;

class ProgressesRepository
{
    private ?PDO $db = null;
    function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }
    function getAll()
    {
        $sql = "SELECT * FROM progresses";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function create($data)
    {
        $sql = "INSERT INTO progresses 
        (goal_id, date, current_value, notes) 
        VALUES 
        (:goal_id, :date, :current_value, :notes)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    function update($data)
    {
        $sql = "UPDATE progresses SET
        goal_id = :goal_id,
        date = :date,
        current_value = :current_value,
        notes = :notes
        WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
    function delete($id)
    {
        $sql = "DELETE FROM progresses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
    function progresses($goalId)
    {
        $sql = "SELECT * FROM progresses WHERE goal_id = :goal_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['goal_id' => $goalId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
