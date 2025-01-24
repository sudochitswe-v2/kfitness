<?php

namespace K\Kfitness\Libs\Database;

use PDO;

class UsersRepository
{
    private ?PDO $db = null;
    public function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }

    public function insert($data)
    {

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['registration_date'] = date('Y-m-d H:i:s');

        // create query statement with sql paramters
        $query = " INSERT INTO  users 
            (name, email, password, registration_date) 
            VALUES 
            (:name, :email, :password, :registration_date )";

        // bind sql parameters
        $statement = $this->db->prepare($query);
        return  $statement->execute($data);
    }

    public function findByEmail($email)
    {
        $statement = $this->db->prepare("
            SELECT *
            FROM users WHERE email = :email
        ");

        $statement->execute([
            ':email' => $email,
        ]);

        $row = $statement->fetch();

        return $row ?? false;
    }
    public function delete($id)
    {
        $statement = $this->db->prepare("
            DELETE FROM users WHERE id = :id
        ");

        return $statement->execute([':id' => $id]);
    }
}
