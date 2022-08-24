<?php
// define('__ROOT__', dirname(dirname(__FILE__)));
require_once('config.php');
require_once(__DIR__ . '/lib/moodlelib.php');
//require '/lib/moodlelib.php';
global $DB;

require 'vendor/autoload.php';

// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

// This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51KkyAqRl8XUoxwKBsLHVMOqMAwuge5p3SL71CJlsobvMco9QzzMnUNo8b6BSMrHBtfCvSagzoBehtPMhlD57c45l00joUd5tnw');
// Replace this endpoint secret with your endpoint's unique secret
// If you are testing with the CLI, find the secret by running 'stripe listen'
// If you are using an endpoint defined with the API or dashboard, look in your webhook settings
// at https://dashboard.stripe.com/webhooks

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_qaCIrDPXrR0mXGic1s3oQBCrxv1qrxZK';

$payload = @file_get_contents('php://input');
$event = null;

try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo '⚠️  Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}
// if ($endpoint_secret) {
//   // Only verify the event if there is an endpoint secret defined
//   // Otherwise use the basic decoded event
//   $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
//   try {
//     $event = \Stripe\Webhook::constructEvent(
//       $payload, $sig_header, $endpoint_secret
//     );
//   } catch(\Stripe\Exception\SignatureVerificationException $e) {
//     // Invalid signature
//     echo '⚠️  Webhook error while validating signature.';
//     http_response_code(400);
//     exit();
//   }
// }

// var_dump($event);

echo 'working on event type ' . $event->type;

// Handle the event
switch ($event->type) {
  case 'customer.subscription.updated':
    $data = $event->data;
    $subscriptionSchedule = $event->data->object;
    
    $subscription = $DB->get_record('subscriptions', array('subscription_id' => $subscriptionSchedule->id));
    
    if(empty($subscription)){
        echo '⚠️ No subscription found error';
        http_response_code(400);
        exit();
    }
    
    $package = $DB->get_record('packages', array('id' => $subscription->package_id));
    
    // Updated subscription plan
    if($package->priceid != $subscriptionSchedule->plan->id){
        
        $newPackage = $DB->get_record('packages', array('priceid' => $subscriptionSchedule->plan->id));

        $subscription->package_id = $newPackage->id;
        
        $DB->update_record('subscriptions', $subscription); 
        
    }
    
    // Cancelled subscription
    if(!empty($subscriptionSchedule->cancel_at)){

        if($subscription->status == 'Pending Cancellation'){
            echo '⚠ ️Subscription already pending cancellation error';
            http_response_code(400);
            exit();
        }
        
        $subscription->status = 'Pending Cancellation';
        
        $subscription->cancel_at = date("Y-m-d H:i:s",$subscriptionSchedule->cancel_at);

        $updated = $DB->update_record('subscriptions', $subscription);
    
    }else if(empty($subscriptionSchedule->cancel_at) && 
                !empty($data->previous_attributes->cancel_at)){
                    
        $subscription->status = 'Active';
        
        $cancel_at = null;

        $updated = $DB->update_record('subscriptions', $subscription);
        
    }
    
    $subscription->current_period_end = date("Y-m-d H:i:s",$subscriptionSchedule->current_period_end);
    
    $updated = $DB->update_record('subscriptions', $subscription);
    
  case 'customer.subscription.deleted':
    // todo - fully cancel the user's subscription
    $data = $event->data;
    $subscriptionSchedule = $event->data->object;
    
    $subscription = $DB->get_record('subscriptions', array('subscription_id' => $subscriptionSchedule->id));
    
    if(empty($subscription)){
        echo '⚠️ No subscription found error';
        http_response_code(400);
        exit();
    }
    
    // Overdue subscription
    if(intVal($subscription->attempt_count) >= 3){
        
        $subscription->status = 'Paused';
        
        $subscription->paused_at =  date("Y-m-d H:i:s");

        $updated = $DB->update_record('subscriptions', $subscription);
        
    }else{
        
        // Cancelled subscription
        if($subscriptionSchedule->status == 'canceled'){
            
            $subscription->status = 'Cancelled';
    
            $updated = $DB->update_record('subscriptions', $subscription);
            
        }
        
    }
    
    break;
    
    
  case 'invoice.payment_failed':
    $invoiceObject = $event->data->object;
    
    $subscription = $DB->get_record('subscriptions', array('subscription_id' => $invoiceObject->subscription));
    
    if(empty($subscription)){
        echo '⚠️ No subscription found error';
        http_response_code(400);
        exit();
    }
    
    $subscription->status = 'Overdue';
    
    $subscription->attempt_count = $invoiceObject->attempt_count;
    
    $subscription->next_payment_attempt = date("Y-m-d H:i:s",$invoiceObject->next_payment_attempt);
    
    $subscription->latest_invoice = $invoiceObject->hosted_invoice_url;

    $updated = $DB->update_record('subscriptions', $subscription);
    
    $tryCountString = '';
    
    switch ($invoiceObject->attempt_count){
        case 1:
            $tryCountString = '1st';
            break;
            
        case 2:
            $tryCountString = '2nd';
            break;
        
        case 3:
            $tryCountString = '3rd and final';
            break;
    }
   
    //Send email
    $package = $DB->get_record('packages', array('id' => $subscription->package_id));
    $toUser = $DB->get_record('user', array('id' => $subscription->user_id));
    $fromUser = 'Swift';
    $subject = 'Payment attempt failed';
    $messageText = "Hi $toUser->firstname $toUser->lastname \n\nYour <b>$tryCountString</b> payment for <b>$package->type $package->name </b>has failed, please make a payment of <b>$$package->amount</b> as soon as possible. \n\nAlternatively, <a href='$invoiceObject->hosted_invoice_url'>Click here</a> to pay now.\n\nRegards \nSwiftLearn";        
    
    
    email_to_user($toUser, $fromUser, $subject, $messageText, $messageHtml, ", ", false);
      
    break;
    

  case 'invoice.payment_succeeded':
    $invoiceObject = $event->data->object;
    
    $subscription = $DB->get_record('subscriptions', array('subscription_id' => $invoiceObject->subscription));
    
    if(empty($subscription)){
        echo '⚠️ No subscription found error';
        http_response_code(400);
        exit();
    }
    
    $subscription->status = 'Active';
    
    $subscription->attempt_count = 0;
    
    $subscription->next_payment_attempt = null;

    $subscription->latest_invoice = $invoiceObject->hosted_invoice_url;

    $updated = $DB->update_record('subscriptions', $subscription);
    
    //Send email
    $package = $DB->get_record('packages', array('id' => $subscription->package_id));
    $toUser = $DB->get_record('user', array('id' => $subscription->user_id));
    $fromUser = 'Swift';
    $subject = 'Payment success';
    $messageText = "Hi $toUser->firstname $toUser->lastname \n\nYour payment of <b>$$package->amount</b> for the <b>$package->type $package->name </b>has been made successfully. \n\nTo view your invoice, <a href='$invoiceObject->hosted_invoice_url'>Click here</a>.\n\nRegards \nSwiftLearn";        
    
    
    email_to_user($toUser, $fromUser, $subject, $messageText, $messageHtml, ", ", false);
    
      
    break;
    
  default:
    echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);