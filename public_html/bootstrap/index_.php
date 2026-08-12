<?php
    /**

     * Fecha: 12/11/2015
     * Descripción: Error Pagina Restringida.
     **/

    require_once('../Libraries/Functions.php');
    $HTML = LoadContentPage('../document_errors/403.html');
    ViewPage($HTML);
?>