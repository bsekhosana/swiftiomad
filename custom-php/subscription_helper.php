<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config.php';
global $DB;


function companyHasSubscription($companyId){
        
}

function getActiveCompanySubscription($companyId){
    // var_dump($db);
    // die();
    return $DB->get_record_sql("SELECT * FROM {subscriptions} WHERE status = 'Active' OR status = 'Pending Cancellation'  OR status = 'Overdue'  OR status = 'Paused' AND company_id = $companyId;");
        
}

function createCompanySubscription($db, $userid, $packageid, $companyid, $subscriptionid, $customerid, $currentperiodend, $status, $package){
    
    $subscription = new stdClass();
    $subscription->user_id =  intVal($userid);
    $subscription->package_id =  intVal($packageid);
    $subscription->company_id =  $companyid;
    $subscription->subscription_id = $subscriptionid;
    $subscription->customer_id = $customerid;
    $subscription->current_period_end = $currentperiodend;
    $subscription->status = $status;
    
    $lastInsertedId = $db->insert_record('subscriptions', $subscription,true);
    
    $company = $db->get_record('company', array('id'=>$companyid));
    
    $company->maxusers = $package->num_users;
    
    $db->update_record('company', $company);
    
    return $db->get_record('subscriptions',array('id'=>$lastInsertedId));
    
}

function getCompanyTrialSubscription($db, $companyid){
    $subscriptions = $db->get_records('subscriptions', array('company_id'=>$companyid));
    $trialPackage = $db->get_record('packages', array('type' => 'Trial'));
    foreach($subscriptions as $subscription){
        if(intVal($subscription->package_id) == intVal($trialPackage->id)){
            return $subscription;
        }
    }
    return null;
}






