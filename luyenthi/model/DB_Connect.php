<?php
    class DB_Connect {
        private $connection;
        private static $instance; //The single instance

        public static function getInstance() {
            if(!self::$instance) { // If no instance then make one
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct() {
            require_once 'config.php';
            $this->_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

            // Error handling
            if(mysqli_connect_error()) {
                trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(),
                    E_USER_ERROR);
            }
        }

        // Magic method clone is empty to prevent duplication of connection
        private function __clone() { }

        // Get mysqli connection
        public function getConnection() {
            return $this->_connection;
        }
    }
?>