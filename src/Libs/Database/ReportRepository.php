<?php

namespace K\Kfitness\Libs\Database;

use PDO;

class ReportRepository
{
    private ?PDO $db = null;
    function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }
    public function getReport($data)
    {
        $sql1 = "SELECT
                    	a.name AS activity,
                    	w.`date`,
                    	w.status,
                    	w.duration 
                    FROM 
                    workouts w 
                    INNER JOIN activities a 
                    ON a.id  = w.activity_id 
                    WHERE w.user_id  = :user_id
                    AND w.`date` LIKE :date";

        $sql2 = "SELECT *
            FROM goals g 
            WHERE :date BETWEEN start_date AND end_date
            AND :user_id = user_id ";

        $sql3 = "SELECT SUM(w.duration * a.calories_per_minute) AS total_calories_burned
            FROM workouts w
            JOIN activities a ON w.activity_id = a.id
            WHERE w.user_id = :user_id
            AND w.`date` LIKE :date ";
        try {
            $statement1 = $this->db->prepare($sql1);
            $statement1->execute($data);

            $statement2 = $this->db->prepare($sql2);
            $statement2->execute($data);

            $statement3 = $this->db->prepare($sql3);
            $statement3->execute($data);

            $workouts = $statement1->fetchAll(\PDO::FETCH_ASSOC);
            $goals = $statement2->fetchAll(\PDO::FETCH_ASSOC);
            $calories = $statement3->fetchColumn();
            $response = array(
                'workouts' => $workouts,
                'goals' => $goals,
                'calories' => $calories
            );
            return $response;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
