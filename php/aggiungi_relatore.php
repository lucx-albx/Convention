<?php
    include './database.php';
    include './sicurezza.php';
    include './gestione.php';
    include './grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['registra_relatore'])){
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $ragione_sociale = $_POST['ragione_sociale'];
        $via_azienda = $_POST['via_azienda'];
        $numero_telefono = $_POST['numero_telefono'];
        $sei_partecipante = false;
        $ruolo = "Relatore";
        $token = "";

        if(preg_replace('/\s+/', '', $nome) == "" || strlen($nome) > 40){
            Grafica::alert_errore("Nome non valido", "Il nome inserito non è valido, riprovare");
        } else if(preg_replace('/\s+/', '', $cognome) == "" || strlen($cognome) > 40){
            Grafica::alert_errore("Cognome non valido", "Il cogome inserito non è valido, riprovare");
        } else if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            Grafica::alert_errore("Email non valida", "L'email inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $password) == "") {
            Grafica::alert_errore("Password non valida", "La password inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $ragione_sociale) == "") {
            Grafica::alert_errore("Ragione sociale non valida", "La ragione sociale inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $via_azienda) == "") {
            Grafica::alert_errore("Via non valida", "La via inserita non è valida, riprovare");
        } else if(preg_replace('/\s+/', '', $numero_telefono) == "") {
            Grafica::alert_errore("Numero non valido", "Il numero inserito non è valido, riprovare");
        } else {
            if(Gestione::ruolo_utente() == "Admin"){
                Database::collegati();

                $email_utilizzata = false;
                $ragione_sociale_utilizzato = false;
                $password_cifrata = Sicurezza::crittografa_password($password);

                //Estraggo tutte le email e ragioni sociali dal db
                $res_controllo_dati = Database::esegui_query(
                    "
                    SELECT Utenti.Email, Relatore.Ek_RagioneSocialeAzienda
                    FROM Utenti 
                    JOIN Relatore ON Utenti.IdUtente = Relatore.Ek_IdUtente;
                    "
                )->fetch_all(MYSQLI_ASSOC);
                
                //Controllo se email oppure la ragione sociale sono già presenti nel db
                foreach($res_controllo_dati as $rcd){
                    if($rcd['Email'] == $email){
                        $email_utilizzata = true;
                    }

                    if($rcd['Ek_RagioneSocialeAzienda'] == $ragione_sociale){
                        $ragione_sociale_utilizzato = true;
                    }
                }
                
                //Controllo se uno dei campi è già nel db
                if($email_utilizzata == true){
                    Grafica::alert_errore("Email in utilizzo", "La email inserita è già in utilizzo oppure non è valida");
                } else if($ragione_sociale_utilizzato == true){
                    Grafica::alert_errore("Ragione sociale in utilizzo", "La ragione sociale inserita è già in utilizzo oppure non è valida");
                } else {
                    //Query per controllare se è già un partecipante
                    $query_partecipante_presente = "
                    SELECT *
                    FROM Utenti
                    WHERE Email = ?; 
                    ";
                    $ck_query_partecipante_presente = Database::$connessione -> prepare($query_partecipante_presente);
                    $ck_query_partecipante_presente -> bind_param('s', $email);
                    $ck_query_partecipante_presente -> execute();
                    $partecipante_presente = $ck_query_partecipante_presente -> get_result();

                    if($partecipante_presente -> num_rows == 0){
                        //Query inserimento nella tabella utenti
                        $query_inserimento_relatore = "
                        INSERT INTO Utenti (Email, Nome, Cognome, Password, Token, Ruolo) 
                        Values (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                        );";

                        $qir = Database::$connessione -> prepare($query_inserimento_relatore);
                        $qir -> bind_param('ssssss', $email, $nome, $cognome, $password_cifrata, $token, $ruolo);
                        $qir -> execute();
                    } else {
                        $sei_partecipante = true;

                        $query_agg_ruolo = "
                        UPDATE Utenti 
                        SET Ruolo = ?
                        WHERE Email = ?;
                        ";
                        $ck_query_agg_ruolo = Database::$connessione -> prepare($query_agg_ruolo);
                        $ck_query_agg_ruolo -> bind_param('ss', $ruolo, $email);
                        $ck_query_agg_ruolo -> execute();
                    }

                    //Query per inserire nella tabella Azienda i nuovi dati
                    $query_inserimento_azienda = "
                    INSERT INTO Azienda (RagioneSocialeAzienda, IndirizzoAzienda, TelefonoAzienda)
                    VALUES (
                        ?,
                        ?,
                        ?
                    );";

                    $qia = Database::$connessione -> prepare($query_inserimento_azienda);
                    $qia -> bind_param('sss', $ragione_sociale, $via_azienda, $numero_telefono);
                    $qia -> execute();

                    //Query per estrarre id dell'utente inserito
                    $query_id_ut = "
                    SELECT IdUtente
                    FROM Utenti
                    WHERE Email = ?
                    AND Password = ?; 
                    ";
                    $ck_query_id_ut = Database::$connessione -> prepare($query_id_ut);
                    $ck_query_id_ut -> bind_param('ss', $email, $password_cifrata);
                    $ck_query_id_ut -> execute();
                    $id_ut = $ck_query_id_ut -> get_result() -> fetch_assoc()['IdUtente'];

                    //Query per inserire nella tabella Relatore i nuovi dati
                    $query_inserimento_relatore_sql_injection = "
                    INSERT INTO Relatore (EK_IdUtente, Ek_RagioneSocialeAzienda)
                    VALUES (
                        ?,
                        ?
                    );";

                    $qir_sqlin = Database::$connessione -> prepare($query_inserimento_relatore_sql_injection);
                    $qir_sqlin -> bind_param('is', $id_ut, $ragione_sociale);
                    $qir_sqlin -> execute();

                    if($sei_partecipante == false){
                        $query_inserimento_partecipante_sql_injection = "
                        INSERT INTO Partecipante (EK_IdUtente, TipologiaPart)
                        VALUES (
                            ?,
                            ?
                        );";

                        $qip_sqlin = Database::$connessione -> prepare($query_inserimento_partecipante_sql_injection);
                        $qip_sqlin -> bind_param('is', $id_ut, $ruolo);
                        $qip_sqlin -> execute();
                    } else {
                        $query_agg_tipologia = "
                        UPDATE Partecipante 
                        SET TipologiaPart = ?
                        WHERE EK_IdUtente = ?;
                        ";
                        $ck_query_agg_tipologia = Database::$connessione -> prepare($query_agg_tipologia);
                        $ck_query_agg_tipologia -> bind_param('si', $ruolo, $id_ut);
                        $ck_query_agg_tipologia -> execute();
                    }

                    Grafica::alert_successo('Relatore registato', 'Il relatore è stato registrato con successo!');
                }
            } else {
                Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
            }
        }
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>