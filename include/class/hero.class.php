<?php

/**
 * HERO
 * Classe per la gestione di questo pattern/framework
 * @Versione 2.0
 * @Data 2014-06-27
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class hero {

    /**
     * Controlla l'esistenza del modulo di una pagina
     * 
     * @param string $data Nome della pagina
     * @return page Inclusione della pagina /module/NomeModulo/mod_util.php  
     * @TODO includere checkJS qui dentro 
     */
    function checkModule($data, $pos) {
        if (file_exists(MODULE_PATH . DS . $data . DS . 'mod_util.php')) {
            include_once(MODULE_PATH . DS . $data . DS . 'mod_util.php');
        }
    }
    
    function checkJS($data){
        if (file_exists(MODULE_PATH . DS . $data . DS . 'js_script.php')) {
            include_once(MODULE_PATH . DS . $data . DS . 'js_script.php');
        }
    }
    /**
     * Conversione di una data
     * da formato italiano a inglese e viceversa
     * 
     * @param string $to "it" oppure "en"
     * @param string $data Data da convertire
     * @return string Data Convertita
     * @uses dataConvert("en", "28/11/1989") return 1989-11-28
     * @uses dataConvert("it", "1989-11-28") return 28/11/1989
     */
    function dataConvert($to, $data) {
        switch ($to) {
            case "it":
                $rsl = explode('-', $data);
                $rsl = array_reverse($rsl);
                return implode($rsl, '/');
                break;
            case "en":
                $rsl = explode('/', $data);
                $rsl = array_reverse($rsl);
                return implode($rsl, '-');
                break;
        }
    }

    /**
     * Funzione per vedere se un numero è compreso in
     * un determinato intervallo
     * 
     * @param int $number Numero da controllare
     * @param int $min Start Range Controllo
     * @param int $max End Range Controllo
     * @return boolean 
     */
    function between($number, $min, $max) {
        if ($number >= $min && $number <= $max) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Converte da TIMESTAMP a data e ora Italiana
     * 
     * @param string $tms
     * @return string Data e ora
     * @example Entra questo => 1383651685 ed esce 05/11/2013 11:41:25
     */
    function deTimeStamp($tms) {
        $dirty = explode(" ", $tms);

        $date = $this->dataConvert("it", $dirty[0]);
        $time = $dirty[1];

        return $date . " " . $time;
    }

    /**
     * Ritorna array con la differenza temporale tra 2 date
     * 
     * @param string Data Inizio
     * @param string Data Fine
     * @return array Ritorna array con differenza temporale = array("days"=>1, "hours"=>"2", "minutes"=>"3", "seconds"=>"4")
     */
    function get_time_difference($start, $end) {
        $uts['start'] = strtotime($start);
        $uts['end'] = strtotime($end);
        if ($uts['start'] !== -1 && $uts['end'] !== -1) {
            if ($uts['end'] >= $uts['start']) {
                $diff = $uts['end'] - $uts['start'];
                if ($days = intval((floor($diff / 86400))))
                    $diff = $diff % 86400;
                if ($hours = intval((floor($diff / 3600))))
                    $diff = $diff % 3600;
                if ($minutes = intval((floor($diff / 60))))
                    $diff = $diff % 60;
                $diff = intval($diff);
                return( array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $diff) );
            }
            else {
                trigger_error("Ending date/time is earlier than the start date/time", E_USER_WARNING);
            }
        } else {
            trigger_error("Invalid date/time data detected", E_USER_WARNING);
        }
        return( false );
    }

    /**
     * Evidenzia comparazione stringhe
     * 
     * @param string $oldString
     * @param string $newString
     */
    function compareStrings($oldString, $newString) {
        $old_array = explode(' ', $oldString);
        $new_array = explode(' ', $newString);

        for ($i = 0; isset($old_array[$i]) || isset($new_array[$i]); $i++) {
            if (!isset($old_array[$i])) {
                echo '<font color="red">' . $new_array[$i] . '</font>';
                continue;
            }

            for ($char = 0; isset($old_array[$i]{$char}) || isset($new_array[$i]{$char}); $char++) {

                if (!isset($old_array[$i]{$char})) {
                    echo '<font color="red">' . substr($new_array[$i], $char) . '</font>';
                    break;
                } elseif (!isset($new_array[$i]{$char})) {
                    break;
                }

                if (ord($old_array[$i]{$char}) != ord($new_array[$i]{$char}))
                    echo '<font color="red">' . $new_array[$i]{$char} . '</font>';
                else
                    echo $new_array[$i]{$char};
            }

            if (isset($new_array[$i + 1]))
                echo ' ';
        }
    }

    /**
     * Splitta campo nominativo in cognome e nome
     * 
     * @param string $col1 Nominativo
     * @param string $divider Se separato con qualcosa
     */
    function nominativoSplit($col1, $divider) {
        $cog = "";
        $nom = "";

        if (count($pieces = @split(" ", $col1)) == 2) {
            $cog = $pieces[0];
            $nom = $pieces[1];
        }

        if (count($pieces = @split(" ", $col1)) == 3) {
            if (strlen($pieces[0]) <= 5) {
                if (substr($pieces[0], 0, 1) == "R") {
                    $cog = $pieces[0];
                    $nom = $pieces[1] . " " . $pieces[2];
                } else {
                    $cog = $pieces[0] . " " . $pieces[1];
                    $nom = $pieces[2];
                }
            } else {
                $cog = $pieces[0];
                $nom = $pieces[1] . " " . $pieces[2];
            }
        }

        if (count($pieces = @split(" ", $col1)) >= 4) {
            if (strlen($pieces[0]) <= 5) {
                $cog = $pieces[0] . " " . $pieces[1];
                $nom = $pieces[2] . " " . $pieces[3];
            } else {
                $cog = $pieces[0];
                $nom = $pieces[1] . " " . $pieces[2] . " " . $pieces[3];
            }
        }

        $nominat = array("cognome" => $cog,
            "nome" => $nom);

        return $nominat;
    }
    
        
    /**
     * Funzione SPERIMENTALE
     * Utente online
     * @global object $mysqli
     * @param string $username
     * @return bool True/False
     */
    function imAlive($username) {
        global $mysqli;
        $user_id = $this->getUserID($username);
        if ($stmt = $mysqli->prepare("UPDATE aliveuser SET time = NOW() WHERE user_id = ? LIMIT 1")) {
            $stmt->bind_param('s', $user_id);
            $stmt->execute();
                return true;
        } else { return false;}
    }
    
    function getStat(){
        global $mysqli;
        $app = array();
        $q = "SELECT MONTH( DATE ) AS rif, SUM( cont ) AS tot FROM marta.search_history GROUP BY MONTH( DATE )";
        $do = $mysqli->query($q);
        echo "[";
        $s = "";
        $cont = 0;
        while ($r = $do->fetch_assoc()){
            $cont++;
            $app[] = $r;
            $s .=  "[$cont, " . $r['tot'] . "],";
        }
        $s = substr($s, 0, -1);
        echo $s . "]";
    }
    
    function monthConvert($n){
        $name = "";
        switch ($n){
            case 1: $name = "Gennaio"; break;
            case 2: $name = "Febbraio"; break;
            case 3: $name = "Marzo"; break;
            case 4: $name =  "Aprile"; break;
            case 5: $name =  "Maggio"; break;
            case 6: $name =  "Giugno"; break;
            case 7: $name =  "Luglio"; break;
            case 8: $name =  "Agosto"; break;
            case 9: $name =  "Settembre"; break;
            case 10: $name =  "Ottobre"; break;
            case 11: $name = "Novembre"; break;
            case 12: $name = "Dicembre"; break;
        }
        
        return $name;
    }
    
    /**
     * Echo del contenuto della cartella "files" 
     */
    function fileList(){
        if ($handle = opendir(SITE_PATH . DS . 'files')) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if($entry != "." && $entry != ".."){
                    echo "<option value=\"$entry\">$entry</option>";
                }
            }
        closedir($handle);
        }
    }
    
    /**
     * Leggi contenuto file
     * 
     * @param string $file
     * @param int $maxLine
     * @return type Mammt !
     */
    function readFiles($file, $maxLine = 1){
        // Reads the file into an array
        $lines = file($file);

        // Cuts off everything after the first three
        $the_rest = array_splice($lines, $maxLine);
        // leaving the first three in the original array $lines
        $first_three = $lines;

        // Stick them back together as strings by implode() with newlines
        $first_three = implode("\n", $first_three);
            return $first_three;
    }
    
    
    function countFileLines($file){
        $linecount = 0;
        $handle = fopen(SITE_PATH . DS . 'files' . DS . $file, "r");
        while(!feof($handle)){
            $line = fgets($handle);
            $linecount++;
        }
        fclose($handle);
        echo number_format($linecount, 0, ",", ".");
        //echo $linecount;
    }
    
    
    function cleanFields($str){
        $bad = "\"'. ç°ò@#`   ";
        for($i=0; $i<=strlen($bad); $i++){
            $str = str_replace($bad[$i], "", $str);
        }
        return $str;
    }
}
