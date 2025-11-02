<?php

    // $conn = mysqli_connect("localhost", "root", "", "r&r_system");

    class Database {
        private $host = "localhost";
        private $user = "root";
        private $pass = "";
        private $db_name = "r&r_system";
        protected $conn;

        public function connection () {

            $this->conn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->db_name
            );
            

            if($this->conn->connect_error) {
                die("Connection error: ". $this->conn->connect_error);
            } else {
                return "Success";
            }
        }

    }

?>