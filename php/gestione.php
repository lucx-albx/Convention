<?php
    class Gestione {
        public static function ruolo_utente() {
            $token = $_COOKIE['token']; 
        
            Database::collegati();

            $ck_ruolo = Database::esegui_query(
                "
                SELECT Ruolo
                FROM Utenti
                WHERE Token = '$token';
                "
            );

            if($ck_ruolo->num_rows > 0){
                return $ck_ruolo->fetch_assoc()['Ruolo'];
            } else {
                return false;
            }
        }

        public static function estrai_nome_cognome_utente(){
            $token = $_COOKIE['token']; 

            $nome_cognome = Database::esegui_query(
                "
                SELECT Nome, Cognome
                FROM Utenti
                WHERE Token = '$token';
                "
            )->fetch_assoc();

            return $nome_cognome;
        }

        public static function estrai_dati_azienda(){
            $token = $_COOKIE['token']; 
            $dati_azienda = "";

            if(self::ruolo_utente() == "Relatore"){
                $dati_azienda = Database::esegui_query(
                    "
                    SELECT IndirizzoAzienda, TelefonoAzienda
                    FROM Utenti
                    JOIN Relatore ON Utenti.IdUtente = Relatore.Ek_IdUtente
                    JOIN Azienda ON Relatore.Ek_RagioneSocialeAzienda = Azienda.RagioneSocialeAzienda
                    WHERE Relatore.Ek_IdUtente = (
                        SELECT Utenti.IdUtente
                        FROM Utenti
                        WHERE Token = '$token'
                    );
                    "
                )->fetch_assoc();
            }

            return $dati_azienda;
        }

        public static function carica_tutti_speech() {
            Database::collegati();

            $ruolo = self::ruolo_utente();

            if($ruolo != false){
                $ck_info_programma = Database::esegui_query(
                    "
                    SELECT IDSpeech, IDProg, Titolo, Argomento, FasciaOraria, NomeSala, NpostiSala, Piano.Numero
                    FROM Programma
                    JOIN Speech ON Programma.Ek_IDSpeech = Speech.IDSpeech
                    JOIN Sala ON Programma.Ek_NomeSala = Sala.NomeSala
                    JOIN Piano ON Sala.Ek_Numero = Piano.Numero
                    ORDER BY Titolo ASC;
                    "
                );

                if($ck_info_programma->num_rows > 0){
                    return $ck_info_programma->fetch_all(MYSQLI_ASSOC);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public static function carica_speech_seguiti() {
            $token = $_COOKIE['token'];
        
            Database::collegati();

            $ruolo = self::ruolo_utente();

            if($ruolo != false){
                $ck_id_part = Database::esegui_query(
                    "
                    SELECT Partecipante.IDPart
                    FROM Utenti 
                    JOIN Partecipante ON Utenti.IdUtente = Partecipante.EK_IdUtente
                    WHERE Utenti.Token = '$token';
                    "
                );
                
                //Controllo se la query ha restituito dei dati
                if($ck_id_part->num_rows > 0){
                    $id_part = $ck_id_part->fetch_assoc()['IDPart'];
                    
                    $ck_info_programma = Database::esegui_query(
                        "
                        SELECT IDSpeech, IDProg, Titolo, Argomento, FasciaOraria, NomeSala, NpostiSala, Piano.Numero
                        FROM Sceglie 
                        JOIN Programma ON Sceglie.Ek_IDProg = Programma.IDProg
                        JOIN Speech ON Programma.Ek_IDSpeech = Speech.IDSpeech
                        JOIN Sala ON Programma.Ek_NomeSala = Sala.NomeSala
                        JOIN Piano ON Sala.Ek_Numero = Piano.Numero
                        WHERE Sceglie.EK_IDPart = '$id_part'
                        ORDER BY Titolo ASC;
                        "
                    );

                    //Controllo se la query ha restituito dei dati
                    if($ck_info_programma->num_rows > 0){
                        return $ck_info_programma->fetch_all(MYSQLI_ASSOC);
                    } else {
                        return false;
                    }

                } else {
                    return false;
                }
            }
        }

        public static function speech_relazionati(){
            $token = $_COOKIE['token'];

            $speech_relazionati = Database::esegui_query(
                "
                SELECT IDSpeech, IDProg, Titolo, Argomento, FasciaOraria, NomeSala, NpostiSala, Piano.Numero
                FROM Relaziona 
                JOIN Programma ON Relaziona.Ek_IDProg = Programma.IDProg
                JOIN Speech ON Programma.Ek_IDSpeech = Speech.IDSpeech
                JOIN Sala ON Programma.Ek_NomeSala = Sala.NomeSala
                JOIN Piano ON Sala.Ek_Numero = Piano.Numero
                WHERE Relaziona.EK_IDRel = (
                    SELECT Relatore.IDRel
                    FROM Utenti 
                    JOIN Relatore ON Utenti.IdUtente = Relatore.EK_IdUtente
                    WHERE Utenti.Token = '$token'
                )
                ORDER BY Titolo ASC;
                "
            )->fetch_all(MYSQLI_ASSOC);

            return $speech_relazionati;
        }
    }
?>