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
     * Check if the called page have is own module and javascript
     * 
     * @param string $data Page name
     * @return page Include page /module/NomeModulo/mod_util.php  
     */
    function checkModule($data, $pos) {
        if (file_exists(MODULE_PATH . DS . $data . DS . 'mod_util.php')) {
            include_once(MODULE_PATH . DS . $data . DS . 'mod_util.php');
        }
        if (file_exists(MODULE_PATH . DS . $data . DS . 'js_script.php')) {
            include_once(MODULE_PATH . DS . $data . DS . 'js_script.php');
        }
    }
    

    /**
     * CData Converter
     * From DD/MM/YYYY to YYYY-MM-DD and vice-versa
     * 
     * @param string $to "it" or "en"
     * @param string $data Date to be converted
     * @return string Converted Date
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
     * PHP do not have a "between" function...so here it is
     * (maybe exist...i don't know...and actually im to much afraid
     * to ask :-P )
     * 
     * 
     * @param int $number Number to check
     * @param int $min Start
     * @param int $max End
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
     * TIMESTAMP converter
     * 
     * @param string $tms
     * @return string Data e ora
     * @example deTimestamp("1383651685") -> 05/11/2013 11:41:25
     */
    function deTimeStamp($tms, $format = "it") {
        $dirty = explode(" ", $tms);

        $date = $this->dataConvert($format, $dirty[0]);
        $time = $dirty[1];

        return $date . " " . $time;
    }

    /**
     * Get DATE TIME difference
     * very useful if you want to calculate exact age
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
     * Color String Comparison
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
     * Usually happen that you have First Name and Surname on same row
     * This Split surname and name
     * 
     * @param string $col1 Nominativo
     * @param string $divider Se separato con qualcosa
     * @return string Splitted names
     * @example nominativoSplit("DI MEO GIOVANNI ANTONIO") => surname[DI MEO], name[GIOVANNI ANTONIO]
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
     * Echo del contenuto della cartella "files" 
     * @todo Remember why this is here
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
