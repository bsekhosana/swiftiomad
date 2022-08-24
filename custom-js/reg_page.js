// ADD INITIAL PROFILE SELECTION DROP-DOWN
var collapsableItem = document.getElementsByClassName('collapsible-actions');
if (collapsableItem != null && collapsableItem.length > 0){
    collapsableItem[0].insertAdjacentHTML('afterend', '<div id="fitem_id_type" class="form-group row  fitem   ">'+
                                                        '<div class="col-md-3 col-form-label d-flex pb-0 pr-md-0" id="yui_3_17_2_1_1661160570890_138">'+
                                                            '<label class="d-inline word-break " for="id_type" id="yui_3_17_2_1_1661160570890_137"> Profile Type</label>'+
                                                            '<div class="form-label-addon d-flex align-items-center align-self-start">'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-9 form-inline align-items-start felement" data-fieldtype="select" id="yui_3_17_2_1_1661160570890_139">'+
                                                        '<select class="custom-select" name="type" id="id_type">'+
                                                            '<option value="">Select Option</option>'+
                                                            '<option value="0">Individual</option>'+
                                                            '<option value="1">Organization</option>'+
                                                        '</select>'+
                                                        '<div class="form-control-feedback invalid-feedback" id="id_error_type">'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>');
}

function  showElement(ele) {
    ele.style.display = 'block';
    ele.removeAttribute('hidden');
}

function  hideElement(ele) {
    ele.style.display = 'none';
    ele.setAttribute('hidden','hidden');
}


//HIDE MORE & COMPANY DETAILS DIV
var type = document.getElementById('id_type');
var usernameDiv = document.getElementById('id_createuserandpass');
var moreDetailsDiv = document.getElementById('id_supplyinfo');
var companyDetailsDiv = document.getElementById('id_category_3');
var profileDiv = document.getElementById('id_category_2');
var fieldHostname = document.getElementById('id_profile_field_hostname');
var fieldType = document.getElementById('id_profile_field_type');
var dot = '.';
var domain = window.location.hostname;
var longFieldName = document.getElementById('id_profile_field_long_name');
var shortName = document.getElementById('id_profile_field_short_name')
var shortNameElement = document.getElementById('fitem_id_profile_field_short_name');
var newUrlName = document.querySelectorAll('[for="id_profile_field_short_name"]')[0];

//var typeValue = document.getElementById('id_type').value;

longFieldName.onkeyup = function(){
    shortName.value =""+longFieldName.value;
    //console.log(document.getElementById("id_profile_field_long_name").value);
};


hideElement(usernameDiv);
hideElement(moreDetailsDiv);
hideElement(profileDiv);
hideElement(companyDetailsDiv);
hideElement(shortName.parentElement.parentElement);
fieldHostname.setAttribute("style","max-width: 50%");

fieldHostname.insertAdjacentHTML('afterend', dot.concat(domain));

    type.addEventListener('change', function() {
        hideElement(shortName.parentElement.parentElement);
        if(type.selectedOptions[0].value == 0){
            fieldType.options[1].selected = true;
            fieldType.options[0].removeAttribute('selected');
            fieldType.options[2].removeAttribute('selected');
            fieldType.options[1].setAttribute('selected','selected');
            console.log(fieldType.selectedOptions[0].value);
            //console.log(type.selectedOptions[0].value);
            showElement(usernameDiv);
            showElement(moreDetailsDiv);
            hideElement(profileDiv);
            hideElement(companyDetailsDiv);
        }else if (type.selectedOptions[0].value == 1){
            fieldType.options[2].selected = true;
            fieldType.options[0].removeAttribute('selected');
            fieldType.options[1].removeAttribute('selected');
            fieldType.options[2].setAttribute('selected','selected');
            console.log(fieldType.selectedOptions[0].value);
            showElement(usernameDiv);
            showElement(moreDetailsDiv);
            showElement(profileDiv);
            showElement(companyDetailsDiv);
            console.log(shortNameElement);
            for (var i = shortNameElement.length - 1; i >= 0; i--){
                hideElement(shortNameElement[i]) ;
            }
        }
        
        fireEvent(fieldType,'change');
        
        
    });
    
    



function fireEvent(element,event){
    if (document.createEventObject){
    // dispatch for IE
    var evt = document.createEventObject();
    return element.fireEvent('on'+event,evt)
    }
    else{
    // dispatch for firefox + others
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent(event, true, true ); // event type,bubbling,cancelable
    return !element.dispatchEvent(evt);
    }
}
    
fieldType.addEventListener('change', function (e) {
    // alert('changed');
    shortNameElement.style.visibility='hidden';
    var hostnameElement = document.getElementById('fitem_id_profile_field_hostname');
    hostnameElement.style.marginTop = '-9.3%' ;
    walkText(document.body);
});

function walkText(node) {
  if (node.nodeType == 3) {
    node.data = node.data.replace(/Webserver hostname/g, "URL Name");
  }
  if (node.nodeType == 1 && node.nodeName != "SCRIPT") {
    for (var i = 0; i < node.childNodes.length; i++) {
      walkText(node.childNodes[i]);
    }
  }
}

    








