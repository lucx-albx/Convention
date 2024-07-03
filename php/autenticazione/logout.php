<?php
    include '../database.php';

    try {
        if(isset($_COOKIE['token'])) {
            $tk = $_COOKIE['token'];
            Database::collegati();

            $query_inserimento_token = "UPDATE Utenti SET Token = '' WHERE Token = ?;";
            $ck_query_inserimento_token = Database::$connessione -> prepare($query_inserimento_token);
            $ck_query_inserimento_token -> bind_param("s", $tk);
            $ck_query_inserimento_token -> execute();

            setcookie('loggato', 'false', time() - 86400, '/');
            setcookie('token', '', time() - 86410, '/');
        }

        header("Location: ../../html/pagine/index.html");
    } catch (Exception $e){
        header("Location: ../../html/pagine/index.html");
    }
?>