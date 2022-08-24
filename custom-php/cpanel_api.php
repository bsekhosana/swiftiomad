<?php

function createSubDomain($subdomain,$domain,$dir,$cpanel_ip) {

    $whmusername = "swift1";
    $whmpassword = ".~TA8?K)t'*zj=$+";
    
    $query = "https://".$cpanel_ip.":2083/json-api/cpanel?cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_func=addsubdomain&cpanel_jsonapi_apiversion=2&dir=/public_html/&rootdomain=".$domain."&domain=".$subdomain."&canoff=0";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    $header[0] = "Authorization: Basic " . base64_encode($whmusername.":".$whmpassword) . "\n\r";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $query);
    $result = curl_exec($curl);
    if ($result == false) {
    //echo "curl_exec threw error \"" . curl_error($curl) . "\" for $query";   
                                                    // log error if curl exec fails
    }
    curl_close($curl);
    
    
    }
    
function deleteSubDomain($subdomain,$domain,$dir,$cpanel_ip) {

    $whmusername = "swift1";
    $whmpassword = ".~TA8?K)t'*zj=$+";
    
    $query = "https://".$cpanel_ip.":2083/json-api/cpanel?cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_func=delsubdomain&cpanel_jsonapi_apiversion=2&dir=/public_html/&rootdomain=".$domain."&domain=".$subdomain."";
    
   // $deletesub =  "https://$domain:2083/json-api/cpanel?cpanel_jsonapi_func=delsubdomain&cpanel_jsonapi_module=SubDomain&cpanel_jsonapi_version=2&domain=".$subdomain.'.'.$domain."&dir=$directory";  //Note: To delete the subdomain of an addon domain, separate the subdomain with an underscore (_) instead of a dot (.). For example, use the following format: subdomain_addondomain.tld
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HEADER,0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    $header[0] = "Authorization: Basic " . base64_encode($whmusername.":".$whmpassword) . "\n\r";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $query);
    $result = curl_exec($curl);
    if ($result == false) {
    //echo "curl_exec threw error \"" . curl_error($curl) . "\" for $query";   
                                                    // log error if curl exec fails
    }
    curl_close($curl);
    
    
    }
    

?>