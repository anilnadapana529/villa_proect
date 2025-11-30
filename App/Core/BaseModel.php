<?php

namespace App\Core;

use App\Core\Database;
use mysqli;

class BaseModel
{
    protected mysqli $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Find by id */
    public function find(int $id): ?array
    {
        $q = $this->db->query("
            SELECT * FROM {$this->table} WHERE id=$id LIMIT 1
        ");

        if ($q->num_rows === 0) return null;
        return $q->fetch_assoc();
    }

    /** Get all rows */
    public function all(): array
    {
        $q = $this->db->query("
            SELECT * FROM {$this->table}
        ");

        return $q->fetch_all(MYSQLI_ASSOC);
    }

    /** Delete a row */
    public function delete(int $id): bool
    {
        return $this->db->query("
            DELETE FROM {$this->table} WHERE id=$id
        ");
    }

    /** Insert array into DB */
    public function insert(array $data): int
    {
        $columns = implode(",", array_keys($data));
        $values = "'" . implode("','", array_map([$this->db, 'real_escape_string'], array_values($data))) . "'";

        $this->db->query("
            INSERT INTO {$this->table} ($columns) 
            VALUES ($values)
        ");

        return $this->db->insert_id;
    }

    /** Update */
    public function update(int $id, array $data): bool
    {
        $pairs = [];

        foreach ($data as $key => $value) {
            $value = $this->db->real_escape_string($value);
            $pairs[] = "$key='$value'";
        }

        $setQuery = implode(",", $pairs);

        return $this->db->query("
            UPDATE {$this->table} 
            SET $setQuery 
            WHERE id=$id
        ");
    }
}
