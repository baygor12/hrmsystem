<?php
class DBConnection {
    private $host = 'localhost'; // Database host
    private $user = 'root';      // Your MySQL database user
    private $pass = 'root';   // Your MySQL database password
    private $dbname = 'hrm_system'; // Your database name

    public function connect() {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        return $conn;
    }
}

?>
