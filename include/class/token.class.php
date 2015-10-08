<?php
/**
 * TOKEN
 * Classe per la generazione e controllo di token
 * @Versione 0.1
 * @Data 2014-06-27
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class token {

    /**
     * Crea token
     * @param int $length Lunghezza token da generare
     * @return mix Ritorna token o FALSE
     */
    function make_token($length = 16) {
        if ($length < 8 || $length > 44)
            return false;
        // prendi info sulla lunghezza
        $length_odd = (($length % 2) != 0);
        $length_has_root = ( strpos(sqrt($length), '.') === false);

        $offset = $length_odd ? 1 : 0;
        $key_str = '';

        $key_str .= $keys[(0 + $offset)] = $this->rand_alphanumeric();
        $key_str .= $keys[(($length / 4) - 1 + $offset)] = $this->rand_alphanumeric();
        $key_str .= $keys[(($length / 2) - 1 + $offset)] = $this->rand_alphanumeric();
        $key_str .= $keys[(($length - 2) + $offset)] = $this->rand_alphanumeric();

        $hashed_keys = $length_has_root ? sha1(md5($key_str)) : sha1(sha1($key_str));


        $hash_enum = 0;
        for ($i = 0; $i < $length; $i++) {
            if ($keys[$i] == '') {
                $keys[$i] = $hashed_keys[$hash_enum];
                $hash_enum++;
            }
        }
        ksort($keys);
        return implode($keys, '');
    }

    /**
     * Funzione per controllo token
     * 
     * @param string $str Token da verificare
     * @return bool True se verificato FALSE se non
     */
    function verify_token($str) {
        $length = strlen($str);
        $keys = str_split($str);
        $length_odd = (($length % 2) != 0);
        $length_has_root = ( strpos(sqrt($length), '.') === false);
        $offset = $length_odd ? 1 : 0;
        $key_str = '';
        $key_str .= $keys[$pos1 = (int) (0 + $offset)];
        $key_str .= $keys[$pos2 = (int) (($length / 4) - 1 + $offset)];
        $key_str .= $keys[$pos3 = (int) (($length / 2) - 1 + $offset)];
        $key_str .= $keys[$pos4 = (int) (($length - 2) + $offset)];
        $hashed_keys = $length_has_root ? sha1(md5($key_str)) : sha1(sha1($key_str));
        $hash_string = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i != $pos1 &&
                    $i != $pos2 &&
                    $i != $pos3 &&
                    $i != $pos4) {
                $hash_string .= $keys[$i];
            }
        }
        $hash_length = $length - 4;
        return ( $hash_string == substr($hashed_keys, 0, $hash_length) );
    }

    /**
     * Funzione generazione caratteri random
     * 
     * @return chr Caratteri random
     */
    function rand_alphanumeric() {
        $subsets[0] = array('min' => 48, 'max' => 57); // ascii digits
        $subsets[1] = array('min' => 65, 'max' => 90); // ascii lowercase English letters
        $subsets[2] = array('min' => 97, 'max' => 122); // ascii uppercase English letters
        $s = rand(0, 2);
        $ascii_code = rand($subsets[$s]['min'], $subsets[$s]['max']);
        return chr($ascii_code);
    }

}

?>
