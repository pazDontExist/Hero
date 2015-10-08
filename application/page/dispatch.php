<?php

sec_login::sec_session_start();
if (!isset($_SESSION['username']))
    exit('Hack Attempt! Reported!');

//im gonna be the best API