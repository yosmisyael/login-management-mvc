<?php

namespace Yosev\Login\Management\Repository;

use Yosev\Login\Management\Domain\User;

class UserRepository
{

    public function __construct(private \PDO $db)
    {
        
    }

    public function store(User $user): User
    {
        
        $sql = "INSERT INTO users (id, name, password) VALUES (?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([$user->id, $user->name, $user->password]);
        
        return $user;
    
    }

    public function update(User $user): User
    {
        $statement = $this->db->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $statement->execute([
            $user->name,
            $user->password,
            $user->id
        ]);
        
        return $user;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->db->prepare("SELECT id, name, password FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function destroyAll(): void
    {
        $this->db->exec("DELETE FROM users");
    }
    
} 
