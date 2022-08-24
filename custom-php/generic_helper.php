<?php

function getServerAddresWithoutHome(){
    $contextDocumentRoot = $_SERVER["CONTEXT_DOCUMENT_ROOT"]; 
    $home = $_SERVER["HOME"];
    
    return str_replace($home, "", $contextDocumentRoot);
}