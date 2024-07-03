<link rel='stylesheet' href='../../css/errori.css?v=3.1'>

<?php
    include '../database.php';
    include '../sicurezza.php';
    include '../grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            Grafica::alert_errore("Email non valida", "L'email inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $password) == "") {
            Grafica::alert_errore("Password non valida", "La password inserita non è valida, riprovare");
        } else {
            Database::collegati();

            $password_cifrata = Sicurezza::crittografa_password($password);

            $query_utente = "SELECT * FROM Utenti WHERE Email = ? AND Password = ?;";

            $prq = Database::$connessione -> prepare($query_utente);
            $prq -> bind_param("ss", $email, $password_cifrata);
            $prq -> execute();
            $dati_utente = $prq -> get_result();

            if($dati_utente->num_rows > 0){
                $tk = Sicurezza::genera_token($email);

                setcookie('loggato', 'true', time() + 86400, '/');
                setcookie('token', $tk, time() + 86410, '/');

                $query_inserimento_token = "UPDATE Utenti SET Token = ? WHERE Email = ? AND Password = ?;";
                $ck_query_inserimento_token = Database::$connessione -> prepare($query_inserimento_token);
                $ck_query_inserimento_token -> bind_param("sss", $tk, $email, $password_cifrata);
                $ck_query_inserimento_token -> execute();

                header("Location: ../pagine/home.php");
            } else {
                Grafica::alert_errore("Credenziali errate", "Le credenziali inserite sono errate, ripovare");
            }
        }

    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>