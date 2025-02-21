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
        // Ensure nullable fields are explicitly set to NULL if they are empty
        $data['distance'] = !empty($data['distance']) ? $data['distance'] : null;
        $data['weight'] = !empty($data['weight']) ? $data['weight'] : null;
        $data['repetitions'] = !empty($data['repetitions']) ? $data['repetitions'] : null;
        $data['sets'] = !empty($data['sets']) ? $data['sets'] : null;
        $data['start_latitude'] = !empty($data['start_latitude']) ? $data['start_latitude'] : null;
        $data['start_longitude'] = !empty($data['start_longitude']) ? $data['start_longitude'] : null;
        $data['end_latitude'] = !empty($data['end_latitude']) ? $data['end_latitude'] : null;
        $data['end_longitude'] = !empty($data['end_longitude']) ? $data['end_longitude'] : null;

        $sql = "INSERT INTO workouts 
            (user_id, activity_id, date, duration, distance, weight, repetitions, sets, status,
            start_latitude, start_longitude, end_latitude, end_longitude) 
            VALUES 
            (:user_id, :activity_id, :date, :duration, :distance, :weight, :repetitions, :sets, :status, :start_latitude, :start_longitude, :end_latitude, :end_longitude)";

        $stmt = $this->db->prepare($sql);

        // Bind values explicitly, specifying `null` for nullable fields
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':activity_id', $data['activity_id'], PDO::PARAM_INT);
        $stmt->bindValue(':date', $data['date'], PDO::PARAM_STR);
        $stmt->bindValue(':duration', $data['duration'], PDO::PARAM_INT);
        $stmt->bindValue(':distance', $data['distance'], is_null($data['distance']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':weight', $data['weight'], is_null($data['weight']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':repetitions', $data['repetitions'], is_null($data['repetitions']) ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':sets', $data['sets'], is_null($data['sets']) ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_STR);

        $stmt->bindValue(':start_latitude', $data['start_latitude'], is_null($data['start_latitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':start_longitude', $data['start_longitude'], is_null($data['start_longitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':end_latitude', $data['end_latitude'], is_null($data['end_latitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':end_longitude', $data['end_longitude'], is_null($data['end_longitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        // Ensure nullable fields are explicitly set to NULL if they are empty
        $data['distance'] = !empty($data['distance']) ? $data['distance'] : null;
        $data['weight'] = !empty($data['weight']) ? $data['weight'] : null;
        $data['repetitions'] = !empty($data['repetitions']) ? $data['repetitions'] : null;
        $data['sets'] = !empty($data['sets']) ? $data['sets'] : null;
        $data['start_latitude'] = !empty($data['start_latitude']) ? $data['start_latitude'] : null;
        $data['start_longitude'] = !empty($data['start_longitude']) ? $data['start_longitude'] : null;
        $data['end_latitude'] = !empty($data['end_latitude']) ? $data['end_latitude'] : null;
        $data['end_longitude'] = !empty($data['end_longitude']) ? $data['end_longitude'] : null;

        $sql = "UPDATE workouts
        SET user_id = :user_id, 
            activity_id = :activity_id, 
            date = :date, 
            duration = :duration, 
            distance = :distance, 
            weight = :weight, 
            repetitions = :repetitions, 
            sets = :sets,
            status = :status,
            start_latitude = :start_latitude, 
            start_longitude = :start_longitude, 
            end_latitude = :end_latitude, 
            end_longitude = :end_longitude
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        // Bind values explicitly, ensuring correct data types
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':activity_id', $data['activity_id'], PDO::PARAM_INT);
        $stmt->bindValue(':date', $data['date'], PDO::PARAM_STR);
        $stmt->bindValue(':duration', $data['duration'], PDO::PARAM_INT);
        $stmt->bindValue(':distance', $data['distance'], is_null($data['distance']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindValue(':weight', $data['weight'], is_null($data['weight']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':repetitions', $data['repetitions'], is_null($data['repetitions']) ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':sets', $data['sets'], is_null($data['sets']) ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':status', $data['status'], PDO::PARAM_STR);

        $stmt->bindValue(':start_latitude', $data['start_latitude'], is_null($data['start_latitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':start_longitude', $data['start_longitude'], is_null($data['start_longitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':end_latitude', $data['end_latitude'], is_null($data['end_latitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':end_longitude', $data['end_longitude'], is_null($data['end_longitude']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->rowCount(); // Return the number of affected rows
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
        $sql = "SELECT 
	                w.*,
	                a.name AS activity,
	                a.calories_per_minute 
                FROM 
                workouts w 
                INNER JOIN activities a 
                ON a.id  = w.activity_id 
                WHERE w.user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $id]);
        return $stmt->fetchAll();
    }
}
