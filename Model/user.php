<?php

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findByEmail($email)
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute(['email' => $email]);

        return $query->fetch() ?: null;
    }

    public function findById($id)
    {
        $query = $this->db->prepare(
            'SELECT id, name, email, role, created_at, updated_at
             FROM users WHERE id = :id'
        );
        $query->execute(['id' => $id]);

        return $query->fetch() ?: null;
    }

    public function getAll()
    {
        return $this->db->query(
            'SELECT id, name, email, role, created_at, updated_at
             FROM users ORDER BY created_at DESC'
        )->fetchAll();
    }

    public function emailExists($email, $exceptId = 0)
    {
        $query = $this->db->prepare(
            'SELECT id FROM users WHERE email = :email AND id != :id'
        );
        $query->execute(['email' => $email, 'id' => $exceptId]);

        return $query->fetch() !== false;
    }

    public function create($user)
    {
        $query = $this->db->prepare(
            'INSERT INTO users (name, email, password_hash, role)
             VALUES (:name, :email, :password_hash, :role)'
        );
        $query->execute($user);

        return (int) $this->db->lastInsertId();
    }

    public function update($id, $user)
    {
        $query = $this->db->prepare(
            'UPDATE users
             SET name = :name, email = :email, role = :role,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $user['id'] = $id;
        $query->execute($user);
    }

    public function updatePassword($id, $passwordHash)
    {
        $query = $this->db->prepare(
            'UPDATE users SET password_hash = :password_hash,
             updated_at = CURRENT_TIMESTAMP WHERE id = :id'
        );
        $query->execute(['password_hash' => $passwordHash, 'id' => $id]);
    }

    public function delete($id)
    {
        $query = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $query->execute(['id' => $id]);

        return $query->rowCount() > 0;
    }

    public function countAdmins()
    {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM users WHERE role = 'admin'"
        )->fetchColumn();
    }

    public function createInitialAdmin($email, $passwordHash)
    {
        if ($this->countAdmins() > 0) {
            return false;
        }

        $existingUser = $this->findByEmail($email);
        if ($existingUser !== null) {
            $query = $this->db->prepare(
                "UPDATE users SET role = 'admin', password_hash = :password_hash,
                 updated_at = CURRENT_TIMESTAMP WHERE id = :id"
            );
            $query->execute([
                'password_hash' => $passwordHash,
                'id' => $existingUser['id'],
            ]);
        } else {
            $this->create([
                'name' => 'Administrator',
                'email' => $email,
                'password_hash' => $passwordHash,
                'role' => 'admin',
            ]);
        }

        return true;
    }
}
