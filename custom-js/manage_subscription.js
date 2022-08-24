var companySection = document.getElementById('inst1');
var allDivs = companySection.getElementsByClassName('alert');
var companyIsDiv = null ;

for(var i=0;i<allDivs.length;i++){

    if(kmpSearch('Your company is', allDivs[i].innerHTML) != -1){
        
        companyIsDiv = allDivs[i];
        var packageType = localStorage.getItem("packagetype");
        var packageName = localStorage.getItem("packagename");
        
        companyIsDiv.insertAdjacentHTML("afterend",'&nbsp;<div id="package-info" class="alert alert-primary">' +packageType+ ' ' +packageName+ '</div>');
        
        break;
    
    }

}




var customerId = localStorage.getItem("customerid");
var subscriptionStatus = localStorage.getItem("subscriptionstatus");
var isTrial = localStorage.getItem("istrial");

var statusHtml = '';

var packageInfo = document.getElementById('package-info');
var invoiceLink = localStorage.getItem("latestinvoice");
if(subscriptionStatus == 'Active'){
    
    statusHtml = '<p style="color:green !important">Active</p>';
    var currentPeriodEnd = localStorage.getItem("currentperiodend");
    
    if(isTrial == 'yes'){
        
        packageInfo.insertAdjacentHTML("afterend",'&nbsp;<div class="alert alert-warning">Trial ending on ' +currentPeriodEnd+ '</div>');
        
    }else{
        
        packageInfo.insertAdjacentHTML("afterend",'&nbsp;<div class="alert alert-warning">Next payment scheduled for ' +currentPeriodEnd+ '</div>');
        
    }
    
}else if(subscriptionStatus == 'Overdue'){
    
    statusHtml  = '<p style="color:orange !important">Overdue</p>';
    var nextPaymentAttempt = localStorage.getItem("nextpaymentattempt");
    var attemptCount = parseInt(localStorage.getItem("attemptcount"));
    var attempString = '';
    switch (attemptCount){
        case 1:
            attempString = '1st';
            break;
            
        case 2:
            attempString = '2nd';
            break;
        
        case 3:
            attempString = '3rd and final';
            break;
    }
    
    packageInfo.insertAdjacentHTML("afterend",'&nbsp;<div class="alert alert-warning"><b>' +attempString+ '</b> payment failed, next payment attempt scheduled for ' +nextPaymentAttempt+ '</div>'+
                                    '<div class="alert alert-danger">Alternatively, <a href="'+invoiceLink+'">Click here</a> to pay now.</div>');
    console.log('attempt count ' + attemptCount);
    
}else if(subscriptionStatus == 'Paused'){
    
    statusHtml  = '<p style="color:red !important">Paused</p>';
    var pausedAt = localStorage.getItem("pausedat");
    packageInfo.insertAdjacentHTML("afterend",'&nbsp;<div class="alert alert-danger">Subscription has been paused since ' +pausedAt+ '.</div>'+
                                    '&nbsp;<div class="alert alert-danger">To pay now, <a href="'+invoiceLink+'">Click here</a>.</div>');
    
    
}else if(subscriptionStatus == 'Pending Cancellation'){
    
    statusHtml  = '<p style="color:#691911 !important">Pending Cancellation</p>';
    var cancelAt = localStorage.getItem("cancelat");
    packageInfo.insertAdjacentHTML("afterend",'&nbsp;<div class="alert alert-danger">Subscription scheduled to be cancelled on ' +cancelAt+ '</div>');
    
}

var numOfUsers = localStorage.getItem("numofusers");
packageInfo.parentElement.insertAdjacentHTML("beforeEnd",'&nbsp;<div class="alert alert-danger">Number of users ' +numOfUsers+ '</div>');

document.getElementById('CompanyAdmin').insertAdjacentHTML("afterbegin", '<a href="javascript:;" onclick="open_billing();">'+
                                                                        	    '<div class="iomadlink mr-2">'+
                                                                        	        '<div class="iomadicon company">'+
                                                                        	       // '<link sizes="96x96" rel="icon" type="image/png" href="https://swift1.co.uk/custom-icons/Manage%20subscriptions.png" />'+
                                                                        	        	'<div class="icon-swift-manage-subscriptions"> </div>'+
                                                                        	        	//'<span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span><span class="path11"></span><span class="path12"></span><span class="path13"></span><span class="path14"></span><span class="path15"></span><span class="path16"></span><span class="path17"></span><span class="path18"></span><span class="path19"></span><span class="path20"></span><span class="path21"></span><span class="path22"></span><span class="path23"></span><span class="path24"></span><span class="path25"></span><span class="path26"></span><span class="path27"></span><span class="path28"></span><span class="path29"></span></div>'+
                                                                        	           // '<div class="fa fa-action fa-edit"></div>'+
                                                                        	        '</div>'+
                                                                        	        '<div class="actiondescription">Manage billing</div>'+
                                                                        	       statusHtml+
                                                                        	    '</div>'+
                                                                        	'</a>'); 
                                                                        	
function open_billing(e) {
    var form = document.createElement("form");
    var element1 = document.createElement("input"); 
    
    form.style.display = "none";
    
    form.method = "POST";
    form.action = window.location.origin+'/custom-php/stripe_customer_portal.php';

    element1.value=customerId;
    element1.name="customer_id";
    form.appendChild(element1);  


    document.body.appendChild(form);

    form.submit();
}

function kmpSearch(pattern, text) {
  if (pattern.length == 0)
    return 0; // Immediate match

  // Compute longest suffix-prefix table
  var lsp = [0]; // Base case
  for (var i = 1; i < pattern.length; i++) {
    var j = lsp[i - 1]; // Start by assuming we're extending the previous LSP
    while (j > 0 && pattern[i] !== pattern[j])
      j = lsp[j - 1];
    if (pattern[i] === pattern[j])
      j++;
    lsp.push(j);
  }

  // Walk through text string
  var j = 0; // Number of chars matched in pattern
  for (var i = 0; i < text.length; i++) {
    while (j > 0 && text[i] != pattern[j])
      j = lsp[j - 1]; // Fall back in the pattern
    if (text[i]  == pattern[j]) {
      j++; // Next char matched, increment position
      if (j == pattern.length)
        return i - (j - 1);
    }
  }
  return -1; // Not found
}
