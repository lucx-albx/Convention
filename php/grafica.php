<?php
    class Grafica {
        public static function navbar_partecipante() {
            echo "<nav class='navbar navbar-expand-lg navbar-light color-nav'>";
                echo "<a class='navbar-brand' href='./home.php'>Home</a>";
                echo "<label class='navbar-toggler container-menu' data-toggle='collapse' data-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>";
                    echo "<input type='checkbox'>";
                    echo "<div class='checkmark'>";
                        echo "<span></span>";
                        echo "<span></span>";
                        echo "<span></span>";
                    echo "</div>";
                echo "</label>";
                echo "<div class='collapse navbar-collapse' id='navbarNavAltMarkup'>";
                    echo "<div class='navbar-nav'>";
                        echo "<a class='nav-item nav-link' href='./tutti_speech.php'>Tutti speech</a>";
                        echo "<a class='nav-item nav-link' href='./speech_seguiti.php'>Speech seguiti</a>";
                    echo "</div>";
                echo "</div>";
            echo "</nav>";
        }

        public static function navbar_relatore() {
            echo "<nav class='navbar navbar-expand-lg navbar-light color-nav'>";
                echo "<a class='navbar-brand' href='./home.php'>Home</a>";
                echo "<label class='navbar-toggler container-menu' data-toggle='collapse' data-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>";
                    echo "<input type='checkbox'>";
                    echo "<div class='checkmark'>";
                        echo "<span></span>";
                        echo "<span></span>";
                        echo "<span></span>";
                    echo "</div>";
                echo "</label>";
                echo "<div class='collapse navbar-collapse' id='navbarNavAltMarkup'>";
                    echo "<div class='navbar-nav'>";
                        echo "<a class='nav-item nav-link' href='./tutti_speech.php'>Tutti speech</a>";
                        echo "<a class='nav-item nav-link' href='./speech_seguiti.php'>Speech seguiti</a>";
                        echo "<a class='nav-item nav-link' href='./speech_relazionati.php'>Speech relazionati</a>";
                    echo "</div>";
                echo "</div>";
            echo "</nav>";
        }

        public static function navbar_admin() {
            echo "<nav class='navbar navbar-expand-lg navbar-light color-nav'>";
                echo "<a class='navbar-brand' href='./home.php'>Home</a>";
                echo "<label class='navbar-toggler container-menu' data-toggle='collapse' data-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>";
                    echo "<input type='checkbox'>";
                    echo "<div class='checkmark'>";
                        echo "<span></span>";
                        echo "<span></span>";
                        echo "<span></span>";
                    echo "</div>";
                echo "</label>";
                echo "<div class='collapse navbar-collapse' id='navbarNavAltMarkup'>";
                    echo "<div class='navbar-nav'>";
                        echo "<a class='nav-item nav-link' href='./elimina_utente.php'>Elimina utente</a>";
                        echo "<a class='nav-item nav-link' href='./crea_relatore.php'>Aggiungi relatore</a>";
                        echo "<a class='nav-item nav-link' href='./crea_speech.php'>Aggiungi speech</a>";
                        echo "<a class='nav-item nav-link' href='./tutti_speech.php'>Tutti speech</a>";
                    echo "</div>";
                echo "</div>";
            echo "</nav>";
        }

        public static function footer() {
            echo "<div class='footer'>";
                echo "<p>&copy; 2024 Convention Alba Luca Francesco</p>";
            echo "</div>";
        }

        public static function alert_successo($titolo, $messaggio) {
            echo "<div class='success-container col-xl-6 col-lg-6 col-md-6 col-sm-11 col-10'>";
                echo "<div class='success-title'>$titolo</div>";
                echo "<div class='success-message'>$messaggio</div>";
                if($titolo != 'Non segui speech' && $titolo != 'Non relazioni speech' && $titolo != 'Speech inesistenti'){
                    echo "<a href='javascript:history.back()' class='success-button'>Torna indietro</a>";
                }
            echo "</div>";

            echo "<link rel='stylesheet' href='../css/successo.css?v=3.1'>";
        }

        public static function alert_errore($titolo, $messaggio) {
            echo "<div class='error-container col-xl-6 col-lg-6 col-md-6 col-sm-11 col-10'>";
                echo "<div class='error-title'>$titolo</div>";
                echo "<div class='error-message'>$messaggio</div>";
                echo "<a href='javascript:history.back()' class='error-button'>Torna indietro</a>";
            echo "</div>";

            echo "<link rel='stylesheet' href='../css/errori.css?v=3.1'>";
        }

        public static function ui_speech($data) {
            $speech_seguiti = Gestione::carica_speech_seguiti();
            $speech_relazionati = Gestione::speech_relazionati();

            foreach($data as $d){
                $id_speech = $d['IDSpeech'];
                $nome_sala = $d['NomeSala'];
                $id_prog = $d['IDProg'];
                $segue = false;
                $relaziona = false;

                foreach($speech_relazionati as $sr){
                    if($sr['IDProg'] == $d['IDProg']){
                        $relaziona = true;
                    }
                }

                echo "<div class='card-speech col-xl-3 col-lg-3 col-md-5 col-sm-5 col-10'>";
                    echo "<h3 class='lh'> ". $d['Titolo'] ."</h3>";
                    echo "<p class='descrizione lh'> ". $d['Argomento'] ."</p>";
                    echo "<p class='data lh'><strong>Data:</strong> ". $d['FasciaOraria'] ."</p>";
                    echo "<p class='sala lh'><strong>Nome sala:</strong> ". $d['NomeSala'] ."</p>";
                    echo "<p class='disponibilita lh'><strong>Posti disponibili:</strong> ". $d['NpostiSala'] ."</p>";
                    echo "<p class='piano lh'><strong>Numero piano:</strong> ". $d['Numero'] ."</p>";

                    if($speech_seguiti != false){
                        foreach($speech_seguiti as $ss){
                            if($id_speech == $ss['IDSpeech']){
                                $segue = true;
                            } 
                        }
                    }
                    
                    if(Gestione::ruolo_utente() != "Admin"){
                        if($relaziona != true){
                            if($d['NpostiSala'] > 0 && !$segue){
                                echo "<div class='speech-button' onclick='iscriviti_specch(".json_encode($id_prog).", ".json_encode($nome_sala).")'>Iscriviti</div>";
                            } else if($d['NpostiSala'] == 0 && !$segue){
                                echo "<div class='speech-button-disable' disabled>Iscriviti</div>";
                            } else {
                                echo "<div class='speech-button' onclick='disiscriviti_speech(".json_encode($id_prog).", ".json_encode($nome_sala).")'>Disiscriviti</div>";
                            }
                        }
                    } else {
                        echo "<div class='speech-button-error' onclick='elimina_specch(".json_encode($id_speech).")'>Elimina</div>";
                    }
                echo "</div>";
            }

            echo "<script src='../../script/script.js?v=1.2'></script>";
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11?v=1.1'></script>";
        }

        public static function ui_speech_relazionati($data) {
            foreach($data as $d){
                echo "<div class='card-speech col-xl-3 col-lg-3 col-md-5 col-sm-5 col-10'>";
                    echo "<h3 class='lh'> ". $d['Titolo'] ."</h3>";
                    echo "<p class='descrizione'> ". $d['Argomento'] ."</p>";
                    echo "<p class='data'><strong>Data:</strong> ". $d['FasciaOraria'] ."</p>";
                    echo "<p class='sala'><strong>Nome sala:</strong> ". $d['NomeSala'] ."</p>";
                    echo "<p class='disponibilita'><strong>Posti disponibili:</strong> ". $d['NpostiSala'] ."</p>";
                    echo "<p class='piano'><strong>Numero piano:</strong> ". $d['Numero'] ."</p>";
                echo "</div>";
            }
        }
    }
?>
