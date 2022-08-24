<?php

function updateDns($dns_type, $dns_name, $dns_data, $dns_domain){
        
    
        error_reporting(0);
        
        
        $domain 	= $_SERVER["SERVER_NAME"];
        $msg = '';
        
        	$dns_port 		= 10;
        	$dns_priority 	= 10;
        	$dns_protocol 	= 'string';
        	$dns_service 	= 'string';
        	$dns_ttl 		= 600;
        	$dns_weight 	= '10';
        
        	$dns_records = "[{\"data\": \"$dns_data\",\"port\": $dns_port,\"priority\": $dns_priority,\"protocol\": \"$dns_protocol\",\"service\": \"$dns_service\",\"ttl\": $dns_ttl,\"weight\": $dns_weight}]";
     
        	$url2 = "https://api.godaddy.com/v1/domains/$dns_domain/records/$dns_type/$dns_name";
        
        	$header = array(
        			'Authorization: sso-key '.GODADDY_API_KEY.':'.GODADDY_API_SECRET.'',
        			'Content-Type: application/json',
        			'Accept: application/json'
        	);
        
        	$ch = curl_init();
        	$timeout=60;
        
        	curl_setopt($ch, CURLOPT_URL, $url2);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);  
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE  
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $dns_records);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        	$result = curl_exec($ch);
        	curl_close($ch);
        		$result = json_decode($result, true);
        // 	if(!$result) {
        // 	    echo "<script>alert('Domain: ".$dns_name." created successfully');</script>";
        // 		return true;
        		
        // 	} else {
        // 	    echo "<script>alert('Domain: ".$dns_name." failed. Reason: ".$result."');</script>";
        // 		return false;
        // 	}
        	
}

function deleteDnsRecord($dns_type, $dns_name, $dns_data, $dns_domain){

        	$url2 = "https://api.godaddy.com/v1/domains/$dns_domain/records/$dns_type/$dns_name";
        
        	$header = array(
        			'Authorization: sso-key '.GODADDY_API_KEY.':'.GODADDY_API_SECRET.'',
        			'Content-Type: application/json',
        			'Accept: application/json'
        	);
        
        	$ch = curl_init();
        	$timeout=60;
        
        	curl_setopt($ch, CURLOPT_URL, $url2);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);  
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE 
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        	$result = curl_exec($ch);
        	curl_close($ch);

}    

?>