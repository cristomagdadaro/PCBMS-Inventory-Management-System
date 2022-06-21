<?php
class DB
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "pcbms_db";
    protected $conn = null;
    protected $sql = "";

    function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        if ($this->conn->connect_errno != 0)
            die("Connection failed {$this->conn->connect_errno}: " . $this->conn->connect_error);
    }

    // Generic Select query
    protected function Select_Query()
    {
        $result =  $this->conn->query($this->sql);
        if ($result)
            return $result->fetch_all(MYSQLI_ASSOC);
        return $this->return_error();
    }

    // Generic Insert query
    protected function Insert_Query()
    {
        $result =  $this->conn->query($this->sql);
        if ($result)
            return array("inserted_id"=>$this->conn->insert_id);
        return $this->return_error();
    }

    // Generic Update query
    protected function Update_Query()
    {
        $result =  $this->conn->query($this->sql);
        if ($result)
            return array("affected_rows"=>$this->conn->affected_rows);
        return $this->return_error();
    }

    // Generic Delete query
    protected function Delete_Query()
    {
        $result =  $this->conn->query($this->sql);
        if ($result)
            return array("affected_rows"=>$this->conn->affected_rows);
        return $this->return_error();
    }

    // Retrieve the errors after failed query execution.
    private function return_error()
    {
        if ($this->conn->connect_errno)
            return array("connect_errno" => $this->conn->connect_errno, "connect_error" => $this->conn->connect_error,"inserted_id"=>$this->conn->insert_id, "affected_rows"=>$this->conn->affected_rows);
        return array("errno" => $this->conn->errno, "error" => $this->conn->error, "inserted_id"=>$this->conn->insert_id, "affected_rows"=>$this->conn->affected_rows);
    }
}
