<?php

/**
 * Module Info
 * @param string name Module Name
 * @param string version Version
 * @param string author Who write this module
 * @param array pages Allowed Pages
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