<?php

    class Database extends Settings
    {
        private $_connection;
        private static $_instance; //The single instance
        /*
        Get an instance of the Database
        @return Instance
        */
        public static function getInstance()
        {
            if(!self::$_instance) // If no instance then make one
                self::$_instance = new self();
            return self::$_instance;
        }
        // Constructor
        private function __construct()
        {
            parent::getSettings();
            $this->_connection = new mysqli(parent::$host, parent::$dbuser, parent::$dbpass, parent::$dbname, parent::$dbport);
            mysqli_query($this->_connection, "SET NAMES 'utf8'");
            // Error handling
            if(mysqli_connect_error())
                trigger_error("Failed to connect to to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
        }
        // Magic method clone is empty to prevent duplication of connection
        private function __clone() { }
        // Get mysqli connection
        public function getConnection()
        {
            return $this->_connection;
        }
        //Shorthand to get connection
        public static function conn()
        {
            return self::getInstance()->getConnection();
        }
        public function __destruct()
        {
            $this->_connection->close();
        }
    }

    class Query extends Database
    {
        //Shorthand to make a mysqli_query
        public static function run($sql)
        {
            return parent::conn()->query($sql);
        }
        public static function first($sql, $assoc = true)
        {
            if($assoc)
                return mysqli_fetch_assoc(self::run($sql));
            else
                return mysqli_fetch_row(self::run($sql));
        }
        public static function firstResult($sql)
        {
            return self::first($sql, false)[0];
        }
        public static function count($name, $table, $next = '') //Creo q el $name sobra jeje con un id va bien, en teoria todas las tablas tienen id, lo dejare como parametro opcipnal despues del next
        {
            if(strlen($next)) $next = " ".$next;
            return mysqli_num_rows(self::run("SELECT $name FROM $table$next"));
            //return mysqli_fetch_assoc(self::run("SELECT COUNT($name) AS total FROM $table$next"))['total'];
        }
    }