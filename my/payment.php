<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/config.php');
global $DB,$USER;




$host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'];

// var_dump($host);
// die();

// require_once( $CFG->dirroot . '/config.php');
// require_once($CFG->dirroot . '/my/lib.php');

// // TODO get all params
// $packageId   = intVal($_GET['packageid']);    // Turn editing on and off
// $userId  = intVal($_GET['userid']);
// $campanyId  = intVal($_GET['companyid']);

require_login();
$packageId   = intVal($_GET['packageid']);
$userId  = intVal($_GET['userid']);
$companyId  = intVal($_GET['companyid']);

$user = $DB->get_record('user', array('id'=>$userId));
$company = $DB->get_record('company', array('id'=>$companyId));
$package = $DB->get_record('packages', array('id'=>$packageId));

?>
<link rel="stylesheet" href="<?php echo $host ?>/custom-css/card.css">
        
    <section id="body-area">

        <form method="POST" action="">
            <div class="form-container">
                <div class="personal-information">
                    <h1 style="margin-top: 12px !important;">Payment details for <b><?php echo $package->type. ' '.$package->name ?></b></h1>
                </div> <!-- end of personal-information -->
                
                <input id="column-left" type="text" name="customer_name" placeholder="Full Name" required="required" />
                <input id="column-right" type="text" name="email" placeholder="Email" required="required" />
                <input id="input-field" type="text" name="address" placeholder="Address" required="required" />
                <input id="column-left" type="text" name="country" placeholder="Country" required="required" />
                <input id="column-right" type="text" name="postal_code" placeholder="Postal Code" required="required" />
                
                <div class="personal-information">
                    <h1 style="margin-top: 12px !important;">Card details</h1>
                </div> <!-- end of personal-information -->

                <input id="column-left" type="text" name="first-name" placeholder="First Name" required="required" />
                <input id="column-right" type="text" name="last-name" placeholder="Surname" required="required" />
                <input id="input-field" type="text" name="number" placeholder="Card Number" required="required" />
                <input id="column-left" type="text" name="expiry" placeholder="MM / YY" required="required" />
                <input id="column-right" type="text" name="cvc" placeholder="CCV" required="required" />

                <div class="card-wrapper"></div>
                <input id="id" name="id" type="text" style="display: none !important;" value="">
                <input id="amount" name="amount" type="text" style="display: none !important;" value="">
                <input id="current_url" name="current_url" type="text" style="display: none !important;">
                <input id="input-button" name="submit" type="submit" onclick="confirmOrder(event);" style="font-size: 1.2em;!important;" value="Pay £<?php echo number_format($package->amount, 2)?>"/>

            </div>
        </form>
        
        <div id="payment-request-button">
          <!-- A Stripe Element will be inserted here. -->
        </div>
        
        <div class="row">
            <div id="card-element">
                <!--Stripe.js injects the Card Element-->
            </div>
        </div>

    </section><!-- #body-area -->

 <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://js.stripe.com/v3/"></script>
 <script src="<?php echo $host ?>/custom-js/jquery.card.js"></script>
    <script src="<?php echo $host ?>/custom-js/card.js"></script>
    <!--<script src="./assets/js/card.js"></script>-->
    
<script>
    var stripe = Stripe('pk_test_51KkyAqRl8XUoxwKBQRqmxAHldpFtoQ51VxvxPBl9KkHWMkhBVbriUuGOFh6xocvXwXXuO2w0XNfFlO7gpOiGHiKp00BPzsObtg', {
      apiVersion: "2020-08-27",
    });
    
    var paymentRequest = stripe.paymentRequest({
      country: 'US',
      currency: 'usd',
      total: {
        label: 'Demo total',
        amount: 1099,
      },
      requestPayerName: true,
      requestPayerEmail: true,
    });
    
    var elements = stripe.elements();
    var prButton = elements.create('paymentRequestButton', {
      paymentRequest: paymentRequest,
    });
    
    // Check the availability of the Payment Request API first.
    paymentRequest.canMakePayment().then(function(result) {
      if (result) {
        prButton.mount('#payment-request-button');
      } else {
        document.getElementById('payment-request-button').style.display = 'none';
      }
    });
</script>


