<?php

/**
 * HTML
 * Classe per la creazione dinamica di contenuti HTML
 * @Versione 1.0
 * @Data 2014-06-27
 * @author Antonio D'Angelo <dangeloantonio179@gmail.com>
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/ Licenza CC
 * @copyright (c) 2014, Antonio D'Angelo
 */
class html {

    /**
     * Crea tabella dinamica in modo automatico
     * 
     * @param string $dataTableName ID da assegnare alla tabella non dimenticare inizializzazione jquery
     * @param string $tableName Nome tabella mysql
     * @param array $a_colName Titoli delle colonne
     * @param array $a_fields Nome dei campi come db
     * 
     * @todo make a_colName and a_fields in an 1 array 
     */
    function dataTable($dataTableName, $tableName, $a_colName, $a_fields, $ids = null, $n_page = null) {
        //mysql_select_db(DB_NAME,  mysql_connect(DB_HOST, DB_USER, DB_PASS));
        global $mysqli;
        global $page;
        ($n_page == null) ? $page = $page : $n_page = $n_page;
        
        if (count($a_colName) != count($a_fields)) {
            echo "errore #3001";
            exit();
        }
        $numCol = count($a_colName);
        ($ids == null) ? $ids = "id" : $ids = $ids;
        $q = "SELECT $ids, ";
        foreach ($a_fields AS $campo) {
            $q .= $campo . ", ";
        }
        $q = substr($q, 0, -2);
        $q .= " FROM $tableName";

        $result = $mysqli->query($q);
        if (!$result){
            if(DEBUG){
           die("ERRR=> " . mysqli_error($mysqli));
            } else {
                die("Errore");
            }
        }
        echo "<table id=\"$dataTableName\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"table table-striped table-bordered table-hover\">";
        echo "<thead>";
        foreach ($a_colName AS $col) {
            echo "<th>" . $col . "</th>";
        }
        echo "<th>Actions</th>";
        echo "</thead>";
        //$count = $result->affected_rows();
        //var_dump($count);
        $count = 1;
        if ($count != 0) {
            while ($data = $result->fetch_assoc()) {
                echo '<tr id="' . $data[$ids] . '"> ';
                foreach ($a_fields AS $campo) {
                    echo "<td>" . $data[$campo] . "</td>";
                }
                echo '<td><a href="index.php?' . $n_page . '&module=mod_' . lcfirst($n_page) . '&action=dettaglio&' . $tableName . '_id=' . $data[$ids] . '"> VEDI</a>';
                echo '</tr>';
            }
        } else {
            var_dump($result);
        }
        echo "</table>";
        echo ' <script> $(document).ready(function() { $("#' . $dataTableName . '").dataTable(); });</script>';
    }

    /**
     * Creazione dinamica delle select box
     * prendendo i contenuti dal db.
     * In automatico mettera come value id elemento e come testo il valode del campo
     * 
     * @param string $selectID
     * @param array $dataElements
     * @param string $attribute
     * @uses html::selectBox("mySelect", array("TableName"=>array("campo1", "campo2")), "class=\"myselectClass\" attr=\"val\" " ); 
     * @todo Sono da finire     
     */
    
    function selectBox($selectID, $dataElements, $attribute = "") {
        $html = '<select id="' . $selectID . '"';
        ($attribute != "") ? $html .= " $attribute >" : $html .= " >";
    }
    //$formID, $tableName, $a_colName, $a_fields, $n_page = null
    function formIT($tbl, $ids){
        global $mysqli;
        global $page;
        $columns = array();
        $query = "SELECT * FROM $tbl LIMIT 1";
        if($result = $mysqli->query($query)){
        // Get field information for all columns
            while ($column_info = $result->fetch_field()){
                $columns[$column_info->name] = $column_info->type;
            }
        }
        foreach ($columns as $fieldName=>$fieldType){
            if($fieldName != $ids){
                echo '<div class="form-group">
                      <label>'. $fieldName .'</label>
                      <input name="assistiti['.$fieldName.']" type="'. $this->_convertTypeField($fieldType) .'" class="form-control" placeholder="'.$fieldName.'">
                    </div>';
            }
        }
    }
    
    /**
     * Function for convert mysqli::fetch_fields from number to What is
     * 
     * 
     * @param int $type Type Vaore restituito da mysqli::fetch_field
     * @return string $caga WTH am I ?
     */
    function _convertTypeField($type){
        $caga = "";
        switch ($type){
            case 1   : $caga = "number"; break;
            case 2   : $caga = "number"; break;
            case 3   : $caga = "number"; break;
            case 4   : $caga = "number"; break;
            case 5   : $caga = "number"; break;
            case 7   : $caga = "date"; break;
            case 8   : $caga = "number"; break;
            case 9   : $caga = "number"; break;
            case 10  : $caga = "date"; break;
            case 11  : $caga = "time"; break;
            case 12  : $caga = "datetime"; break;
            case 13  : $caga = "date"; break;
            case 16  : $caga = "number"; break;
            case 246 : $caga = "decimal"; break;
            case 252 : $caga = "text"; break;
            case 253 : $caga = "text"; break;
            case 254 : $caga = "text"; break;
/*            case 1 : echo "TINYINT/BOOL"; break;
            case 2 : echo "SMALLINT"; break;
            case 3 : echo "INTEGER"; break;
            case 4 : echo "FLOAT"; break;
            case 5 : echo "DOUBLE"; break;
            case 7 : echo "TIMESTAMP"; break;
            case 8 : echo "BIGINT/SERIAL"; break;
            case 9 : echo "MEDIUMINT"; break;
            case 10 : echo "DATE"; break;
            case 11 : echo "TIME"; break;
            case 12 : echo "DATETIME"; break;
            case 13 : echo "YEAR"; break;
            case 16 : echo "BIT"; break;
            case 246 : echo "DECIMAL/NUMERIC/FIXED"; break;
            case 252 : echo "TINYBLOB/BLOB/TINYTEXT/TEXT/MEDIUMTEXT/LONGTEXT"; break;
            case 253 : echo "VARCHAR/VARBINARY"; break;
            case 254 : echo "CHAR/ENUM/SET/BINARY"; break;*/
        }
        return $caga;
    }

}