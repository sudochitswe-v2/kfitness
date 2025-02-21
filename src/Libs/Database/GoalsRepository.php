<?php

namespace K\Kfitness\Libs\Database;

use PDO;

class GoalsRepository
{
    private ?PDO $db = null;
    function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }
    public function getAll()
    {
        $sql = "SELECT * FROM goals";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function create($data)
    {
        $sql = "INSERT INTO goals 
        (user_id, description, target_value, start_date, end_date, status) 
        VALUES 
        (:user_id, :description, :target_value, :start_date, :end_date, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    public function update($data)
    {
        $sql = "UPDATE goals SET
        description = :description,
        target_value = :target_value,
        start_date = :start_date,
        end_date = :end_date,
        user_id = :user_id,
        status = :status
        WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
    public function delete($id)
    {
        $sql = "DELETE FROM goals WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
    public function details($id)
    {
        $sql = "SELECT * FROM goals WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $detail = $stmt->fetch(PDO::FETCH_ASSOC);
        $progessesTable = new ProgressesRepository(new MySQL());
        $detail['progresses'] = $progessesTable->progresses($id);
        return $detail;
    }
    public function userGoals($user_id)
    {
        $sql = "SELECT * FROM goals WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}
