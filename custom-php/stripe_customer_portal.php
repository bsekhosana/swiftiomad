<?php
use Stripe\Stripe;
use Stripe\WebhookEndpoint;
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
\Stripe\Stripe::setApiKey('sk_test_51KkyAqRl8XUoxwKBsLHVMOqMAwuge5p3SL71CJlsobvMco9QzzMnUNo8b6BSMrHBtfCvSagzoBehtPMhlD57c45l00joUd5tnw');

$host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'];

$customerId = $_POST['customer_id'];

// var_dump($_POST);
// die();

// Authenticate your user.
$session = \Stripe\BillingPortal\Session::create([
  'customer' => "$customerId",
  'return_url' => $host.'/my/index.php?portal=1',
]);

// Redirect to the customer portal.
header("Location: " . $session->url);
exit();