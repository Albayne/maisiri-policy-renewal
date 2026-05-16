<?php
require_once __DIR__ . '/../classes/Database.php';

class Policy
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT p.*, u.username AS created_by_name FROM policies p JOIN users u ON p.created_by = u.id ORDER BY p.renewal_date ASC');
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT p.*, u.username AS created_by_name FROM policies p JOIN users u ON p.created_by = u.id WHERE p.id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO policies (policy_number, client_name, insurance_type, premium_amount, start_date, renewal_date, status, created_by) VALUES (:policy_number, :client_name, :insurance_type, :premium_amount, :start_date, :renewal_date, :status, :created_by)');
        $stmt->execute([
            'policy_number' => $data['policy_number'],
            'client_name' => $data['client_name'],
            'insurance_type' => $data['insurance_type'],
            'premium_amount' => $data['premium_amount'],
            'start_date' => $data['start_date'],
            'renewal_date' => $data['renewal_date'],
            'status' => $data['status'],
            'created_by' => $data['created_by']
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE policies SET policy_number = :policy_number, client_name = :client_name, insurance_type = :insurance_type, premium_amount = :premium_amount, start_date = :start_date, renewal_date = :renewal_date, status = :status WHERE id = :id');
        return $stmt->execute([
            'policy_number' => $data['policy_number'],
            'client_name' => $data['client_name'],
            'insurance_type' => $data['insurance_type'],
            'premium_amount' => $data['premium_amount'],
            'start_date' => $data['start_date'],
            'renewal_date' => $data['renewal_date'],
            'status' => $data['status'],
            'id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM policies WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function getDashboardStats(): array
    {
        $total = $this->db->query('SELECT COUNT(*) FROM policies')->fetchColumn();
        $active = $this->db->query('SELECT COUNT(*) FROM policies WHERE status = "Active"')->fetchColumn();
        $expired = $this->db->query('SELECT COUNT(*) FROM policies WHERE status = "Expired"')->fetchColumn();
        $nearRenewal = $this->db->query(
            'SELECT COUNT(*) FROM policies WHERE status = "Active" AND renewal_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)'
        )->fetchColumn();

        return [
            'total' => (int)$total,
            'active' => (int)$active,
            'expired' => (int)$expired,
            'nearRenewal' => (int)$nearRenewal,
        ];
    }

    public function getByPolicyNumber(string $policyNumber): ?array
    {
        $stmt = $this->db->prepare('SELECT id FROM policies WHERE policy_number = :policy_number');
        $stmt->execute(['policy_number' => $policyNumber]);
        return $stmt->fetch() ?: null;
    }
}
