<?php
    include './database.php';
    include './gestione.php';
    include './grafica.php';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        Database::collegati();

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data);
        $id_prog_str = $data->idp;
        $id_prog = intval($id_prog_str);
        $nome_sala = $data->ns;
        $token = $_COOKIE['token']; 
        $messaggio = "Disiscritto allo speech con successo!";

        //Riduciamo i posti dello speech
        Database::esegui_query(
            "
            UPDATE Sala 
            SET NpostiSala = (
                CASE 
                    WHEN NpostiSala >= 0 THEN NpostiSala + 1 
                    ELSE 0 
                END
            )
            WHERE NomeSala = '$nome_sala';
            "
        );
        
        //Estraggo id dell'utente loggato
        $so_id_part = Database::esegui_query(
            "
            SELECT Partecipante.IDPart
            FROM Utenti 
            JOIN Partecipante ON Utenti.IdUtente = Partecipante.EK_IdUtente
            WHERE Utenti.Token = '$token';
            "
        );

        if($so_id_part->num_rows > 0){
            $id_part = intval($so_id_part -> fetch_assoc()['IDPart']);
            //Aggiungiamo lo specch dell'utente tra i seguiti
            Database::esegui_query("DELETE FROM Sceglie WHERE Ek_IDProg = $id_prog AND Ek_IDPart = $id_part;");
        } else {
            $messaggio = "Errore, riprovare";
        }

        echo json_encode(array('message' => $messaggio));
    } else {
        Grafica::alert_errore("Permesso negato", "Non hai il permesso per visualizzare questa pagina");
    }
?>