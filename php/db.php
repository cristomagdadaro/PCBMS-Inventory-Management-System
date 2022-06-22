<?php
class DB
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "pcbms_db";
    static $conn_static = null;
    protected $conn = null;
    protected $sql = "";

    function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
            DB::$conn_static = $this->conn;
        } catch (mysqli_sql_exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
            exit;
        }
    }

    // Generic Select query
    protected function Select_Query()
    {
        try {
            $result =  $this->conn->query($this->sql);
            if ($result)
                return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            return array("error" => $e->getMessage());
        }
    }

    // Generic Insert query
    protected function Insert_Query()
    {
        try {
            $result =  $this->conn->query($this->sql);
            if ($result)
                return array("inserted_id" => $this->conn->insert_id);
        } catch (mysqli_sql_exception $e) {
            return array("error" => $e->getMessage());
        }
    }

    // Generic Update query
    protected function Update_Query()
    {
        try {
            $result =  $this->conn->query($this->sql);
            if ($result)
                return array("affected_rows" => $this->conn->affected_rows);
        } catch (mysqli_sql_exception $e) {
            return array("error" => $e->getMessage());
        }
    }

    // Generic Delete query
    protected function Delete_Query()
    {
        try {
            $result =  $this->conn->query($this->sql);
            if ($result)
                return array("affected_rows" => $this->conn->affected_rows);
        } catch (mysqli_sql_exception $e) {
            return array("error" => $e->getMessage());
        }
    }

    // Retrieve the errors after failed query execution.
    private function return_error()
    {
        if ($this->conn->connect_errno)
            return array("connect_errno" => $this->conn->connect_errno, "connect_error" => $this->conn->connect_error, "inserted_id" => $this->conn->insert_id, "affected_rows" => $this->conn->affected_rows);
        return array("errno" => $this->conn->errno, "error" => $this->conn->error, "inserted_id" => $this->conn->insert_id, "affected_rows" => $this->conn->affected_rows);
    }
}
