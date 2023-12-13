<?php

trait Connection
{
    private $conn;

    function __construct()
    {
        try {
            mysqli_report(MYSQLI_REPORT_OFF);
            // Establishing connection
            $config = require "./core/config.php";
            $this->conn = new mysqli($config['database']['hostname'], $config['database']['username'], $config['database']['password'], $config['database']['db']);

        } catch (Exception $ex) {
            die("Some error occured: " . $ex->getMessage());
        }
    }

    function __destruct()
    {
        $this->conn->close();
    }
}

interface CRUD
{
    public function add($table, $data);
    public function update($table, $updateStr, $searchId, $searchCriteria = 'id');
    public function delete($table, $id, $criteria = 'id');
    public function selectAll($table);
    public function selectAllSpecific($table, $searchId, $searchCriteria);
    public function selectOne($table, $id);
    public function selectNegate($table, $searchId, $searchCriteria = 'id');
    public function selectColumn($column, $table, $searchId, $searchCriteria);
    public function selectAllJoin($table1, $joins);
    public function selectOneJoin($table, $joins, $columnStr, $searchId, $searchCriteria = 'id');
    public function rowCount($table);
    public function lastEntry($table, $column = 'uuid');
}

class DatabaseQuery implements CRUD
{
    use Connection;
    public function add($table, $data)
    {
        $date = date("Y-m-d H:i:s");
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ({$columns}, `creation_date`, `modification_date`) VALUES ('$values', '$date', '$date')";
        if (!$this->conn->query($sql)) {
            die("Error creating user: " . $this->conn->error);
        }
    }
    public function update($table, $updateStr, $searchId, $searchCriteria = 'id')
    {
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE $table SET $updateStr modification_date = '$date' WHERE $searchCriteria='$searchId'";
        if (!$this->conn->query($sql)) {
            die("Error locking user: " . $this->conn->error);
        }
    }
    public function delete($table, $id, $criteria = 'id')
    {
        $sql = "DELETE FROM $table WHERE $criteria='$id'";
        if (!$this->conn->query($sql)) {
            die("Error deleting user: " . $this->conn->error);
        }
    }
    public function selectAll($table)
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function selectAllSpecific($table, $searchId, $searchCriteria)
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function selectOne($table, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_assoc();
    }

    public function selectNegate($table, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria != $searchId";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function selectColumn($column, $table, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT $column FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    public function selectColumnMultiCondition($column, $table, $conditions)
    {
        $sql = "SELECT $column FROM $table WHERE {$conditions[0]['criteria']} = '{$conditions[0]['id']}'";
        unset($conditions[0]);
        foreach ($conditions as $condition) {
            $sql .= " AND {$condition['criteria']} = '{$condition['id']}'";
        }
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    public function selectAllJoin($table, $joins)
    {
        $sql = "SELECT * FROM $table";

        foreach ($joins as $join) {
            $sql .= " LEFT JOIN {$join['table']} ON {$join['condition']}";
        }

        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching user: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function selectAllJoinSpecific($table, $joins, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT * FROM $table";

        foreach ($joins as $join) {
            $sql .= " LEFT JOIN {$join['table']} ON {$join['condition']}";
        }
        $sql .= " WHERE $searchCriteria='$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching user: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function selectOneJoin($table, $joins, $columnStr, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT $columnStr FROM $table";

        foreach ($joins as $join) {
            $sql .= " LEFT JOIN {$join['table']} ON {$join['condition']}";
        }
        $sql .= " WHERE $searchCriteria='$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching user: " . $this->conn->error);
        }
        return $result->fetch_assoc();
    }

    public function rowCount($table)
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->num_rows;
    }

    public function lastEntry($table, $column = 'uuid')
    {
        $sql = "SELECT $column FROM $table ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    public function selectPartial($table, $columns, $search, $conditions=[])
    {
        $sql="SELECT * FROM $table WHERE ($columns[0] LIKE '%$search%'";
        unset($columns[0]);
        foreach($columns as $column)
        {
            $sql .= " OR $column LIKE '%$search%'";
        }
        $sql .= ")";
        foreach($conditions as $condition)
        {
            $sql .= " AND {$condition['criteria']} = {$condition['id']}";
        }
        echo $sql;
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}