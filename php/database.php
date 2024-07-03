<?php
    class Database {
        private static $host = "localhost";
        private static $username = "root";
        private static $password = "";
        private static $database = "Convention";
        public static $connessione;

        public static function collegati(){
            self::$connessione = new mysqli(
                self::$host, 
                self::$username, 
                self::$password, 
                self::$database
            );

            if (self::$connessione->connect_error) {
                die("Connessione fallita: " . self::$connessione->connect_error);
            }
        }

        public static function esegui_query($query){
            $res = self::$connessione->query($query);

            if (!$res) {
                echo "Errore nella query: " . self::$connessione->error;
            }

            return $res;
        }
    }
?>