<?php
/**
 * Classe per la generazione automatica del menu
 * in base al livello ed i permessi di un utente
 */
/**
 * MENU
 * Classe per la generazione/creazione dei menu di marta in base
 * al livello ed i permessi di un utente
 * 
 * @Versione 0.6
 * @Data 2014-06-27
 * @Revision 2015-10-06
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class menu {
    /**
     * Crea Menù in base a livello di utenza
     * 
     * @global Object $mysqli Connessione al DB
     * @param string $role Ruolo utente
     */
    function createMenu($role){
        $menu = new dbhandler("menuLoader($role)");
        var_dump($menu);
        /*
        $d = $mysqli->query("SELECT * FROM menu WHERE (role = '$role' OR role = 'Global' AND parentof = 0 ORDER BY sortorder ASC");
        while ( $mnu = $d->fetch_assoc() ) {
            echo '<li><a href="'. $mnu['link'] .'">' . $mnu['name'] .'</a>';
                $this->loadSub($mnu['id']);
            echo '</li>';
        }*/
    }
    
    /**
     * Carica sotto menu voci menu primarie
     * 
     * @global Object $mysqli Connessione al DB
     * @param integer $mnuID ID Voce menu primario
     */
    function loadSub($mnuID){
        global $mysqli;
        if($result = $mysqli->query("")->num_rows != 0){
            echo "";
        }
    }
}