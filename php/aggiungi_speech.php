<?php
    include './database.php';
    include './gestione.php';
    include './grafica.php';
    include './risultato_formattato.php';

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aggiungi_speech'])){
        $email = $_POST['email'];
        $titolo_speech = $_POST['titolo_speech'];
        $testo_speech = $_POST['testo_speech'];
        $data_speech = $_POST['data_speech'];
        $ora_speech = $_POST['ora_speech'];
        $nome_sala = $_POST['nome_sala'];
        $piano_presente = false;
        $sala_presente = false;
        $titolo_presente = false;

        //Formattiamo la data come sul db
        $fascia_oraria = $data_speech . " " . $ora_speech . ":00";

        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            Grafica::alert_errore("Email non valida", "L'email inserita non è valida, riprovare");
        } else if(strlen($titolo_speech) > 50){
            Grafica::alert_errore("Titolo non valido", "Il titolo inserito non è valido, riprovare");
        } else if(strlen($testo_speech) > 100){
            Grafica::alert_errore("Testo non valido", "Il testo inserito non è valido, riprovare");
        } else if(strlen($nome_sala) > 50 || $nome_sala == "Nessuna sala disponibile") {
            Grafica::alert_errore("Nome sala non valido", "Il nome della sala inserito non è valido oppure non è disponibile");
        } else {
            if(Gestione::ruolo_utente() == "Admin"){
                Database::collegati();

                //Controlliamo se il nome della sala è già nel db
                $query_res_sala_presente = "SELECT Sala.NomeSala FROM Sala JOIN Programma ON Sala.NomeSala = Programma.Ek_NomeSala WHERE Sala.NomeSala = ?;";
                $ck_query_res_sala_presente = Database::$connessione -> prepare($query_res_sala_presente);
                $ck_query_res_sala_presente -> bind_param('s', $nome_sala);
                $ck_query_res_sala_presente -> execute();
                $res_sala_presente = $ck_query_res_sala_presente -> get_result();

                if($res_sala_presente -> num_rows > 0){
                    $sala_presente = true;
                }

                if($sala_presente == false){        
                    $query_res_titolo_presente = "SELECT Titolo FROM Speech WHERE Titolo = ?";
                    $ck_query_res_titolo_presente = Database::$connessione -> prepare($query_res_titolo_presente);
                    $ck_query_res_titolo_presente -> bind_param('s', $titolo_speech);
                    $ck_query_res_titolo_presente -> execute();
                    $res_titolo_presente = $ck_query_res_titolo_presente -> get_result();

                    if($res_titolo_presente -> num_rows > 0){
                        $titolo_presente = true;
                    }

                    if($titolo_presente == false){
                        $query_id_relatore = "SELECT IDRel FROM Relatore JOIN Utenti ON Utenti.IdUtente = Relatore.Ek_IdUtente WHERE Email = ?;";
                        $ck_query_id_relatore = Database::$connessione -> prepare($query_id_relatore);
                        $ck_query_id_relatore -> bind_param('s', $email);
                        $ck_query_id_relatore -> execute();
                        $ck_id_relatore = $ck_query_id_relatore -> get_result();

                        if($ck_id_relatore -> num_rows > 0){
                            $id_relatore = $ck_id_relatore -> fetch_assoc()['IDRel'];

                            $query_inserisci_speech = "INSERT INTO Speech (Titolo, Argomento) VALUES (?, ?);";
                            $ck_query_inserisci_speech = Database::$connessione -> prepare($query_inserisci_speech);
                            $ck_query_inserisci_speech -> bind_param('ss', $titolo_speech, $testo_speech);
                            $ck_query_inserisci_speech -> execute();

                            $query_id_speech = "SELECT IDSpeech FROM Speech WHERE Titolo = ? AND Argomento = ?;";
                            $ck_query_id_speech = Database::$connessione -> prepare($query_id_speech);
                            $ck_query_id_speech -> bind_param('ss', $titolo_speech, $testo_speech);
                            $ck_query_id_speech -> execute();
                            $id_speech = $ck_query_id_speech -> get_result() -> fetch_assoc()['IDSpeech'];

                            $query_inserisci_programma = "INSERT INTO Programma (Ek_IDSpeech, Ek_NomeSala, FasciaOraria) VALUES (?, ?, ?);";
                            $ck_query_inserisci_programma = Database::$connessione -> prepare($query_inserisci_programma);
                            $ck_query_inserisci_programma -> bind_param('iss', $id_speech, $nome_sala, $fascia_oraria);
                            $ck_query_inserisci_programma -> execute();

                            $query_id_programma = "SELECT IDProg FROM Programma WHERE Ek_NomeSala = ? AND FasciaOraria = ?;";
                            $ck_query_id_programma = Database::$connessione -> prepare($query_id_programma);
                            $ck_query_id_programma -> bind_param('ss', $nome_sala, $fascia_oraria);
                            $ck_query_id_programma -> execute();
                            $id_programma = $ck_query_id_programma -> get_result() -> fetch_assoc()['IDProg'];

                            $query_inserisci_relaziona = "INSERT INTO Relaziona VALUES (?, ?);";
                            $ck_query_inserisci_relaziona = Database::$connessione -> prepare($query_inserisci_relaziona);
                            $ck_query_inserisci_relaziona -> bind_param('ii', $id_programma, $id_relatore);
                            $ck_query_inserisci_relaziona -> execute();

                            Grafica::alert_successo('Speech aggiunto', 'Lo speech è stato aggiunto con successo!');
                        } else {
                            Grafica::alert_errore("Email inesistente", "La email inserita è inesistente oppure non è un relatore, riprovare");
                        }
                    } else {
                        Grafica::alert_errore("Titolo non valido", "Il titolo inserito è già esistente, inserirne un altro");
                    }
                } else {
                    Grafica::alert_errore("Sala non valida", "Il nome della sala inserito è già registrata, riprovare");
                }
            }                          
        }
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>