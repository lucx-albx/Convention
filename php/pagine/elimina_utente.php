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
    <link rel="stylesheet" href="../../css/home.css?v=3.4">

    <title>Elimina utente - Admin</title>
</head>
<body>
    <?php
        Grafica::navbar_admin();
    ?>

    <div class="content">
        <h2 class="center-text">Elimina utente</h2>

        <form action="../rimuovi_utente.php" method="POST" class="mt-4">
            <label for="first-name">Nome e cognome utente</label>
            <select name="nome_cognome" class="input-field">
                <?php
                    $nom_cog = Database::esegui_query(
                        "
                        SELECT Nome, Cognome
                        FROM Utenti
                        ORDER BY Nome ASC;
                        "
                    ) -> fetch_all(MYSQLI_ASSOC);

                    foreach($nom_cog as $nc){
                        $nmc = $nc['Nome'] . " " . $nc['Cognome'];
                ?>

                <option value="<?=$nmc?>" > 
                    <?=$nmc?>
                </option>

                <?php
                    } 
                ?>
            </select>

            <label for="email">Email utente</label>
            <select name="email" class="input-field">
                <?php
                    $email = Database::esegui_query(
                        "
                        SELECT Email
                        FROM Utenti
                        WHERE Ruolo != 'Admin'
                        ORDER BY Email ASC;
                        "
                    ) -> fetch_all(MYSQLI_ASSOC);

                    foreach($email as $e){
                ?>

                <option value=<?=$e['Email']?> > 
                    <?=$e['Email']?>
                </option>

                <?php
                    } 
                ?>
            </select>

            <div class="radio-buttons-container">
                <div class="radio-button">
                    <input name="radio-group" value="Partecipante" id="radio2" class="radio-button__input" type="radio" checked>
                    <label for="radio2" class="radio-button__label">
                        <span class="radio-button__custom"></span> Partecipante
                    </label>
                </div>
                <div class="radio-button">
                    <input name="radio-group" value="Relatore" id="radio1" class="radio-button__input" type="radio">
                    <label for="radio1" class="radio-button__label">
                        <span class="radio-button__custom"></span> Relatore
                    </label>
                </div>
            </div>

            <button type="submit" class="submit-btn" name="elimina_utente">Elimina</button>
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