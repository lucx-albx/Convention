<?php
    include '../database.php';
	include '../risultato_formattato.php';
    include '../gestione.php';
	include '../grafica.php';

    if(!isset($_COOKIE['loggato'])){
        header("Location: ../autenticazione/logout.php");
    } else {
        $ruolo = Gestione::ruolo_utente();

        if($ruolo != false  && $ruolo == "Relatore"){
            $dati_speech = Gestione::speech_relazionati();
        } else {
            header("Location: ../autenticazione/logout.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css?v=1.1' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
    <link rel="stylesheet" href="../../css/home.css?v=3.5">

    <title>Speech relazionati</title>
</head>
<body>
    <?php
        Grafica::navbar_relatore();
    ?>

    <div class="spazia container-speech row">
        <?php
            if($dati_speech != false){
                Grafica::ui_speech_relazionati($dati_speech);
            } else {
                Grafica::alert_successo('Non relazioni speech', 'Al momento non relazioni alcuno speech, fai richiesta!');
            }
        ?>
    </div>

    <?php
        Grafica::footer();
    ?>

    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js?v=1.0' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>  
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js?v=1.0' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js?v=1.0' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
</body>
</html>