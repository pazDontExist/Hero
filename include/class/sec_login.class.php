<?php

error_reporting(0);

/**
 * SEC_LOGIN
 * Classe per la gestione del login sicuro e supporta anche protocollo HTTPS
 * @Versione 1.0 rev 12
 * @Data 2014-06-27
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class sec_login {

    /**
     * Crea Sessione sicura
     * effettua ovverride parametri esistenti per le sessioni
     */
    function sec_session_start() {
        $session_name = 'sec_session_id'; // Imposta un nome di sessione
        $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
        $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
        ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
        $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
        session_start(); // Avvia la sessione php.
        session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
    }

    /**
     * Funzione per controllo login
     * 
     * @param string E-Mail utente
     * @param string Password Utente
     * @param object Oggetto Connessione al db
     * @return boolean True se tutto ok FALSE se qualcosa va storto
     */
    function login($user, $password, $mysqli) {
        // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
        if ($stmt = $mysqli->prepare("SELECT id, nomeutente, nome, cognome, sesso, datanas, email, password, salt, status FROM utenti WHERE nomeutente = ? LIMIT 1")) {
            $stmt->bind_param('s', $user); // esegue il bind del parametro '$email'.
            $stmt->execute(); // esegue la query appena creata.
            $stmt->store_result();
            $stmt->bind_result($user_id, $username, $nome, $cognome, $sesso, $datanas, $mail, $db_password, $salt, $status); // recupera il risultato della query e lo memorizza nelle relative variabili.
            $stmt->fetch();
            $password = hash('sha512', $password . $salt); // codifica la password usando una chiave univoca.
            if ($stmt->num_rows == 1) { // se l'utente esiste
                //echo "<script> alert(\"user exist\");</script>";
                // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
                if ($this->checkbrute($user_id, $mysqli) == true) {
                    echo "Account Bloccato causa troppi tentativi falliti di login";
                    return false;
                } else {
                    if ($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
                        $_SESSION['user_id'] = $user_id;
                        $username = preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "", $username); // ci proteggiamo da un attacco XSS
                        $_SESSION['username'] = $username;
                        $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
                        $_SESSION['nome'] = $nome;
                        $_SESSION['cognome'] = $cognome;
                        $_SESSION['mail'] = $mail;
                        $_SESSION['sesso'] = $sesso;
                        $_SESSION['datanas'] = $datanas;
                        $_SESSION['status'] = $status;
                        return true;
                    } else { // Password incorretta. Registriamo il tentativo fallito nel database.
                        $now = time();
                        $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
                        return false;
                    }
                }
            } else {// L'utente inserito non esiste.
                echo "<script> alert(\"user dexist\");</script>";
                return false;
            }
        }
    }

    /**
     * Controlla se è sotto bruteforce
     * 
     * @param int ID Utente
     * @param object Oggetto Connessione al db
     * @return boolean TRUE se sotto bruteforce FALSE se ok
     */
    function checkbrute($user_id, $mysqli) {
        // Recupero il timestamp
        $now = time();
        // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
        $valid_attempts = $now - (2 * 60 * 60);
        if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
            $stmt->bind_param('i', $user_id);
            // Eseguo la query creata.
            $stmt->execute();
            $stmt->store_result();
            // Verifico l'esistenza di più di 5 tentativi di login falliti.
            if ($stmt->num_rows > 5) {
                $mysqli->query("UPDATE utenti SET status = 4 WHERE user_id = $user_id ");
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Funzione per controllare accesso alle pagine
     * limitandolo soloa gli utenti che hanno effettuato il login
     * 
     * @param type $mysqli Connessione al db
     * @return boolean
     */
    function login_check($mysqli) {
        // Verifica che tutte le variabili di sessione siano impostate correttamente
        if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];
            $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
            if ($stmt = $mysqli->prepare("SELECT password FROM utenti WHERE id = ? LIMIT 1")) {
                $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
                $stmt->execute(); // Esegue la query creata.
                $stmt->store_result();

                if ($stmt->num_rows == 1) { // se l'utente esiste
                    $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
                    $stmt->fetch();
                    $login_check = hash('sha512', $password . $user_browser);
                    if ($login_check == $login_string) {
                        return true; // Login eseguito!!!!
                    } else {
                        return false; //  Login NON eseguito
                    }
                } else {
                    return false; // Login non eseguito
                }
            } else {
                return false; // Login non eseguito
            }
        } else {
            return false; // Login non eseguito
        }
    }

}
