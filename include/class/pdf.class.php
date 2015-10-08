<?php

/**
 * Extend a FPDF per la creazione dei file pdf
 */
/**
 * PDF
 * Extend ad FPDF in modo da gestire la creazione dei documenti 
 * in modo più semplice e dinamico
 * 
 * @Versione 0.5
 * @Data 2014-06-27
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class pdf extends fpdf {

    /**
     *
     * @var string Numero Caso CR 
     */
    var $CR = "";

    /**
     *
     * @var string Anno Registrazione CR
     */
    var $ANNO_CR = "";

    /**
     * Assegna numero caso canreg
     * @param string $num Numero Caso CR
     */
    function _setCR($num) {
        $this->CR = $num;
    }

    /**
     * Assegna numero caso canreg
     * @param string $num Numero Caso CR
     */
    function _setAnnoCR($ann) {
        $this->ANNO_CR = $ann;
    }

    /**
     * Effettua Override header della classe principale
     */
    function Header() {
        /**
         * Logo
         */
        $this->Image('img/logo_asl.png', 70, 10, 70);
        /**
         * Font
         */
        $this->SetFont('Helvetica', 'B', 15);

        $this->Cell(80);
        /**
         * Titolo
         */
        $this->Cell(30, 70, 'Monitoraggio Ambientale e Registro Tumori', 0, 0, 'C');

        $this->Ln(10);
        $this->SetFont('Helvetica', 'B', 10);
        $this->cell(100);
        $this->Cell(100, 20, 'CANREG #' . $this->CR, 0, 0, 'C');
        $this->Ln(10);
        $this->Cell(290, 10, 'ANNO ' . $this->ANNO_CR, 0, 0, 'C');
        $this->Ln(40);
    }

    /**
     * Effettua Override footer della classe principale
     * @todo Aggiungere: data generazione documento, ID operatore
     */
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb} - Stampato con ' . APP_NAME . ' | Creato da ' . DEVELOPER, 0, 0, 'C');
        $this->SetY(-20);
        $this->Cell(0, 10, 'DOCUMENTO USO INTERNO - VIETATA OGNI RIPRODUZIONE E/O VISIONE NON AUTORIZZATA');
    }

}
