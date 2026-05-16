<?php
require_once __DIR__ . '/../classes/Database.php';

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username AND status = "active"');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getAll(): array
    {
        return $this->db->query('SELECT id, username, role, status, created_at FROM users ORDER BY id')->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, username, role, status, created_at FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $username, string $password, string $role): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $username, string $role, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET username = :username, role = :role, status = :status WHERE id = :id');
        return $stmt->execute([
            'username' => $username,
            'role' => $role,
            'status' => $status,
            'id' => $id
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute([
            'password' => password_hash($newPassword, PASSWORD_BCRYPT),
            'id' => $id
        ]);
    }

    public function deactivate(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET status = "inactive" WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
