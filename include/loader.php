<?php
require_once (SITE_PATH . DS . 'include' . DS . 'bridge.php');
$hero = new hero();
$request = $_SERVER['QUERY_STRING'];
$parsed = explode('&', $request);
$page = array_shift($parsed);
($page == "") ? $page = "Home" : $page = $page;
$getVars = array();
foreach ($parsed as $argument) {
    list($variable, $value) = @split('=', $argument);
    $getVars[$variable] = $value;
}

$target = SITE . DS . 'page' . DS . $page . '.php';
$GLOBALS['page'] = $page;

/**
 * Pagine per il quale NON si deve caricare header e footer
 */
$exclude = array("dispatch", "Login", "Javascript", "Logout" );

if (!in_array($page, $exclude) && @$getVars['action'] != 'dispatch') {
    include_once(SITE . DS . 'include' . DS . 'header.php');
    if (file_exists($target)) {
        $hero->checkModule($page, $getVars['action']);
        include_once ($target);
    } else {
        include_once(SITE . DS . 'page' . DS . 'Home.php');
    }
    include_once(SITE . DS . 'include' . DS . 'footer.php');
} else {
    if (file_exists($target)) {
        $hero->checkModule($page, $getVars['action']);
        include_once ($target);
    }
}