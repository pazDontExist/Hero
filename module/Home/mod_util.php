<?php

/**
 * Informazioni sul modulo
 * @param string name Nome del modulo
 * @param string version Versione del modulo
 * @param string author Autore del modulo
 * @param array pages Elenco delle pagine appartenenti al modulo
 */
$Module = array(
    "name" => "mod_home",
    "version" => "1.0",
    "author" => "D'Angelo Antonio",
    "pages" => array("dispatch", "mia_pagina")
);

$GLOBALS["ModuleInfo"] = $Module;

/*** MODULE CLASS/FUNCTION ***/

function foo(){
    echo "BAR";
}

/***********************/