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
    public function selectColumn($column, $table, $searchId, $searchCriteria);
    public function selectAllJoin($table1, $table1MatchCol, $table2, $table2MatchCol);
    public function selectOneJoin($table1, $table1MatchCol, $table2, $table2MatchCol, $columnStr, $searchId, $searchCriteria = 'id');
    public function rowCount($table);
    public function lastEntry($table, $column='uuid');
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
    public function selectColumn($column, $table, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT $column FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    public function selectAllJoin($table1, $table1MatchCol, $table2, $table2MatchCol)
    {
        $sql = "SELECT *, $table1.creation_date, $table1.modification_date AS {$table1}_modification_date, $table2.modification_date AS {$table2}_modification_date FROM $table1 LEFT JOIN $table2 ON $table1.$table1MatchCol = $table2.$table2MatchCol ORDER BY $table1.$table1MatchCol";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching user: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function selectOneJoin($table1, $table1MatchCol, $table2, $table2MatchCol, $columnStr, $searchId, $searchCriteria = 'id')
    {
        $sql = "SELECT $columnStr, $table1.creation_date AS {$table1}_creation_date, $table1.modification_date AS {$table1}_modification_date, $table2.creation_date AS {$table2}_creation_date, $table2.modification_date AS {$table2}_modification_date FROM $table1 LEFT JOIN $table2 ON $table1.$table1MatchCol = $table2.$table2MatchCol WHERE $searchCriteria='$searchId'";
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

    public function lastEntry($table, $column='uuid')
    {
        $sql="SELECT $column FROM $table ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }
}