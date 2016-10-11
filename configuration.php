<?php

/*** DEVELOPING OPTION ***/
define('DEBUG', true);

/*** !!! WARNING !!! SOME PATH DEFINER ***/
define('SITE_PATH', getcwd());
define('DS', DIRECTORY_SEPARATOR);
define('SITE', SITE_PATH . DS . 'application');
define('MODULE_PATH', SITE_PATH . DS . 'module');
define('FPDF_FONTPATH', SITE_PATH . DS . 'include' . DS . 'font' . DS);
/*** END DI COSE CHE NON PUOI TOCCARE ***/


/*** DATABASE ***/

define('DB_HOST', "localhost");
define('DB_USER', "utente");
define('DB_PASS', "password");
define('DB_NAME', "hero");

/**************************/

/*** INFO ***/
define('APP_NAME', "MyApp"); // nome app
define('APP_VERSION', "1.0.0"); // versione
define('APP_SLOGAN', "Wow! Im Done!"); //slogan
define('APP_LAST_UPDATE', "08/10/2015"); //slogan

/*** AUTHOR ***/
define('DEVELOPER', "MyName IS");
define('DEVELOPER_MAIL', 'my@email.com');
define('DEVELOPER_URL', 'https://plus.google.com/+AntonioDAngelo');

// mail
define("STRIKETEAM_EMAIL", "email@mail.com");
define("LOG_FILE", "error.log");

// tipi
define("DEST_EMAIL", "1");
define("DEST_LOGFILE", "3");

/**
 * my_error_handler($errno, $errstr, $errfile, $errline)
 *
 * Author(s):  Dont Remember who is original author
 * Revision by: Antonio D'Angelo
 * Date: 06/04/2014
 * 
 * custom error handler
 *
 * Parameters:
 *  $errno:   Error level
 *  $errstr:  Error message
 *  $errfile: File in which the error was raised
 *  $errline: Line at which the error occurred
 */
function my_error_handler($errno, $errstr, $errfile, $errline) {
    switch ($errno) {
        case E_USER_ERROR:
            // Send an e-mail to the administrator
            error_log("[ " . date("d/m/Y H:i:s") . " ] - Error: $errstr \n Fatal error on line $errline in file $errfile \n", DEST_EMAIL, STRIKETEAM_EMAIL);

            // Write the error to our log file
            error_log("[ " . date("d/m/Y H:i:s") . " ] - Error: $errstr \n Fatal error on line $errline in file $errfile \n", DEST_LOGFILE, LOG_FILE);
            break;

        case E_USER_WARNING:
            // Write the error to our log file
            error_log("[ " . date("d/m/Y H:i:s") . " ] - Warning: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
            break;

        case E_USER_NOTICE:
            // Write the error to our log file
            error_log("[ " . date("d/m/Y H:i:s") . " ] - Notice: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
            break;

        default:
            // Write the error to our log file
            error_log("[ " . date("d/m/Y H:i:s") . " ] - Unknown error [#$errno]: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
            break;
    }

    // Don't execute PHP's internal error handler
    return TRUE;
}

// Use set_error_handler() to tell PHP to use our method
$old_error_handler = set_error_handler("my_error_handler");