<link rel='stylesheet' href='../../css/errori.css?v=3.1'>

<?php
    include '../database.php';
    include '../sicurezza.php';
    include '../grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if(preg_replace('/\s+/', '', $nome) == "" || strlen($nome) > 40){
            Grafica::alert_errore("Nome non valido", "Il nome inserito non è valido, riprovare");
        } else if(preg_replace('/\s+/', '', $cognome) == "" || strlen($cognome) > 40){
            Grafica::alert_errore("Cognome non valido", "Il cogome inserito non è valido, riprovare");
        } else if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            Grafica::alert_errore("Email non valida", "L'email inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $password) == "" || $password !== $password2) {
            Grafica::alert_errore("Password non valida", "La password inserita non è valida, riprovare");
        } else {
            Database::collegati();

            $password_cifrata = Sicurezza::crittografa_password($password);
            $tk = Sicurezza::genera_token($password);

            $query_res = "SELECT * FROM Utenti WHERE Email = ?;";
            $pqr = Database::$connessione -> prepare($query_res);
            $pqr -> bind_param('s', $email);
            $pqr -> execute();
            $res = $pqr -> get_result();

            if($res->num_rows == 0){
                $ruolo = 'Partecipante';
                $query_iu = "INSERT INTO Utenti (Nome, Cognome, Email, Password, Token, Ruolo) VALUES (?, ?, ?, ?, ?, ?);";
                $pqiu = Database::$connessione -> prepare($query_iu);
                $pqiu -> bind_param('ssssss', $nome, $cognome, $email, $password_cifrata, $tk, $ruolo);
                $pqiu -> execute();

                $res_ut = Database::esegui_query(
                    "
                    SELECT * 
                    FROM Utenti
                    WHERE Email = '$email'
                    AND Password = '$password_cifrata'; 
                    "
                ) -> fetch_assoc();

                $ek_ut = $res_ut['IdUtente'];
                $tipologia = "Partecipante";

                $query_inserimento_partecipante = "
                INSERT INTO Partecipante (EK_IdUtente, TipologiaPart)
                VALUES (
                    ?,
                    ?
                );
                ";
                $ck_query_inserimento_partecipante = Database::$connessione -> prepare($query_inserimento_partecipante);
                $ck_query_inserimento_partecipante -> bind_param('is', $ek_ut, $tipologia);
                $ck_query_inserimento_partecipante -> execute();

                setcookie('loggato', 'true', time() + 86400, '/');
                setcookie('token', $tk, time() + 86410, '/');

                header("Location: ../pagine/home.php");
            } else {
                Grafica::alert_errore("Email in utilizzo", "La email inserita è già in utilizzo oppure non è valida");
            }            
        }
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>