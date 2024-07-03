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
    

    <title>Crea speech - Admin</title>
</head>
<body>
    <?php
        Grafica::navbar_admin();
    ?>

    <div class="content">
        <h2 class="center-text">Aggiungi speech</h2>

        <form action="../aggiungi_speech.php" method="POST" class="mt-4">
            <label for="email">Email Relatore</label>
            <input type="email" id="email" class="input-field" name="email" value="Mariella.Blu@gmail.com" required>

            <label for="title">Titolo dello Speech</label>
            <input type="text" id="title" class="input-field" name="titolo_speech" value="Speech011" required>

            <label for="text">Testo dello Speech</label>
            <textarea id="text" class="input-field" rows="4" name="testo_speech" required>Del testo per lo speech011</textarea>

            <label for="date">Data dello Speech</label>
            <input type="date" id="date" class="input-field" name="data_speech" value="2024-06-01" required>

            <label for="time">Ora dello Speech</label>
            <input type="time" id="time" class="input-field" name="ora_speech" value="11:30" required>

            <select name="nome_sala" class="input-field">
                <?php
                    $nome_sala = Database::esegui_query(
                        "
                        SELECT Sala.NomeSala
                        FROM Sala 
                        LEFT JOIN Programma ON Sala.NomeSala = Programma.Ek_NomeSala
                        WHERE Programma.Ek_NomeSala IS NULL
                        ORDER BY Sala.NomeSala ASC;
                        "
                    ) -> fetch_all(MYSQLI_ASSOC);
                    
                    if(count($nome_sala) > 0){
                        foreach($nome_sala as $nms){
                ?>

                            <option value="<?=$nms['NomeSala']?>" > 
                                <?=$nms['NomeSala']?>
                            </option>

                <?php
                        }
                    } else {
                ?>
                        <option value="Nessuna sala disponibile"> 
                            Nessuna sala disponibile
                        </option>
                <?php
                    }
                ?>

            </select>

            <button type="submit" class="submit-btn" name="aggiungi_speech">Aggiungi</button>
        </form>
    </div>

    <?php
        Grafica::footer();
    ?>

    <script src='https://code.jquery.com/jquery-3.2.1.slim.min.js?v=1.0' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js?v=1.0' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js?v=1.0' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>
</body>
</html>