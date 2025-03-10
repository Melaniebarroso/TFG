<?php
class MySQLWrapper {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql) {
        $result = $this->connection->query($sql);

        if (!$result) {
            die("Query failed: " . $this->connection->error);
        }

        return $result;
    }

    public function prepareStatement($sql) {
        return $this->connection->prepare($sql);
    }

    public function executeStatement($statement) {
        if (!$statement->execute()) {
            die("Statement execution failed: " . $statement->error);
        }

        return $statement->get_result();
    }

    public function bindParams($statement, $types, ...$params) {
        $statement->bind_param($types, ...$params);
    }

    public function fetchAssoc($result) {
        return $result->fetch_assoc();
    }

    public function fetchAll($result) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function escapeString($value) {
        return $this->connection->real_escape_string($value);
    }

    public function close() {
        $this->connection->close();
    }
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
}


$mysqlWrapper = new MySQLWrapper('sql210.infinityfree.com', 'if0_38459062', 'Melanibarroso13', 'if0_38459062_administracion');