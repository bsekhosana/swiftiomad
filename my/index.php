<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    moodlecore
 * @subpackage my
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Stripe\Stripe;

require_once(__DIR__ . '/../config.php');
require_once($CFG->dirroot . '/my/lib.php');
require_once($CFG->dirroot . '/custom-php/subscription_helper.php');
require_once($CFG->dirroot . '/custom-php/company_helper.php');
require_once($CFG->dirroot . '/custom-php/stripe_helper.php');

redirect_if_major_upgrade_required();

// TODO Add sesskey check to edit
$edit   = optional_param('edit', null, PARAM_BOOL);    // Turn editing on and off
$reset  = optional_param('reset', null, PARAM_BOOL);

require_login();

echo "<link rel='stylesheet' href='../custom-css/swift-icons.css'>";

$hassiteconfig = has_capability('moodle/site:config', context_system::instance());
if ($hassiteconfig && moodle_needs_upgrading()) {
    redirect(new moodle_url('/admin/index.php'));
}

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$strmymoodle = get_string('myhome');

if (isguestuser()) {  // Force them to see system default, no editing allowed
    // If guests are not allowed my moodle, send them to front page.
    if (empty($CFG->allowguestmymoodle)) {
        redirect(new moodle_url('/', array('redirect' => 0)));
    }

    $userid = null;
    $USER->editing = $edit = 0;  // Just in case
    $context = context_system::instance();
    $PAGE->set_blocks_editing_capability('moodle/my:configsyspages');  // unlikely :)
    $strguest = get_string('guest');
    $header = "$SITE->shortname: $strmymoodle ($strguest)";
    $pagetitle = $header;

} else {        // We are trying to view or edit our own My Moodle page

    if(isset($_GET['session_id'])){
        require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
        
        $stripe = getStripeClient();
        $session = $stripe->checkout->sessions->retrieve($_GET['session_id'], []);
        
        if(!empty($session)){
            
            $stripeSubscription = $stripe->subscriptions->retrieve(
              $session->subscription,
              []
            );
            $invoiceId = $stripeSubscription->latest_invoice;
            $invoice = $stripe->invoices->retrieve($invoiceId);
            $invoice_hosted_url = $invoice->hosted_invoice_url;
            $companyId = intVal($_GET['companyid']);
            
            $package = $DB->get_record('packages', array('id' =>$_GET['packageid']));
            $newSubscription = createCompanySubscription($DB, $_GET['userid'], $_GET['packageid'], $companyId, $session->subscription, $session->customer,
                                     date("Y-m-d H:i:s",$stripeSubscription->current_period_end), 'Active', $package);
            
        
            $company = $DB->get_record('company', array('id'=>$companyId));
            $userToEmail = $DB->get_record('user',array('id'=>$_GET['userid']));
            $companyURL = $_SERVER['REQUEST_SCHEME']. '://' .$company->hostname;
            $fromUser = 'Swift';
            $subject = 'New subscription';
            $messageText = "Hi $userToEmail->firstname $userToEmail->lastname, \n\nYour payment of <b>$$package->amount</b> has been made successfully. 
                            \nYour company  (<b><a href='$companyURL'>$company->name</a></b>) is now subscribed to our <b>$package->type $package->name</b> plan.
                            \nTo view your invoice, <a href='$invoice_hosted_url'>Click here</a>.\n\nRegards \nSwiftLearn";        
            
            
            email_to_user($userToEmail, $fromUser, $subject, $messageText, $messageHtml, ", ", false);
            
            $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST']. "/my";
            
            echo "<script>alert('Payment successful! Your company is now under the $package->type $package->name package');location.href='$host';</script>";
            
        }else{
            
            $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST']. "/my";
            
            echo "<script>alert('No payment session found.');location.href='$host';</script>";
            
        }
       
    }if(isset($_GET['canceled'])){
        
        $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST']. "/my";
        
        echo "<script>alert('Payment failed, please try again');location.href='$host';</script>";
        
    }else{
        
        require_once($CFG->dirroot.'/local/iomad/lib/company.php');
        $myCompany = company::by_userid($USER->id);
        
        if($myCompany){
            
            $companyUser = $DB->get_record('company_users', array('companyid'=>$myCompany->get('id'), 'userid'=>$USER->id));
            
            // CHECKING IF LOGGED IN USER IS COMPANY'S MANAGER
            if(isUserObjectAManager($companyUser)){
                $companyId = $myCompany->get('id');
                $companySubscription = $DB->get_record_sql("SELECT * FROM {subscriptions} WHERE status = 'Active' OR status = 'Pending Cancellation'  OR status = 'Overdue'  OR status = 'Paused' AND company_id = $companyId;");
         //getActiveCompanySubscription($companyId);
                
                if(empty($companySubscription)){
                    
                    // Check if trial subsciption has been requested
                    if(isset($_GET['activatetrial'])){
                        
                        $package = $DB->get_record('packages', array('type' => 'Trial'));
                        // $subscription = new stdClass();
                        // $subscription->user_id =  intVal($USER->id);
                        // $subscription->package_id =  intVal($package->id);
                        // $subscription->company_id =  $companyId;
                        // $subscription->subscription_id = 'sub_'.generateRandomString(24);
                        // $subscription->customer_id = 'cus_'.generateRandomString(14);
                        // $subscription->current_period_end = date("Y-m-d H:i:s",strtotime('+7 day', time()));
                        // $subscription->status = 'Active';
                        
                        // $DB->insert_record('subscriptions', $subscription);
                        
                        $newSubscription = createCompanySubscription($DB, $USER->id, $package->id, $companyId, 'sub_'.generateRandomString(24), 'cus_'.generateRandomString(14), 
                                            date("Y-m-d H:i:s",strtotime('+7 day', time())), 'Active', $package);
                        
                        $company = $DB->get_record('company', array('id'=>$companyId));
                        
                        // $company->maxusers = $package->num_users;
                        
                        // $DB->update_record('company', $company);
                        
                        $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST']. "/my";
                        
                        $end_date = date("l M j, Y g:iA", strtotime('+7 day', time()));
                        
                        $companyURL = $_SERVER['REQUEST_SCHEME']. '://' .$company->hostname;
                        $fromUser = 'Swift';
                        $subject = 'New trial package subscription';
                        $messageText = "Hi $USER->firstname $USER->lastname, 
                            \n\nYour company  (<b><a href='$companyURL'>$company->name</a></b>) is now subscribed to our <b>$package->type $package->name</b> plan.
                            \n\nWhich will end in $end_date.
                            \n\nRegards \nSwiftLearn";        
            
            
                        email_to_user($USER, $fromUser, $subject, $messageText, $messageHtml, ", ", false);
                        
                        echo "<script>alert('Trial subscription activated! Your company is now under the 7 Day $package->type package, which will expire on $end_date.');location.href='$host';</script>";
                                    
                    }else{
                        
                        $trialSubscription = getCompanyTrialSubscription($DB, $companyId);
                        
                        // $companyHasTrialPackage = false;
                    
                        // $subscriptions = $DB->get_records('subscriptions', array('company_id'=>$myCompany->get('id')));
                        
                        // $trialPackage = $DB->get_record('packages', array('type' => 'Trial'));
                        
                        // foreach($subscriptions as $subscription){
                            
                        //     if(intVal($subscription->package_id) == intVal($trialPackage->id)){
                        //         $companyHasTrialPackage = true;
                        //     }
                        // }
                        
                        $packages = null;
                        
                        if(!empty($trialSubscription)){
                            
                            $packages = $DB->get_records_sql("SELECT * FROM {packages} WHERE  id <> $trialSubscription->package_id;");
   
                        }else{
                        
                            $packages = $DB->get_records('packages');
                            
                        }
                        
                        $array = json_decode(json_encode($packages), true);
                        $dataPackages = json_encode($array);
                        
                        // var_dump($packages);
                        // die();
                        
                        $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST'];
                        
                        echo "<script>localStorage.setItem('packages', '".$dataPackages."');localStorage.setItem('companyid', '".$myCompany->get('id')."');localStorage.setItem('userid', '".$USER->id."');</script>";
                        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>';
                        // echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                        
                        $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/custom-js/package_selection.js'));
                            
                    }
                    
                }else {
                    
                    $package = $DB->get_record('packages', array('id' => $companySubscription->package_id));
                    
                    if($package->type == 'Trial' && $companySubscription->status == 'Active'){
                        
                        $date = new DateTime($companySubscription->current_period_end);
                        $now = new DateTime();
                        if($date < $now) {
                            
                            $companySubscription->status = 'Completed';
                            
                            $company = $DB->get_record('company', array('id' => $companySubscription->company_id));
                            
                            $DB->update_record('subscriptions', $companySubscription);
                            
                            $host = $_SERVER['REQUEST_SCHEME']. '://' . $_SERVER['HTTP_HOST']. "/my";
                            $companyURL = $_SERVER['REQUEST_SCHEME']. '://' .$company->hostname;
                            $fromUser = 'Swift';
                            $subject = 'End of trial subscription';
                            $messageText = "Hi $USER->firstname $USER->lastname, 
                            \n\nYour subscription to our <b>$package->type $package->name</b> plan for your company <b>($company->name)</b> has ended.
                            \n\nRegards \nSwiftLearn";        
            
            
                            email_to_user($USER, $fromUser, $subject, $messageText, $messageHtml, ", ", false);
                            
                            echo "<script>alert('Trial subscription ended! Thank you for your time with us.');location.href='$host';</script>";
                            
                        }
                    }
                    
                    $format = "l M j, Y g:iA";
                    
                    $cancel_at = !empty($companySubscription->cancel_at) ? date($format, strtotime($companySubscription->cancel_at)) : 'none';
                    
                    $current_period_end = !empty($companySubscription->current_period_end) ? date($format, strtotime($companySubscription->current_period_end)) : 'none';
                    
                    $next_payment_attempt = !empty($companySubscription->next_payment_attempt) ? date($format, strtotime($companySubscription->next_payment_attempt)) : 'none';
                    
                    $attempt_count = !empty($companySubscription->attempt_count) ? $companySubscription->attempt_count : 0;
                    
                    $paused_at = !empty($companySubscription->paused_at) ? date($format, strtotime($companySubscription->paused_at)) : 'none';
                    
                    $latest_invoice = !empty($companySubscription->latest_invoice) ? $companySubscription->latest_invoice : 'none';
                    
                    $current_num_of_users = count($myCompany->get_all_user_ids());
                    
                    $is_trial = $package->type == 'Trial' ? 'yes' : 'no';
    
                    echo "<script>localStorage.setItem('latestinvoice', '".$latest_invoice."');localStorage.setItem('istrial', '".$is_trial."');localStorage.setItem('numofusers', '".$current_num_of_users." / ".$package->num_users."');localStorage.setItem('pausedat', '".$paused_at."');localStorage.setItem('attemptcount', '".$attempt_count."');localStorage.setItem('nextpaymentattempt', '".$next_payment_attempt."');localStorage.setItem('currentperiodend', '".$current_period_end."');localStorage.setItem('cancelat', '".$cancel_at."');localStorage.setItem('packagename', '".$package->name."');localStorage.setItem('packagetype', '".$package->type."');localStorage.setItem('customerid', '".$companySubscription->customer_id."');localStorage.setItem('subscriptionstatus', '".$companySubscription->status."');</script>";
                    
                    $PAGE->requires->js( new moodle_url($CFG->wwwroot . '/custom-js/manage_subscription.js'));
                    
                }
                
            }
                
        }
        
    }
    
    $userid = $USER->id;  // Owner of the page
    $context = context_user::instance($USER->id);
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
    $header = "$SITE->shortname: $strmymoodle";
    $pagetitle = $strmymoodle;
}

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page($userid, MY_PAGE_PRIVATE)) {
    print_error('mymoodlesetup');
}

// Start setting up the page
$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/my/index.php', $params);
$PAGE->set_pagelayout('mydashboard');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_subpage($currentpage->id);
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);

if (!isguestuser()) {   // Skip default home page for guests
    if (get_home_page() != HOMEPAGE_MY) {
        if (optional_param('setdefaulthome', false, PARAM_BOOL)) {
            set_user_preference('user_home_page_preference', HOMEPAGE_MY);
        } else if (!empty($CFG->defaulthomepage) && $CFG->defaulthomepage == HOMEPAGE_USER) {
            $frontpagenode = $PAGE->settingsnav->add(get_string('frontpagesettings'), null, navigation_node::TYPE_SETTING, null);
            $frontpagenode->force_open();
            $frontpagenode->add(get_string('makethismyhome'), new moodle_url('/my/', array('setdefaulthome' => true)),
                    navigation_node::TYPE_SETTING);
        }
    }
}

// Toggle the editing state and switches
if (empty($CFG->forcedefaultmymoodle) && $PAGE->user_allowed_editing()) {
    if ($reset !== null) {
        if (!is_null($userid)) {
            require_sesskey();
            if (!$currentpage = my_reset_page($userid, MY_PAGE_PRIVATE)) {
                print_error('reseterror', 'my');
            }
            redirect(new moodle_url('/my'));
        }
    } else if ($edit !== null) {             // Editing state was specified
        $USER->editing = $edit;       // Change editing state
    } else {                          // Editing state is in session
        if ($currentpage->userid) {   // It's a page we can edit, so load from session
            if (!empty($USER->editing)) {
                $edit = 1;
            } else {
                $edit = 0;
            }
        } else {
            // For the page to display properly with the user context header the page blocks need to
            // be copied over to the user context.
            if (!$currentpage = my_copy_page($USER->id, MY_PAGE_PRIVATE)) {
                print_error('mymoodlesetup');
            }
            $context = context_user::instance($USER->id);
            $PAGE->set_context($context);
            $PAGE->set_subpage($currentpage->id);
            // It's a system page and they are not allowed to edit system pages
            $USER->editing = $edit = 0;          // Disable editing completely, just to be safe
        }
    }

    // Add button for editing page
    $params = array('edit' => !$edit);

    $resetbutton = '';
    $resetstring = get_string('resetpage', 'my');
    $reseturl = new moodle_url("$CFG->wwwroot/my/index.php", array('edit' => 1, 'reset' => 1));

    if (!$currentpage->userid) {
        // viewing a system page -- let the user customise it
        $editstring = get_string('updatemymoodleon');
        $params['edit'] = 1;
    } else if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
    } else {
        $editstring = get_string('updatemymoodleoff');
        $resetbutton = $OUTPUT->single_button($reseturl, $resetstring);
    }

    $url = new moodle_url("$CFG->wwwroot/my/index.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($resetbutton . $button);

} else {
    $USER->editing = $edit = 0;
}

echo $OUTPUT->header();

if (core_userfeedback::should_display_reminder()) {
    core_userfeedback::print_reminder_block();
}

echo $OUTPUT->custom_block_region('content');

$PAGE->requires->js( new moodle_url($CFG->wwwroot . '/custom-js/swift_icons.js'));

echo $OUTPUT->footer();

// Trigger dashboard has been viewed event.
$eventparams = array('context' => $context);
$event = \core\event\dashboard_viewed::create($eventparams);
$event->trigger();
