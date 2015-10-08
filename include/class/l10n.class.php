<?php

class l10n {
    function __construct($lang) {
        $this->loadLang($lang);
        return $this;
    }
    
    function loadLang($lang){
        return true;
    }
}

