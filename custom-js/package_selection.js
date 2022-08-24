document.getElementsByTagName("head")[0].insertAdjacentHTML("beforeend", '<meta name="viewport" content="width=device-width, initial-scale=1" />');
document.getElementsByTagName("head")[0].insertAdjacentHTML("beforeend", '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"/>');


var modalHtml =  window.location.protocol + '//' + window.location.host +  '/my/package_modal.html';

document.getElementById('region-main-box').insertAdjacentHTML("beforeend", '<button id="modalActivate" type="button" hidden class="btn btn-danger" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#exampleModalPreview">Launch demo modal</button>'+
                                                    '<div class="modal fade right" id="exampleModalPreview" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true"><div class="modal-dialog-full-width modal-dialog momodel modal-fluid " role="document"><div style="margin-left: -18%;left: -80%;" class="modal-content-full-width modal-content ">'+
                                                        '<iframe src="'+modalHtml+'" style="height:900px;width:300%;" title="Iframe Example"></iframe></div></div></div>');
                                                

setTimeout(function () {
        
    let myModal = new bootstrap.Modal(document.getElementById('exampleModalPreview'), {backdrop: 'static', keyboard: false});
       
    myModal.show();
        
}, 1000);
