<?php
    include '../database.php';
    include '../gestione.php';
    include '../risultato_formattato.php';
    include '../grafica.php';

    if(!isset($_COOKIE['loggato'])){
        header("Location: ../autenticazione/logout.php");
    } else {
        $dati_speech = Gestione::carica_speech_seguiti();
        $ruolo = Gestione::ruolo_utente();

        if($ruolo == "Partecipante"){
            header("Location: ./partecipante.php");
        } else if($ruolo == "Relatore"){
            header("Location: ./relatore.php");
        } else if($ruolo == "Admin"){
            header("Location: ./admin.php");
        } else {
            header("Location: ../autenticazione/logout.php");
        }
    }
?>