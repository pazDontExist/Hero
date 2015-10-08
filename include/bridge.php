<?php

/**
 * Includi in modo automatico il file della classe richiamata
 * 
 * @param string $class_name Nome Classe
 * @return pageInclusion Inclusione pagina che contiene la classe
 * @todo Aggiungere codice autoscrivente...scrivi e crea una classe se non esiste
 */
function __autoload($class_name) {
    include_once (SITE_PATH . DS . 'include' . DS . 'class' . DS . $class_name . '.class.php');
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
