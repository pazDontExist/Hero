<?php

/**
 * DB_HANDLER
 * Classe connessione al DB
 * @Versione 0.2
 * @Data 2015-09-28
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class dbhandler {
    var $_method = "";
    function __construct($method) {
        $this->{$method}();
    }
    
    /*** MENU SQL ***/
    function menuLoader($role){
        global $mysqli;
        $d = $mysqli->query("SELECT * FROM menu WHERE (role = '$role' OR role = 'Global' AND parentof = 0 ORDER BY sortorder ASC");
        while ( $mnu = $d->fetch_assoc() ) {
            echo '<li><a href="'. $mnu['link'] .'">' . $mnu['name'] .'</a>';
                $this->loadSub($mnu['id']);
            echo '</li>';
        }
    }
    
    function loadSub($mnuID){}
    
    /*** LOGIN SQL ***/
    
    function login(){}
    
    /*** PROFILO SQL ***/
    function myData(){
        echo "SONO DIO";
    }
}
