<?php
require_once __DIR__ . '/../config/database.php';

class Publikasi
{
    public static function all($limit = 12, $offset = 0, $search = '', $dosen_id = null, $kategori_id = null)
    {
        global $pdo;
        $where = [];
        $params = [];
        if ($search) {
            $where[] = "LOWER(judul) LIKE ?";
            $params[] = '%' . strtolower($search) . '%';
        }
        if ($dosen_id) {
            $where[] = "EXISTS (SELECT 1 FROM publikasi_dosen pd WHERE pd.publikasi_id = publikasi.id AND pd.dosen_id = ? )";
            $params[] = $dosen_id;
        }
        if ($kategori_id) {
            $where[] = "EXISTS (SELECT 1 FROM publikasi_kategori pk WHERE pk.publikasi_id = publikasi.id AND pk.kategori_id = ? )";
            $params[] = $kategori_id;
        }
        $sql = "SELECT * FROM publikasi";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $sql .= " ORDER BY tanggal DESC, id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function count($search = '', $dosen_id = null, $kategori_id = null)
    {
        global $pdo;
        $where = [];
        $params = [];
        if ($search) {
            $where[] = "LOWER(judul) LIKE ?";
            $params[] = '%' . strtolower($search) . '%';
        }
        if ($dosen_id) {
            $where[] = "EXISTS (SELECT 1 FROM publikasi_dosen pd WHERE pd.publikasi_id = publikasi.id AND pd.dosen_id = ? )";
            $params[] = $dosen_id;
        }
        if ($kategori_id) {
            $where[] = "EXISTS (SELECT 1 FROM publikasi_kategori pk WHERE pk.publikasi_id = publikasi.id AND pk.kategori_id = ? )";
            $params[] = $kategori_id;
        }
        $sql = "SELECT COUNT(*) FROM publikasi";
        if ($where) $sql .= " WHERE " . implode(' AND ', $where);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public static function find($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM publikasi WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getDosen($publikasi_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT d.* FROM dosen d JOIN publikasi_dosen pd ON d.id = pd.dosen_id WHERE pd.publikasi_id = ?");
        $stmt->execute([$publikasi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getKategori($publikasi_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT k.* FROM kategori k JOIN publikasi_kategori pk ON k.id = pk.kategori_id WHERE pk.publikasi_id = ?");
        $stmt->execute([$publikasi_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data, $dosen_ids, $kategori_ids)
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO publikasi (judul, tanggal, file, link, deskripsi) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['judul'],
            $data['tanggal'],
            $data['file'],
            $data['link'],
            $data['deskripsi']
        ]);
        $publikasi_id = $pdo->lastInsertId();
        // Insert pivot dosen
        foreach ($dosen_ids as $dosen_id) {
            $pdo->prepare("INSERT INTO publikasi_dosen (publikasi_id, dosen_id) VALUES (?, ?)")->execute([$publikasi_id, $dosen_id]);
        }
        // Insert pivot kategori
        foreach ($kategori_ids as $kategori_id) {
            $pdo->prepare("INSERT INTO publikasi_kategori (publikasi_id, kategori_id) VALUES (?, ?)")->execute([$publikasi_id, $kategori_id]);
        }
        return $publikasi_id;
    }

    public static function update($id, $data, $dosen_ids, $kategori_ids)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE publikasi SET judul=?, tanggal=?, file=?, link=?, deskripsi=? WHERE id=?");
        $stmt->execute([
            $data['judul'],
            $data['tanggal'],
            $data['file'],
            $data['link'],
            $data['deskripsi'],
            $id
        ]);
        // Update pivot dosen
        $pdo->prepare("DELETE FROM publikasi_dosen WHERE publikasi_id = ?")->execute([$id]);
        foreach ($dosen_ids as $dosen_id) {
            $pdo->prepare("INSERT INTO publikasi_dosen (publikasi_id, dosen_id) VALUES (?, ?)")->execute([$id, $dosen_id]);
        }
        // Update pivot kategori
        $pdo->prepare("DELETE FROM publikasi_kategori WHERE publikasi_id = ?")->execute([$id]);
        foreach ($kategori_ids as $kategori_id) {
            $pdo->prepare("INSERT INTO publikasi_kategori (publikasi_id, kategori_id) VALUES (?, ?)")->execute([$id, $kategori_id]);
        }
    }

    public static function delete($id)
    {
        global $pdo;
        $pdo->prepare("DELETE FROM publikasi_dosen WHERE publikasi_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM publikasi_kategori WHERE publikasi_id = ?")->execute([$id]);
        $stmt = $pdo->prepare("DELETE FROM publikasi WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Ambil data publikasi lengkap dari view
    public static function allLengkap($limit = 100, $offset = 0)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM view_publikasi_lengkap ORDER BY tanggal DESC LIMIT ? OFFSET ?");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create publikasi menggunakan stored procedure
    public static function createSP($data, $kategori_ids, $dosen_ids)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT tambah_publikasi(?, ?, ?, ?, ?, ?) AS result");
        $stmt->execute([
            $data['judul'],
            $data['tanggal'],
            $data['file'],
            $data['link'],
            $kategori_ids,
            $dosen_ids
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['result'];
    }
}