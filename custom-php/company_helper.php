<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';

function isUserObjectAManager($user){
    
    return !empty($user) && $user->managertype == 1;

}