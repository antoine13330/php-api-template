<?php

namespace AntoineD\Api\Models;

use AntoineD\Api\DB\Connection;
use \PDO;
use \PDOException;

require_once (__DIR__."/../DB/db-config.php");

class Model
{
    private string $table;
    public PDO $conn;
    public array $recurringUpdateDatas;
    public string $primaryKey;
    public bool $autoUpdateDates = false;
    public array $bannedFields = ['deleted' , 'creation_date' , 'change_date', 'id'];
    public array $authorizedForCreationOnly = [];
    public function __construct(string $table, bool $autoUpdateDates = false, array $suppBannedFields = [] , array $authorizedForCreationOnly = [])
    {   
        $this->table = $this->sanitizeTableName($table);
        $tempConn = new Connection();
        $this->conn = $tempConn->connect();
        $this->autoUpdateDates = $autoUpdateDates;
        $this->bannedFields = array_merge($this->bannedFields, $suppBannedFields);
        $this->authorizedForCreationOnly = $authorizedForCreationOnly;
    }

    private function sanitizeTableName(string $table): string
    {
        // Sanitariser le nom de la table
        return preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    }

    public function all(): array
    {
        try {
            $query = $this->conn->prepare("SELECT * FROM {$this->table} WHERE deleted = false");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function find(int $id): array
    {
        try {
            $query = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id AND deleted = false");
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function create(array $data): int
    {
        try {
            foreach ($this->bannedFields as $field) {
                if (in_array($field, $this->authorizedForCreationOnly)) {
                    continue;
                }
                unset($data[$field]);
            }
            if ($this->autoUpdateDates) {
                $data['creation_date'] = date('Y-m-d H:i:s');
                $data['change_date'] = date('Y-m-d H:i:s');
            }
            $fields = implode(', ', array_keys($data));
            $values = ':' . implode(', :', array_keys($data));
            $query = $this->conn->prepare("INSERT INTO {$this->table} ($fields) VALUES ($values)");
            $query->execute($data);
            return (int) $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): int
    {
        try {
            foreach ($this->bannedFields as $field) {
                unset($data[$field]);
            }
            if ($this->autoUpdateDates) {
                $data['change_date'] = date('Y-m-d H:i:s');
            }
            $fields = '';
            foreach ($data as $key => $value) {
                $fields .= $key . ' = :' . $key . ', ';
            }
            $fields = rtrim($fields, ', ');
            $data['id'] = $id;
            $query = $this->conn->prepare("UPDATE {$this->table} SET $fields WHERE id = :id");
            $query->execute($data);
            return $query->rowCount();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function delete(int $id): int
    {
        try {
            $query = $this->conn->prepare("UPDATE {$this->table} SET deleted = false WHERE id = :id");
            $query->execute(['id' => $id]);
            return $query->rowCount();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}
