<?php
    include './database.php';
    include './sicurezza.php';
    include './gestione.php';
    include './grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['elimina_utente'])){
        $nome_cognome = $_POST['nome_cognome'];
        $email = $_POST['email'];
        $radio_check = $_POST['radio-group'];

        if(preg_replace('/\s+/', '', $nome_cognome) == "" || strlen($nome_cognome) > 40){
            Grafica::alert_errore("Nome e cognome non valido", "Il nome e cognome inserito non è valido, riprovare");
        } else if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            Grafica::alert_errore("Email non valida", "L'email inserita non è valida, riprovare");
        } else {
            if(Gestione::ruolo_utente() == "Admin"){
                Database::collegati();

                $email_trovata = false;
                $estremi_trovati = false;

                //Estraggo tutte le email e ragioni sociali dal db
                if($radio_check == "Partecipante"){
                    $res_controllo_dati = Database::esegui_query(
                        "
                        SELECT Utenti.Email, Utenti.IdUtente, Utenti.Nome, Utenti.Cognome
                        FROM Utenti
                        WHERE Utenti.Ruolo = 'Partecipante';
                        "
                    )->fetch_all(MYSQLI_ASSOC);
                } else if($radio_check == "Relatore"){
                    $res_controllo_dati = Database::esegui_query(
                        "
                        SELECT Utenti.Email, Utenti.IdUtente, Utenti.Nome, Utenti.Cognome
                        FROM Utenti
                        WHERE Utenti.Ruolo = 'Relatore';
                        "
                    )->fetch_all(MYSQLI_ASSOC);
                }
                
                //Controllo se email oppure la ragione sociale sono già presenti nel db
                foreach($res_controllo_dati as $rcd){
                    if($rcd['Email'] == $email){
                        $email_trovata = true;
                        $id_utente = $rcd['IdUtente']; 
                    }

                    $nmc = $rcd['Nome'] . " " . $rcd['Cognome'];

                    if($nome_cognome == $nmc){
                        $estremi_trovati = true;
                    }
                }
                
                //Controllo se uno dei campi è già nel db
                if($email_trovata == false){
                    Grafica::alert_errore("Email non trovata", "L'email inserita è inesistente");
                } else if($estremi_trovati == false){
                    Grafica::alert_errore("Utente non trovato", "I dati dell'utente non sono stati trovati, reinserirli");
                } else {
                    $query_id_partecipante = "SELECT IDPart FROM Utenti JOIN Partecipante ON Utenti.IdUtente = Partecipante.EK_IdUtente WHERE Utenti.IdUtente = ?;";
                    $ck_query_id_partecipante = Database::$connessione -> prepare($query_id_partecipante);
                    $ck_query_id_partecipante -> bind_param('i', $id_utente);
                    $ck_query_id_partecipante -> execute();
                    $id_part = $ck_query_id_partecipante -> get_result() -> fetch_assoc()['IDPart'];

                    if($radio_check == "Relatore"){
                        $query_seleziona_ragione_sociale = "SELECT Ek_RagioneSocialeAzienda, IDRel FROM Relatore WHERE EK_IdUtente = ?;";
                        $ck_query_seleziona_ragione_sociale = Database::$connessione -> prepare($query_seleziona_ragione_sociale);
                        $ck_query_seleziona_ragione_sociale -> bind_param('i', $id_utente);
                        $ck_query_seleziona_ragione_sociale -> execute();
                        $res = $ck_query_seleziona_ragione_sociale -> get_result() -> fetch_assoc();
                        $ragione_sociale = $res['Ek_RagioneSocialeAzienda'];
                        $id_rel = $res['IDRel'];

                        $query_seleziona_programmi = "SELECT Ek_IDProg FROM Relaziona WHERE EK_IDRel = ?;";
                        $ck_query_seleziona_programmi = Database::$connessione -> prepare($query_seleziona_programmi);
                        $ck_query_seleziona_programmi -> bind_param('i', $id_rel);
                        $ck_query_seleziona_programmi -> execute();
                        $programmi = $ck_query_seleziona_programmi -> get_result() -> fetch_all(MYSQLI_ASSOC);

                        foreach($programmi as $prog){
                            $query_rimuovi_sceglie = "DELETE FROM Sceglie WHERE EK_IDProg = ?;";
                            $ck_query_rimuovi_sceglie = Database::$connessione -> prepare($query_rimuovi_sceglie);
                            $ck_query_rimuovi_sceglie -> bind_param('i', $prog['Ek_IDProg']);
                            $ck_query_rimuovi_sceglie -> execute();

                            $query_rimuovi_speech = "DELETE FROM Relaziona WHERE Ek_IDProg = ?;";
                            $ck_query_rimuovi_speech = Database::$connessione -> prepare($query_rimuovi_speech);
                            $ck_query_rimuovi_speech -> bind_param('i', $prog['Ek_IDProg']);
                            $ck_query_rimuovi_speech -> execute();

                            $query_seleziona_programmi = "SELECT Ek_IDSpeech FROM Programma WHERE IDProg = ?;";
                            $ck_query_seleziona_programmi = Database::$connessione -> prepare($query_seleziona_programmi);
                            $ck_query_seleziona_programmi -> bind_param('i', $prog['Ek_IDProg']);
                            $ck_query_seleziona_programmi -> execute();
                            $res_prog = $ck_query_seleziona_programmi -> get_result() -> fetch_assoc();
                            $id_speech = $res_prog['Ek_IDSpeech'];

                            $query_rimuovi_programma = "DELETE FROM Programma WHERE IDProg = ?;";
                            $ck_query_rimuovi_programma = Database::$connessione -> prepare($query_rimuovi_programma);
                            $ck_query_rimuovi_programma -> bind_param('i', $prog['Ek_IDProg']);
                            $ck_query_rimuovi_programma -> execute();

                            $query_rimuovi_speech = "DELETE FROM Speech WHERE IDSpeech = ?;";
                            $ck_query_rimuovi_speech = Database::$connessione -> prepare($query_rimuovi_speech);
                            $ck_query_rimuovi_speech -> bind_param('i', $id_speech);
                            $ck_query_rimuovi_speech -> execute();
                        }

                        $query_rimuovi_relatore = "DELETE FROM Relatore WHERE IDRel = ?;";
                        $ck_query_rimuovi_relatore = Database::$connessione -> prepare($query_rimuovi_relatore);
                        $ck_query_rimuovi_relatore -> bind_param('i', $id_rel);
                        $ck_query_rimuovi_relatore -> execute();
                    }

                    if($radio_check == "Partecipante"){
                        $query_rimuovi_sceglie = "DELETE FROM Sceglie WHERE EK_IDPart = ?;";
                        $ck_query_rimuovi_sceglie = Database::$connessione -> prepare($query_rimuovi_sceglie);
                        $ck_query_rimuovi_sceglie -> bind_param('i', $id_part);
                        $ck_query_rimuovi_sceglie -> execute();
                    }

                    $query_rimuovi_partecipante = "DELETE FROM Partecipante WHERE EK_IdUtente = ?;";
                    $ck_query_rimuovi_partecipante = Database::$connessione -> prepare($query_rimuovi_partecipante);
                    $ck_query_rimuovi_partecipante -> bind_param('i', $id_utente);
                    $ck_query_rimuovi_partecipante -> execute();

                    $query_rimuovi_utente = "DELETE FROM Utenti WHERE IdUtente = ?;";
                    $ck_query_rimuovi_utente = Database::$connessione -> prepare($query_rimuovi_utente);
                    $ck_query_rimuovi_utente -> bind_param('i', $id_utente);
                    $ck_query_rimuovi_utente -> execute();

                    if($radio_check == "Relatore"){
                        $query_rimuovi_azienda = "DELETE FROM Azienda WHERE RagioneSocialeAzienda = ?;";
                        $ck_query_rimuovi_azienda = Database::$connessione -> prepare($query_rimuovi_azienda);
                        $ck_query_rimuovi_azienda -> bind_param('s', $ragione_sociale);
                        $ck_query_rimuovi_azienda -> execute();
                    }

                    Grafica::alert_successo("Utente eliminato", "L'utente è stato eliminato con successo!");
                }
            } else {
                Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
            }
        }
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>