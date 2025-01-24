<?php

namespace K\Kfitness\Libs\Database;

use PDO;

class WorkoutsRepository
{
    private ?PDO $db = null;
    function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }
    public function getAll()
    {
        $sql = "SELECT * FROM workouts";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function create($data)
    {
        $sql = "INSERT INTO workouts 
        (user_id, activity_id, date, duration, distance, weight, repetitions, sets) 
        VALUES 
        (:user_id, :activity_id, :date, :duration, :distance, :weight, :repetitions, :sets)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    public function update($data)
    {
        $sql = "UPDATE workouts
        SET user_id = :user_id, activity_id = :activity_id, date = :date, duration = :duration, distance = :distance, weight = :weight, repetitions = :repetitions, sets = :sets
        WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }
    public function details($id)
    {
        $sql = "SELECT * FROM workouts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $workout = $stmt->fetch();
        $workout['activity'] = (new ActivitiesRepository(new MySQL()))->getById($workout['activity_id']);
        return $workout;
    }
    public function delete($id)
    {
        $sql = "DELETE FROM workouts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
    public function getByUserId($id)
    {
        $sql = "SELECT * FROM workouts WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $id]);
        return $stmt->fetchAll();
    }
}
