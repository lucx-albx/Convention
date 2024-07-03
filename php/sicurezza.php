<?php
    class Sicurezza {
        public static function crittografa_password($psw){
            return hash("sha256", $psw);
        }

        public static function confondi_stringa($data) {
            $lung = strlen($data);
            $indici = range(0, $lung - 1);
            $email_confusa = '';
        
            while (count($indici) > 0) {
                $indice_casuale = array_rand($indici);
                $carattere = $data[$indici[$indice_casuale]];
                $email_confusa .= $carattere;
        
                unset($indici[$indice_casuale]);
                $indici = array_values($indici);
            }
        
            return $email_confusa;
        }

        public static function genera_token($arg){
            $num1 = rand(1000, 100000);
            $num2 = rand(1000, 100000);
            $stringa_confusa = self::confondi_stringa($arg);
            $componi_stringa = strval($num1) . strval($stringa_confusa) . strval($num2);

            return self::crittografa_password($componi_stringa);
        }
    }
?>