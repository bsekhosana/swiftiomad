<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';

function getStripeClient(){
    
   return new \Stripe\StripeClient('sk_test_51KkyAqRl8XUoxwKBsLHVMOqMAwuge5p3SL71CJlsobvMco9QzzMnUNo8b6BSMrHBtfCvSagzoBehtPMhlD57c45l00joUd5tnw');
        
}