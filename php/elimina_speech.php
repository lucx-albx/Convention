<?php
    include './database.php';
    include './gestione.php';
    include './grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        Database::collegati();

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data);
        $id_speech_str = $data->ids;
        $id_speech = intval($id_speech_str);
        $messaggio = "Speech eliminato con successo!";

        if(Gestione::ruolo_utente() == "Admin"){
            $ck_idp = Database::esegui_query(
                "
                SELECT IDProg
                FROM Programma 
                WHERE Ek_IDSpeech = $id_speech;
                "
            );

            if($ck_idp -> num_rows > 0){
                $idp = $ck_idp -> fetch_assoc()['IDProg'];
    
                $ck_nms = Database::esegui_query(
                    "
                    SELECT Ek_NomeSala
                    FROM Programma 
                    WHERE Ek_IDSpeech = $id_speech;
                    "
                );

                if($ck_nms -> num_rows > 0){
                    $nms = $ck_nms -> fetch_assoc()['Ek_NomeSala'];

                    $ck_num = Database::esegui_query(
                        "
                        SELECT Ek_Numero
                        FROM Sala 
                        WHERE NomeSala = '$nms';
                        "
                    );

                    if($ck_num -> num_rows > 0){
                        $num = $ck_num -> fetch_assoc()['Ek_Numero'];

                        Database::esegui_query(
                            "
                            DELETE FROM Sceglie
                            WHERE Ek_IDProg = $idp;
                            "
                        );

                        Database::esegui_query(
                            "
                            DELETE FROM Relaziona
                            WHERE Ek_IDProg = $idp;
                            "
                        );

                        Database::esegui_query(
                            "
                            DELETE FROM Programma
                            WHERE Ek_IDSpeech = $id_speech;
                            "
                        );

                        Database::esegui_query(
                            "
                            DELETE FROM Speech
                            WHERE IDSpeech = $id_speech;
                            "
                        );

                        $posti = 20;

                        Database::esegui_query(
                            "
                            UPDATE Sala
                            SET NpostiSala = $posti
                            WHERE NomeSala = '$nms';
                            "
                        );
                    } else {
                        $messaggio = "Errore nell'eliminazione dello speech";
                    }
                } else {
                    $messaggio = "Errore nell'eliminazione dello speech";
                }
            } else {
                $messaggio = "Errore nell'eliminazione dello speech";
            }
        } else{
            $messaggio = "Non hai i permesso per eliminare lo speech!";
        }

        echo json_encode(array('message' => $messaggio));
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>