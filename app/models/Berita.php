<?php
require_once __DIR__ . '/../config/database.php';
class Berita
{
    public static function all($limit = 6, $offset = 0, $search = '')
    {
        global $pdo;
        $where = [];
        $params = [];
        if ($search) {
            $where[] = "LOWER(judul) LIKE ? OR LOWER(isi) LIKE ?";
            $params[] = '%' . strtolower($search) . '%';
            $params[] = '%' . strtolower($search) . '%';
        }
        $sql = "SELECT * FROM berita";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $sql .= " ORDER BY tanggal DESC, id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function count($search = '')
    {
        global $pdo;
        $where = [];
        $params = [];
        if ($search) {
            $where[] = "LOWER(judul) LIKE ? OR LOWER(isi) LIKE ?";
            $params[] = '%' . strtolower($search) . '%';
            $params[] = '%' . strtolower($search) . '%';
        }
        $sql = "SELECT COUNT(*) FROM berita";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function create($data)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO berita (judul, isi, gambar, tanggal, penulis) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['judul'],
            $data['isi'],
            $data['gambar'],
            $data['tanggal'],
            $data['penulis']
        ]);
        return $pdo->lastInsertId();
    }
    public static function update($id, $data)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE berita SET judul=?, isi=?, gambar=?, tanggal=?, penulis=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([
            $data['judul'],
            $data['isi'],
            $data['gambar'],
            $data['tanggal'],
            $data['penulis'],
            $id
        ]);
    }
    public static function delete($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM berita WHERE id=?");
        $stmt->execute([$id]);
    }
}
