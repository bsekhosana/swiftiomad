<?php
use Stripe\Stripe;
use Stripe\WebhookEndpoint;
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';
// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
\Stripe\Stripe::setApiKey('sk_test_51KkyAqRl8XUoxwKBsLHVMOqMAwuge5p3SL71CJlsobvMco9QzzMnUNo8b6BSMrHBtfCvSagzoBehtPMhlD57c45l00joUd5tnw');

$host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'];

// The price ID passed from the front end.
$priceId = $_POST['priceId'];
$companyId = $_POST['companyId'];
$userId = $_POST['userId'];
$packageId = $_POST['packageId'];
// $priceId = '{{PRICE_ID}}';

$session = \Stripe\Checkout\Session::create([
  'mode' => 'subscription',
  'line_items' => [[
    'price' => $priceId,
    // For metered billing, do not pass quantity
    'quantity' => 1,
  ]],
  'success_url' => $host.'/my/index.php?session_id={CHECKOUT_SESSION_ID}&companyid='.$companyId.'&packageid='.$packageId.'&userid='.$userId,
  'cancel_url' => $host.'/my/index.php?canceled=1',
]);

// $session = \Stripe\Checkout\Session::create([
//   'success_url' => $host.'/my/index.php?session_id={CHECKOUT_SESSION_ID}',
//   'cancel_url' => $host.'/my/stripe/canceled.html',
//   'mode' => 'subscription',
//   'line_items' => [[
//     'price' => $priceId,
//     // For metered billing, do not pass quantity
//     'quantity' => 1,
//   ]],
//   'setup_intent_data' => ['metadata'=>[
//                                 'companyid'=> $companyId,
//                                 'userid'=> $userId,
//                                 'packageid'=> $packageId,
                                
//                         ]],
// ]);

// Redirect to the URL returned on the Checkout Session.
// With vanilla PHP, you can redirect with:
//   header("HTTP/1.1 303 See Other");
header("Location: " . $session->url);
?>