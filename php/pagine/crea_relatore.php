<?php
    include '../database.php';
    include '../gestione.php';
    include '../risultato_formattato.php';
    include '../grafica.php';

    if(!isset($_COOKIE['loggato'])){
        header("Location: ../autenticazione/logout.php");
    } else {
        $ruolo = Gestione::ruolo_utente();

        if($ruolo != false){
            if($ruolo != "Admin"){
                header("Location: ./home.php");
            }
        } else {
            header("Location: ../autenticazione/logout.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css?v=1.1' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
    <link rel="stylesheet" href="../../css/home.css?v=3.3">

    <title>Aggiungi relatore - Admin</title>
</head>
<body>
    <?php
        Grafica::navbar_admin();
    ?>

    <div class="content">
        <h2 class="center-text">Registra relatore</h2>

        <form action="../aggiungi_relatore.php" method="POST" class="mt-4">
            <label for="first-name">Nome Relatore</label>
            <input type="text" id="first-name" class="input-field" name="nome" value="Mariella" required>

            <label for="last-name">Cognome Relatore</label>
            <input type="text" id="last-name" class="input-field" name="cognome" value="Blu" required>

            <label for="email">Email Relatore</label>
            <input type="email" id="email" class="input-field" name="email" value="Mariella.Blu@gmail.com" required>

            <label for="password">Password Relatore</label>
            <input type="password" id="password" class="input-field" name="password" value="Mariella.Blu" required>

            <label for="company">Ragione Sociale Azienda</label>
            <input type="text" id="company" class="input-field" name="ragione_sociale" value="RagioneSociale11" required>

            <label for="address">Via Azienda</label>
            <input type="text" id="address" class="input-field" name="via_azienda" value="Via nuova 23" required>

            <label for="phone">Numero di Telefono Azienda</label>
            <input type="tel" id="phone" class="input-field" pattern="[0-9]{10}" name="numero_telefono" value="1234567891" required>

            <button type="submit" class="submit-btn" name="registra_relatore">Registra</button>
        </form>
    </div>

    <?php
        Grafica::footer();
    ?>

    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js?v=1.1' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js?v=1.1' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js?v=1.1' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
</body>
</html>