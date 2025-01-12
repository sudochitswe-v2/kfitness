<?php

namespace K\Kfitness\Libs\Database;



class UsersRepository
{
    private $db = null;

    public function __construct(MySQL $db)
    {
        $this->db = $db->connect();
    }

    public function insert($data)
    {

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // create query statement with sql paramters
        $query = " INSERT INTO  users 
            (name, email, password,date_of_birth, height, weight) 
            VALUES 
            (:name, :email, :password,:date_of_birth, :height, :weight )";

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
