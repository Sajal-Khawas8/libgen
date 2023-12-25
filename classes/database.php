<?php

/**
 * This trait contains constructor and destructor to establish and destroy connection with the database respectively
 */
trait Connection
{
    private $conn;

    /**
     * This constructor establishes connection with the database
     */
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
        // Closing the connection
        $this->conn->close();
    }
}

/**
 * This interface contains the rules for writing db methods
 */
interface CRUD
{
    public function add(string $table, array $data);
    public function update(string $table, string $updateStr, mixed $searchId, string $searchCriteria = 'id');
    public function delete(string $table, mixed $id, string $criteria = 'id');
    public function selectAll(string $table);
    public function selectAllSpecific(string $table, mixed $searchId, string $searchCriteria);
    public function selectOne(string $table, mixed $id);
    public function selectNegate(string $table, mixed $searchId, string $searchCriteria = 'id');
    public function selectColumn(string $column, string $table, mixed $searchId, string $searchCriteria);
    public function selectAllJoin(string $table, array $joins);
    public function selectOneJoin(string $table, array $joins, string $columnStr, mixed $searchId, string $searchCriteria = 'id');
    public function rowCount(string $table);
    public function lastEntry(string $table, string $column = 'uuid');
}

/**
 * This class contains methods to perform CRUD operations on the database
 */
class DatabaseQuery implements CRUD
{
    /**
     * Uses the Connection trait which performs following functions:
     * 1. Establishes connection with the database (through constructor)
     * 2. Destroys the connection after operation (through destructor)
     */
    use Connection;

    /**
     * This method adds data to a table
     * 
     * @param string $table The name of the table
     * @param array $data The data to insert in key value pair, where keys will be the names of the columns
     * and values will be the values to add
     * @return void
     */
    public function add(string $table, array $data): void
    {
        $date = date("Y-m-d H:i:s");
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_values($data));
        $sql = "INSERT INTO $table ({$columns}, `creation_date`, `modification_date`) VALUES ('$values', '$date', '$date')";
        if (!$this->conn->query($sql)) {
            die("Error creating user: " . $this->conn->error);
        }
    }

    /**
     * This method updates data in a table
     * 
     * @param string $table The name of the table
     * @param string $updateStr The update string
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return void
     */
    public function update(string $table, string $updateStr, mixed $searchId, string $searchCriteria = 'id'): void
    {
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE $table SET $updateStr, modification_date = '$date' WHERE $searchCriteria='$searchId'";
        if (!$this->conn->query($sql)) {
            die("Error locking user: " . $this->conn->error);
        }
    }

    /**
     * This method deletes a row from the table
     * 
     * @param string $table The name of the table
     * @param mixed $id The value to search
     * @param string $criteria The column in which the $id needs to be searched. Defaults to 'id'.
     * @return void
     */
    public function delete(string $table, mixed $id, string $criteria = 'id'): void
    {
        $sql = "DELETE FROM $table WHERE $criteria='$id'";
        if (!$this->conn->query($sql)) {
            die("Error deleting user: " . $this->conn->error);
        }
    }

    /**
     * This method selects and returns all the data from the table
     * 
     * @param string $table The name of the table
     * @return array Returns the rows of the table
     */
    public function selectAll(string $table): array
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * This method selects and returns all rows from the table which matches the $searchId
     * 
     * @param string $table The name of the table
     * @param mixed $searchId The value to search
     * @param string $serachCriteria The column in which the $searchId needs to be searched.
     * @return array Returns the rows from the table
     */
    public function selectAllSpecific(string $table, mixed $searchId, string $searchCriteria): array
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * This method selects and returns one row from the table which matches the $searchId
     * 
     * @param string $table The name of the table
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return mixed Returns one row from the table
     */
    public function selectOne(string $table, mixed $searchId, string $searchCriteria = 'id'): mixed
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_assoc();
    }

    /**
     * This method selects and returns rows from the table which does not match with the $searchId
     * 
     * @param string $table The name of the table
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return array Returns rows from the table
     */
    public function selectNegate(string $table, mixed $searchId, string $searchCriteria = 'id'): array
    {
        $sql = "SELECT * FROM $table WHERE $searchCriteria != $searchId";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * This method selects and returns one value from the table which matches the $searchId
     * 
     * @param string $column The column to find
     * @param string $table The name of the table
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return mixed Returns the value of the searched column from the table
     */
    public function selectColumn(string $column, string $table, mixed $searchId, string $searchCriteria = 'id'): mixed
    {
        $sql = "SELECT $column FROM $table WHERE $searchCriteria = '$searchId'";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    /**
     * This method selects and returns all rows from the table which matches the conditions using AND operator
     * 
     * @param string $table The name of the table
     * @param array $conditions The conditions in key value pair where key 'criteria' will be the column name
     * and value 'id' will be the value of the column
     * @return mixed Returns the value of the searched column from the table
     */
    public function selectAllMultiCondition(string $table, array $conditions): mixed
    {
        $sql = "SELECT * FROM $table WHERE ";
        foreach ($conditions as $column => $value) {
            $sql .= "$column = '$value' AND ";
        }
        $sql=rtrim($sql, " AND ");
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * This method selects and returns one value from the table which matches the conditions using AND operator
     * 
     * @param string $column The column to find
     * @param string $table The name of the table
     * @param array $conditions The conditions in key value pair where key 'criteria' will be the column name
     * and value 'id' will be the value of the column
     * @return mixed Returns the value of the searched column from the table
     */
    public function selectColumnMultiCondition(string $column, string $table, array $conditions): mixed
    {
        $sql = "SELECT $column FROM $table WHERE ";
        foreach ($conditions as $column => $value) {
            $sql .= "$column = '$value' AND ";
        }
        $sql=rtrim($sql, " AND ");
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    /**
     * This method selects and returns all rows from the table which are in the result of a JOIN query
     * 
     * @param string $table The name of the table
     * @param array $joins A two dimensional array representing the joins as key value pairs 
     * where key 'table' will be the name of the table and value 'condition' will be the on condition
     * @return array Returns rows from the table
     */
    public function selectAllJoin(string $table, array $joins): array
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

    /**
     * This method selects and returns all rows from the table which are in the result of a JOIN query
     * 
     * and satisfies the condition
     * @param string $table The name of the table
     * @param array $joins A two dimensional array representing the joins as key value pairs 
     * where key 'table' will be the name of the table and value 'condition' will be the on condition
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return array Returns rows from the table
     */
    public function selectAllJoinSpecific(string $table, array $joins, mixed $searchId, string $searchCriteria = 'id'): array
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

    /**
     * This method selects and returns specified columns from the table which are in the result of a JOIN query
     * and satisfies the condition
     * 
     * @param string $table The name of the table
     * @param array $joins A two dimensional array representing the joins as key value pairs 
     * where key 'table' will be the name of the table and value 'condition' will be the on condition
     * @param string $columnStr The columns to select
     * @param mixed $searchId The value to search
     * @param string $searchCriteria The column in which the $searchId needs to be searched. Defaults to 'id'.
     * @return mixed Returns specified columns from the table
     */
    public function selectOneJoin(string $table, array $joins, string $columnStr, mixed $searchId, string $searchCriteria = 'id'): mixed
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

    /**
     * This method counts the number of rows in a table
     * 
     * @param string $table The name of the table
     * @return int|string Returns number of rows in the table
     */
    public function rowCount(string $table): int|string
    {
        $sql = "SELECT * FROM $table";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->num_rows;
    }

    /**
     * This method selects and returns the specified columns from the last entered row in a table
     * 
     * @param string $table The name of the table
     * @param string $column The columns to select. Defaults to 'uuid'.
     * @return mixed Returns the searched columns
     */
    public function lastEntry(string $table, string $column = 'uuid'): mixed
    {
        $sql = "SELECT $column FROM $table ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Some Error Occured: " . $this->conn->error);
        }
        return $result->fetch_column();
    }

    /**
     * This method selects and returns specified columns from the table using wildcard characters 
     * which searches the columns in the rows partially according to the conditions
     * 
     * @param string $table The name of the table
     * @param array $columns The columns to select
     * @param mixed $search The value to search
     * @param array $conditions The conditions in key value pair where key 'criteria' will be the column name
     * and value 'id' will be the value of the column
     * @return array Returns selected rows from the table
     */
    public function selectPartial(string $table, array $columns, string $search, array $conditions = []): array
    {
        $sql = "SELECT * FROM $table WHERE ($columns[0] LIKE '%$search%'";
        unset($columns[0]);
        foreach ($columns as $column) {
            $sql .= " OR $column LIKE '%$search%'";
        }
        $sql .= ")";
        foreach ($conditions as $column => $value) {
            $sql .= " AND $column = '$value'";
        }
        $sql=rtrim($sql, " AND ");
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Error searching: " . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}