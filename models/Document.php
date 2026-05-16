<?php
require_once __DIR__ . '/../classes/Database.php';

class Document
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByPolicyId(int $policyId): array
    {
        $stmt = $this->db->prepare('SELECT d.*, u.username AS uploaded_by_name FROM documents d JOIN users u ON d.uploaded_by = u.id WHERE d.policy_id = :policy_id ORDER BY d.uploaded_at DESC');
        $stmt->execute(['policy_id' => $policyId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM documents WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $policyId, string $filename, string $originalName, string $fileType, int $uploadedBy): int
    {
        $stmt = $this->db->prepare('INSERT INTO documents (policy_id, filename, original_name, file_type, uploaded_by) VALUES (:policy_id, :filename, :original_name, :file_type, :uploaded_by)');
        $stmt->execute([
            'policy_id' => $policyId,
            'filename' => $filename,
            'original_name' => $originalName,
            'file_type' => $fileType,
            'uploaded_by' => $uploadedBy
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $doc = $this->getById($id);
        if ($doc) {
            $filePath = __DIR__ . '/../uploads/' . $doc['filename'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $stmt = $this->db->prepare('DELETE FROM documents WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        }
        return false;
    }
}
