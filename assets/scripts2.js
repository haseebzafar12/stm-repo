$(document).ready(function() {
  $(document).on('click', '.channelSubmit', function(){
    
    var masterID = $('.DNlist').val();
    
    var amzoschecked = $("input[name='AmzOs']:checked").val();
    var amzqcchecked = $("input[name='AmzQc']:checked").val();
    var ebayoschecked = $("input[name='EbayOs']:checked").val();
    var ebayaochecked = $("input[name='EbayAo']:checked").val();
    
    var post_m = "channelsubmit";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{masterID:masterID,amzoschecked:amzoschecked,amzqcchecked:amzqcchecked,ebayoschecked:ebayoschecked,ebayaochecked:ebayaochecked,post_m:post_m},
        success:function(data){
          location.reload(true);
        }
    });
    
  });  
  $(document).on('change', '.stockStatus', function(){
    var stockStatus = $(this).val();
    var masterID = $(this).attr('data-id');
    
    if(stockStatus == 'discontinue'){
      var post_m  = "discontinue";
      var msg = 'Are you sure you want to discontinue?';
      var endMsg = 'Item Discontinued!';
    }else if(stockStatus == 'continue'){
      var post_m  = "continue";
      var msg = 'Are you sure you want to continue?';
      var endMsg = 'Item Continued!';
    }

    if(stockStatus != "DNlist"){
      swal({
        title: msg,
        type: 'warning',
        confirmButtonText: 'Yes',
        showCancelButton: true,
        padding: '2em'
      }).then(function(result) {
        if (result.value) {
          $.ajax({
              method: "post",
              url:"common/ajax/ajax.php",
              dataType: 'text',
              data:{masterID:masterID,post_m:post_m},
              success:function(data){
                console.log(data);
                swal({
                  title: endMsg,
                  padding: '2em',
                  type: 'success'
                }).then(function (result) {
                  location.reload(true);
                })
              }
          });          
        }
      })
    }
            
    
  });   
});  

$(document).ready(function() {
  $(document).on('change', '.stockStatus', function(){
    var stockStatus = $(this).val();
    var masterID = $(this).attr('data-id');
    var post_m = "DNlist";
    if(stockStatus == 'DNlist'){
      $("#DNlistModal").modal('show');
        $.ajax({
            method: "post",
            url:"common/ajax/ajax.php",
            dataType: 'text',
            data:{masterID:masterID,post_m:post_m},
            success:function(data){
              $("#channelBody").html(data);
            }
        });   
    }
  });   
}); 

$(document).ready(function() {

  $(document).on('click', '.lw_sku', function(){
    $("#channellistingModal").modal();
    var lwsku = $(this).attr('data-id');
    var post_m = "load_lwsku_detail";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m,lwsku:lwsku},
        
        success:function(data){
          $('#listingBody').html(data);
        }
    });
  });
  $(document).on('click', '.lw_sku_inv', function(){
    $("#salesDetail").modal();
    var lwsku = $(this).attr('data-id');
    var post_m = "lwsku_detail";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m,lwsku:lwsku},
        
        success:function(data){
          $('#listingBody').html(data);
        }
    });
  });    

});  
$(document).ready(function() {
  
  flatpickr(document.getElementById('trendDate'));
  

  // fetch_csv();
  fetch_users();
  setInterval(function(){
    fetch_users();
    notification();
    update_last_activity();
    update_user_chat_history();
  },5000);



  $(document).on('click', '#importFbaOs', function(){
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','block');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','none');
  });
  $(document).on('click', '#importFbaQc', function(){
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','block');
    $("#upload_excel").css('display','none');
  });
  $(document).on('click', '#importBtn', function(e){  
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','block');
  });

  $(document).on('click', '#importAmzOS', function(){
    $("#importamzos_sale").css('display','block');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','none');
  });

  $(document).on('click', '#importAmzQC', function(){
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','block');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','none');
  });

  $(document).on('click', '#importEbayOS', function(){
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','block');
    $("#importebayac_sale").css('display','none');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','none');
  });
  $(document).on('click', '#importEbayAC', function(){
    $("#importamzos_sale").css('display','none');
    $("#importamzqc_sale").css('display','none');
    $("#importebayos_sale").css('display','none');
    $("#importebayac_sale").css('display','block');
    $("#importFBAOs").css('display','none');
    $("#importFBAQc").css('display','none');
    $("#upload_excel").css('display','none');
  });
  
  function fetch_csv(){
    var post_m = "fetch_csv";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m},
        beforeSend: function(){
          $('#imageloading').css("display", "block");
        },
        success:function(data){
          $('#fetchCSV').html(data);
        },complete: function () {
          $('#imageloading').css("display", "none");
        }
    });  
  }
  $(document).on('click', '.uploadCSV', function(){

    var file = document.getElementById("UploadCSV").files[0];
    var csvDate = document.getElementById("trendDate").value;

    if(csvDate != ""){
      var form_data = new FormData();

    var files = $('input[type=file]').val().replace(/C:\\fakepath\\/i, '')
    
    form_data.append("fileName",file);
    form_data.append("FBMFlatpickr",csvDate);

    var post_m = "insertCSV";
      $.ajax({
        url:"common/ajax/uploadCSV.php",
        type: "post",
        contentType: false,
        cache: false,
        processData:false,
        data: form_data,
        beforeSend: function(){

          var html = "";
          
          html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h5 style="margin-left:10%;">Processing...</h5></div>';
          if(csvDate != "" && file != ""){
            $("#fetchCSV").html(html);  
          }
          
        },
        success:function(data){
          
          $("#trendDate").val(csvDate);
          $("#UploadCSV").val('');
          $('#upload_excel').css('display','none');

          $("#importBtn").attr('disabled', true);

          $(".tickmark_fbm").append("<img src='images/tickmark2.png' height='35' width='35'>");

        },complete: function () {
          $('#imageloading').css("display", "none");
        }
    });  
    }else{
      alert("Date is required");
    }
    

  });
  $(document).on('click', '.importFbaInvOs', function(){ 
    
    var file = document.getElementById("importFbaInvOs").files[0];
    var csvDate = document.getElementById("trendDate").value;

    if(csvDate != ""){
      var form_data = new FormData();
    
      form_data.append("fileName",file);
      form_data.append("FBA_amz_os",csvDate);

      $.ajax({
        url:"common/ajax/fba_inv_os.php",
        type: "post",
        contentType: false,
        cache: false,
        processData:false,
        data: form_data,
        beforeSend: function(){
          var html = "";
          
          html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h5 style="margin-left:10%;">Uploading FBA Inventory...</h5></div>';

          $("#fetchCSV").html(html);
        },
        success:function(data){

          
          $('#imageloading').css("display", "none");
          $(".importFbaInvOs").val('');
          $("#importFBAOs").css('display','none');
          $("#trendDate").val(csvDate);
          $("#importFbaOs").attr('disabled', true);

          $(".tickmark_fbaos").prepend("<img src='images/tickmark2.png' height='35' width='35'>");

        },complete: function () {
          $('#imageloading').css("display", "none");
          
        }
    });
    }else{
      alert("Date is required");
    }
    
  });

  $(document).on('click', '.importFbaInvQc', function(){ 
    
    var file = document.getElementById("importFbaInvQc").files[0];
    var csvDate = document.getElementById("trendDate").value;
    if(csvDate != ""){

      var form_data = new FormData();
      form_data.append("fileName",file);
      form_data.append("FBA_amz_qc",csvDate);
      $.ajax({
        url:"common/ajax/fba_inv_qc.php",
        type: "post",
        contentType: false,
        cache: false,
        processData:false,
        data: form_data,
        beforeSend: function(){
          var html = "";
          
          html += '<div id="imageloading" style="margin-left:34%; margin-top:1%;"><img src="images/loading-icon.gif" height="150" width="150"><br><h5 style="margin-left:13%;">Uploading FBA Inventory...</h5></div>';

          $("#fetchCSV").html(html);
        },
        success:function(data){

          
          $("#trendDate").val(csvDate);
          $('#imageloading').css("display", "none");
          $(".importFbaInvQc").val('');
          $("#importFBAQc").css('display','none');
          $("#importFbaQc").attr('disabled', true);

          $(".tickmark_fbaqc").append("<img src='images/tickmark2.png' height='35' width='35'>");
          
        },complete: function () {
          $('#imageloading').css("display", "none");
          
        }
    });

    }else{
      alert("Date is required");
    }  
    
  });
  
  $(document).on('click', '#showData', function(){ 
    fetch_csv();
  }); 
  
  $(document).on('click', '.import_amz_os', function(){
  
    var file = document.getElementById("AmzOSFile").files[0];
    var csvDate = document.getElementById("trendDate").value;
    if(csvDate != ""){

      var form_data = new FormData();
      form_data.append("fileName",file);
      form_data.append("AmzOsFlatpickr",csvDate);
      $.ajax({
        url:"common/ajax/amz_os_sale.php",
        type: "post",
        contentType: false,
        cache: false,
        processData:false,
        data: form_data,
        beforeSend: function(){
          var html = "";
          
          html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h6 style="margin-left:13%;">Processing...</h6></div>';

          $("#fetchCSV").html(html);
          
        },
        success:function(data){
          $("#trendDate").val(csvDate);
          $("#importamzos_sale").css('display','none');
          $("#importAmzOS").attr('disabled', true);

          $(".tickmark_amzos").append("<img src='images/tickmark2.png' height='35' width='35'>");
                
        },complete: function () {
          $('#imageloading').css("display", "none");
          // fetch_csv();
        }
    });
    
    }else{
      alert("Date is required");
    }
    
  });
  $(document).on('click', '.import_amz_qc', function(){
  
    var file = document.getElementById("AmzQCFile").files[0];
    var csvDate = document.getElementById("trendDate").value;
    if(csvDate != ""){
      var form_data = new FormData(); 
      form_data.append("fileName",file);
      form_data.append("AmzQcFlatpickr",csvDate);
      
      $.ajax({
          url:"common/ajax/amz_qc_sale.php",
          type: "post",
          contentType: false,
          cache: false,
          processData:false,
          data: form_data,
          beforeSend: function(){
            var html = "";
            
            html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h6 style="margin-left:13%;">Processing...</h6></div>';

            $("#fetchCSV").html(html);
            
          },
          success:function(data){
            $("#trendDate").val(csvDate);
            $("#importamzqc_sale").css('display','none');
            $("#importAmzQC").attr('disabled', true);

            $(".tickmark_amzqc").append("<img src='images/tickmark2.png' height='35' width='35'>");
                  
          },complete: function () {
            $('#imageloading').css("display", "none");
            // fetch_csv();
          }
      });
    }else{
      alert("Date is required");
    }

  });

  $(document).on('click', '.import_ebay_os', function(){
  
    var file = document.getElementById("EbayOSFile").files[0];
    var csvDate = document.getElementById("trendDate").value;

    if(csvDate != ""){
      var form_data = new FormData();
      form_data.append("fileName",file);
      form_data.append("EbayOsFlatpickr",csvDate);

      $.ajax({
          url:"common/ajax/ebay_os_sale.php",
          type: "post",
          contentType: false,
          cache: false,
          processData:false,
          data: form_data,
          beforeSend: function(){
            var html = "";
            
            html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h6 style="margin-left:13%;">Processing...</h6></div>';

            $("#fetchCSV").html(html);
            
          },
          success:function(data){
            $("#trendDate").val(csvDate);
            $("#importebayos_sale").css('display','none');
            $("#importEbayOS").attr('disabled', true);

            $(".tickmark_ebayos").append("<img src='images/tickmark2.png' height='35' width='35'>");
            
          },complete: function () {
            $('#imageloading').css("display", "none");
            // fetch_csv();
          }
      });
    }else{
      alert("Date is required");
    }
    
  });
  $(document).on('click', '.import_ebay_ac', function(){
  
    var file = document.getElementById("EbayACFile").files[0];
    var csvDate = document.getElementById("trendDate").value;

    if(csvDate != ""){
      var form_data = new FormData();
      form_data.append("fileName",file);
      form_data.append("EbayAoFlatpickr",csvDate);
      $.ajax({
          url:"common/ajax/ebay_ac_sale.php",
          type: "post",
          contentType: false,
          cache: false,
          processData:false,
          data: form_data,
          beforeSend: function(){
            var html = "";
            
            html += '<div id="imageloading" style="margin-left:34%;"><img src="images/loading-icon.gif" height="100" width="100"><br><h6 style="margin-left:13%;">Processing...</h6></div>';

            $("#fetchCSV").html(html);
            
          },
          success:function(data){
            $("#trendDate").val(csvDate);
            $("#importebayac_sale").css('display','none');
            $("#importEbayAC").attr('disabled', true);

            $(".tickmark_ebayao").append("<img src='images/tickmark2.png' height='35' width='35'>");

            $("#alertBox").css('display','block');

          },complete: function () {
            $('#imageloading').css("display", "none");
            // fetch_csv();
          }
      });
    }else{
      alert("Date is required");
    }

    
  }); 
  
  function fetch_users(){
    var post_m = "loadContent";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m},
        success:function(data){
          $('.people').html(data);
        }
    });  
  }
  function notification(){
    var post_m = "notification";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m},
        success:function(data){
          if(data > 0){
            // console.log("data -> "+data);
            $('span#bage_notification').show();
            $('span#bage_notification').html(data);
          }else{
            $('span#bage_notification').hide();
          }
          
        }
    });  
  }
  function update_last_activity(){
    var post_m = "update_last_activity";
    $.ajax({
        method: "post",
        url:"common/ajax/ajax.php",
        dataType: 'text',
        data:{post_m:post_m},
        success:function(data){
          console.log(data);
        }
    });
  }

  function make_chat_box(to_user_id,to_user_name,to_image){

    var box_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You are chatting with '+to_user_name+'">';
    var img = "";
    if(to_image != ""){
        img += '<img src="images/'+to_image+'" alt="avatar" style="height:40px; width:40px; border-radius:10px;">';
    }else{
        img += '<img src="assets/img/90x90.jpg" alt="avatar" style="height:40px; width:40px; border-radius:10px;">';
    }
    box_content += '<div class="headingOFChat"><div class="current-chat-user-name"><span>'+img+'<span class="name">'+to_user_name+'</span></span></div></div>';

    box_content += '<div class="chat_box" style="height:400px; border:2px solid #ccc;" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
    box_content += fetch_user_chat_history(to_user_id);
    box_content += '</div>';
    box_content += '<div class="chat-footer chat-active">';
        box_content += '<div class="chat-input">';
          box_content += '<div class="form-control chatMessage" contenteditable data-touserid="'+to_user_id+'" id="chat_message_'+to_user_id+'" placeholder="Type a message"></div>';
          box_content += '<div class="image_upload">';
            
              box_content += '<label for="uploadFile_'+to_user_id+'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg></label>';
              box_content += '<input type="file" name="upload_file" class="upload_file" id="uploadFile_'+to_user_id+'" data-id="'+to_user_id+'">';

          box_content += '</div>';
          box_content += '<div class="imageContainer_'+to_user_id+'"><img id="loadingImage_'+to_user_id+'" src="images/loading-icon.gif" height="30" width="30" style="float:left; margin-left:110px; display:none;"></div>';

          box_content += '<button type="button" class="btn btn-info send_message" data-touserid="'+to_user_id+'" id="chat_message_'+to_user_id+'">Send</button>'; 
          // box_content += '<input type="text" class="mail-write-box form-control chatMessage" data-touserid="'+to_user_id+'" id="chat_message_'+to_user_id+'" placeholder="Type a message"/>';
           
       box_content += '</div>';
    box_content += '</div>';

    box_content += '</div>';

    $('#user-modal').html(box_content);
    

  }
  

  
  $(document).on('click', '.uploadCSV', function(e){
      
    $("#uploadEX").css('display','block');
    
  });
  $(document).on('focus', '.chatMessage', function(e){
    var isType = "1";
    var post_m = "focus_message";
    $.ajax({
          url: 'common/ajax/ajax.php',
          type: 'post',
          data: {isType:isType,post_m:post_m},
          success:function(data){
            console.log(data+'Focus');
          }
    });
  });
  $(document).on('blur', '.chatMessage', function(e){
    var isType = "0";
    var post_m = "focus_message";
    $.ajax({
          url: 'common/ajax/ajax.php',
          type: 'post',
          data: {isType:isType,post_m:post_m},
          success:function(data){
            console.log(data+'Blur');
          }
    });
  });
  
  $(document).on('click', '.delImage', function(){
    var file = $(this).attr('data-id');
    var post_m = "delImage";
    $.ajax({
          url: 'common/ajax/ajax.php',
          type: 'post',
          data: {file:file,post_m:post_m},
          success:function(data){
            $('.image_preview').hide();
            $('.image_file').val('');
          }
    });    
  });
  $(document).on('click', '.attachement', function(){
    var file = $(this).attr('data-file');
    window.location = "common/ajax/down.php?file="+file;
    
  });  
  $(document).on('change', '.upload_file', function(){

    var to_user_id = $(this).attr('data-id');
    var file = document.getElementById("uploadFile_"+to_user_id).files[0];
    
    var form_data = new FormData();
    form_data.append("file_name",file);
    
    $.ajax({
          url: 'common/ajax/upload.php',
          type: 'post',
          contentType: false,
          cache: false,
          processData:false,
          data: form_data,
          beforeSend: function(){
            $('#loadingImage_'+to_user_id).css("display", "block");
          },
          success:function(data){
            data = $.parseJSON(data);
            if(data['ext'] == 'jpg' || data['ext'] == 'jpeg' || data['ext'] == 'png' || data['ext'] == 'gif'){
              $(".imageContainer_"+to_user_id).html('<div class="image_preview"><img src="upload/'+data['fileName']+'" height="100" width="100"/><a class="delImage" data-id="'+data['fileName']+'" style="color:#332d2f; position:absolute; top:394pt; left:112px;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a><input type="hidden" class="image_file_'+to_user_id+'" value="'+data['fileName']+'"></div>');  
            }else{
              $(".imageContainer_"+to_user_id).html('<div class="preview"><div class="filePreview">'+data['fileName']+'</div><input type="hidden" class="image_file_'+to_user_id+'" value="'+data['fileName']+'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></div>');
            }
            
          },complete: function () {
            $('#loadingImage_'+to_user_id).css("display", "none");
          }
    });
    
  });
  $(document).on('click', '.send_message', function(){
    
     var to_user_id =  $(this).attr('data-touserid');
     var chat_message = $("#chat_message_"+to_user_id).html();
     var file = $('.image_file_'+to_user_id).val();
     var post_m = "send-message";
       $.ajax({
          url: 'common/ajax/ajax.php',
          type: 'post',
          cache: false,
          data: {file:file,to_user_id:to_user_id,chat_message:chat_message,post_m:post_m},
          beforeSend: function(){
            $('#loadingImage_'+to_user_id).css("display", "block");
          },
          success:function(data){
            $("#chat_message_"+to_user_id).html('');
            $('#chat_history_'+to_user_id).html(data);
            $('.image_preview').hide();
            
            $('.preview').hide();
            var file = $('.image_file_'+to_user_id).val('');
             var objDiv = $('#chat_history_'+to_user_id);
             var h = objDiv.get(0).scrollHeight;
             objDiv.animate({scrollTop: h});
          },complete: function () {
            $('#loadingImage_'+to_user_id).css("display", "none");
          }
      });
    
  });
 function fetch_user_chat_history(to_user_id)
 {
  var post_m = "loadChat";
  $.ajax({
   url:"common/ajax/ajax.php",
   method:"POST",
   data:{to_user_id:to_user_id,post_m:post_m},
   success:function(data){
    
    $('#chat_history_'+to_user_id).html(data);
    
    var objDiv = $('#chat_history_'+to_user_id);
    var h = objDiv.get(0).scrollHeight;
    objDiv.animate({scrollTop: h});

   }
  });
 }
  $(document).on('click', '.person', function(){

        var to_user_id = $(this).attr('data-touserid');
        var to_user_name = $(this).attr('data-username');
        var to_image = $(this).attr('data-image');
        
        make_chat_box(to_user_id,to_user_name,to_image);
        
        $('#user_dialog_'+to_user_id).dialog({
          autoOpen:false,
          width:400
        });

        $('#user_dialog_'+to_user_id+'').dialog('open');

      
  });
  function update_user_chat_history(){
    $('.chat_box').each(function(){
      var to_user_id = $(this).attr('data-touserid');
      fetch_user_chat_history(to_user_id);

    });
    
  }
  
});
$(document).ready(function() {
    // var assigneeId = $(this).attr('data-id');
    // var tr_assignee = $('.assigne_data_'+assigneeId+'');
    // tr_assignee.clone().insertAfter(tr_assignee);
    $(document).on('click', '.copyAssignee', function(){
       var assigneeId = $(this).attr('data-id');
       var post_m = "copyAssignee";
        swal({
          title: 'Are you sure you want to copy?',
          type: 'warning',
          confirmButtonText: 'Copy',
          showCancelButton: true,
          padding: '2em'
        }).then(function(result) {
          if (result.value) {
            $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{assigneeId:assigneeId,post_m:post_m},
                success:function(data){
                  swal({
                    title: 'ROW COPIED!',
                    padding: '2em',
                    type: 'success'
                  }).then(function (result) {
                    location.reload(true);
                  })
                }
            });          
          }
        })
         
    });
});  

$(document).ready(function() {
  $(document).on('click', '#suppsBodyIcon', function(){
  
    $('.supContent').fadeToggle(1000); 
    $('#headingTR').fadeToggle(1000);
    $('.supCon').fadeToggle(1000);
  });
});
$(document).ready(function() {
  $(document).on('click', '#catsBodyIcon', function(){ 
    $('.contentHeading').fadeToggle(1000); 
    $('.content').fadeToggle(1000);
  });
});
$(document).ready(function() {
  $(document).on('click', '#menu-ex', function(){ 
    $('#empTBL').fadeToggle(1000); 
    $('#HeadEMp').fadeToggle(1000);
    $('#HeadEM').fadeToggle(1000);
  });
});
$(document).ready(function() {
  $(document).on('click', '.filterSupp', function(){
    var suppPost = $(".suppPost").val();
    var categoryPost = $(".categoryPost").val();
    var fromdateP = $("#fromdateP").val();
    var todateP = $("#todateP").val();
    var post_m = "filterSupp";
    $.ajax({
        url: 'common/ajax/ajax_data.php',
        type: 'post',
        data: {suppPost:suppPost,categoryPost:categoryPost,fromdateP:fromdateP,todateP:todateP,post_m:post_m},
        success:function(response){
          $('.post').remove();
          $('#pagination').remove();
          $(".tbodyContent").html(response);
        }
    
    });    
  });    
});
$(document).ready(function() {
  $(document).on('click', '.filterEmp', function(){
    
    var catEmp = $(".catEmp").val();
    var subtaskEmp = $(".subtaskEmp").val();
    var fDate = $("#fDate").val();
    var tDate = $("#tDate").val();
    var post_m = "filterEmp";

    $.ajax({
        url: 'common/ajax/ajax.php',
        type: 'post',
        data: {catEmp:catEmp,subtaskEmp:subtaskEmp,fDate:fDate,tDate:tDate,post_m:post_m},
        success:function(response){
          console.log(response);
          //$('.content').remove();
          $("#empTBL").html(response);
        }
    });

  });    
});  
$(document).ready(function() {
  $(document).on('click', '.filterCats', function(){
    var supplierPost = $(".supplier").val();
    var catPost = $(".category").val();
    var fromPost = $("#fromFlatpickr").val();
    var toPost = $("#toFlatpickr").val();
    var post_m = "filterCats";
    $.ajax({
        url: 'common/ajax/ajax_data.php',
        type: 'post',
        data: {supplierPost:supplierPost,catPost:catPost,fromPost:fromPost,toPost:toPost,post_m:post_m},
        success:function(data){
          $('.content').remove();
          // // $('#pagination').remove();
          
          $(".catshead").after(data);
        }
    
    });    
  });    
});  
$(document).ready(function() {
  // Load more data
  $(document).on('click', '.load-more', function(){
        var row = Number($('#row').val());
        var allcount = Number($('#all').val());
        
        var supplierPost = $("#supplierPost").val();
        var catPost = $("#catPost").val();
        var fromDate = $("#fromDate").val();
        var toDate = $("#toDate").val();
        // var submit = "post":
        var rowperpage = 17;
        row = row + rowperpage;
        if(row <= allcount){
            $("#row").val(row);
            var post_m = "showmore";
            $.ajax({
                url: 'common/ajax/ajax_data.php',
                type: 'post',
                data: {supplierPost:supplierPost,catPost:catPost,fromDate:fromDate,toDate:toDate,row:row,post_m:post_m},
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                },
                success: function(response){

                    // Setting little delay while displaying new content
                    setTimeout(function() {
                        // appending posts after last post with class="post"
                        $(".post:last").after(response).show().fadeIn("slow");
                        // $("#cattable").after('<div class="test"></div>');
                        var rowno = row + rowperpage;

                        // checking row value is greater than allcount or not
                        if(rowno > allcount){

                            // Change the text and background
                            $('.load-more').text("Hide");
                        }else{
                            $(".load-more").text("Load more");
                        }
                    }, 2000);

                }
            });
        }else{
            $('.load-more').text("Loading...");

            // Setting little delay while removing contents
            setTimeout(function() {

                // When row is greater than allcount then remove all class='post' element after 3 element
                var postback = $('.post:nth-child(17)').nextAll('.post').remove();
                if(postback){
                  $(".load-more").text("Load more");
                }
                // Reset the value of row
                $("#row").val(0);
                
            }, 2000);


        }

    });

});
function printDiv() {

  var contentTable=document.getElementById('contentTable');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+contentTable.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}
$(document).ready(function(){
  var f1 = flatpickr(document.getElementById('fromFlatpickr'));
  var f1 = flatpickr(document.getElementById('toFlatpickr'));

  flatpickr(document.getElementById('fromdateP'));
  flatpickr(document.getElementById('todateP'));

  flatpickr(document.getElementById('fDate'));
  flatpickr(document.getElementById('tDate'));


});
$(document).ready(function(){
  $(".tagging").select2({
    tags: true,
    placeholder: 'Search User...'
  });

});
  $(document).ready(function(){
    var quill = new Quill('#editor', {
    theme: 'snow'
    });
    var quill2 = new Quill('#editor-readonly', {
        theme: 'snow'
    });
    $("#saveContent").click(function(){
      var lContent = quill.root.innerHTML;
      listingContent = lContent.replace(/'/g, "\\'");
      var taskID = $(".taskID").val();
      var post_m = "listingContent";
      $.ajax({
          method: "post",
          url:"common/ajax/ajax_data.php",
          dataType: 'text',
          data:{taskID:taskID,listingContent:listingContent,post_m:post_m},
          success:function(data){
              swal({
                title: 'CONTENT SAVED!',
                padding: '2em',
                type: 'success'
              }).then(function (result) {
                location.reload(true);
              })            
          }
      });  
    });
     
});

$(document).ready(function(){

    $(document).on('click', '.saveAnnounce', function(){
        var title = $('.title').val();
        var detail = $('.detail').val();
        var status = $('.status').val();
        var post_m = "saveAnnounce";
        if(title == ""){
          swal({
              title: 'TITLE REQUIRED?',
              type: 'question',
              padding: '2em'
          });   
        }else if(detail == ""){
          swal({
              title: 'DETAIL REQUIRED?',
              type: 'question',
              padding: '2em'
          });  
        }else{
          $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{title:title,detail:detail,status:status,post_m:post_m},
            success:function(data){
              if(data == '1'){
                  swal({
                    title: 'ANNOUNCEMENT SAVED!',
                    padding: '2em',
                    type: 'success'
                  }).then(function (result) {
                    location.reload(true);
                  })
              }
            }
          });  
        }
    });
    
    $(document).on('click', '.closeAnnouce', function(){
        $('.title').val('');
        $('.detail').val('');
    });
    $(document).on('click', '.editAnnounce', function(){
        $('#updateAnnoucement').modal('show');
        var id = $(this).attr('data-id');
        var post_m = "editAnnounce";
        $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{id:id,post_m:post_m},
            success:function(data){
              data = $.parseJSON(data);
              $('.id').val(data['id']);
              $('.editTitle').val(data['title']);
              $('.editDetail').val(data['detail']);
            }
          }); 
    });
    $(document).on('click', '.updateAnnounce', function(){
        var id = $('.id').val();
        var title = $('.editTitle').val();
        var detail = $('.editDetail').val();
        var post_m = "updateAnnounce";
          $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{id:id,title:title,detail:detail,post_m:post_m},
            success:function(data){
              if(data == '1'){
                  swal({
                    title: 'RECORD UPDATED!',
                    padding: '2em',
                    type: 'success'
                  }).then(function (result) {
                    location.reload(true);
                  })
              }
            }
          });  
        
    });
});
$(document).ready(function(){
    $(document).on('change', '.announceStatus', function(){
        var statusID = $(this).val();
        var annouceID = $(this).attr('data-id');
        var post_m = "announceStatus";
        $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{statusID:statusID,annouceID:annouceID,post_m:post_m},
            success:function(data){
              if(data == '1'){
                swal({
                    title: 'RECORD UPDATED!',
                    padding: '2em',
                    type: 'success'
                  })
               
              }
            }
        });
    });

    $(document).on('click', '.deleteAnnounce', function(){
        var id = $(this).attr('data-id');
        var post_m = 'deleteAnnounce';
        swal({
          title: 'Are you sure you want to delete?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete?',
          padding: '2em'
        }).then(function(result) {
          if (result.value) {
            $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{id:id,post_m:post_m},
                success:function(data){
                  if(data == '1'){
                      swal({
                        title: 'DELETED!',
                        padding: '2em',
                        type: 'success'
                      }).then(function (result) {
                        location.reload(true);
                      })
                  }
                }
            }); 
            
          }
        })
        
    });
    
});   
$(document).ready(function(){
    $(document).on('click', '.copyTask', function(){
       var taskID = $(this).attr('data-id');
       var post_m = "copyTask";
        swal({
          title: 'Are you sure you want to replicate?',
          type: 'warning',
          confirmButtonText: 'Replicate',
          showCancelButton: true,
          padding: '2em'
        }).then(function(result) {
          if (result.value) {
            $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{taskID:taskID,post_m:post_m},
                success:function(data){
                  swal({
                    title: 'TASK COPIED!',
                    padding: '2em',
                    type: 'success'
                  }).then(function (result) {
                    location.reload(true);
                  })
                }
            });          
          }
        })
         
    });
    $(document).on('keyup', '.search_content', function(){

        var searchValue = $(this).val();
        var post_m = "search_content";
        $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{searchValue:searchValue,post_m:post_m},
            success:function(data){
              $('.searchContentDiv').html(data);
            }
        }); 

    });
    $(document).on('click', '#exportClick', function(){
        var fromdate =  $(".fromdate").val();
        var todate =  $(".todate").val();
        var post_m = "exportFile";
        $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{fromdate:fromdate,todate:todate,post_m:post_m},
            success:function(result){
                setTimeout(function() {
                  var dlbtn = document.getElementById("dlbtn");
                  var file = new Blob([result], {type: 'text/csv'});
                  dlbtn.href = URL.createObjectURL(file);
                  dlbtn.download = 'myfile.csv';
                  $( "#mine").click();
                }, 1000);
                $(".fromdate").val("");
                $(".todate").val("");
                $("#exportModal").modal('hide');

            }
        });
    });    
});
$(document).ready(function(){
    // Javascript to enable link to tab
    var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
    if (hash) {
        $('.nav-tabs a[href="#' + hash + '"]').tab('show');
    } 

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    })
    $(document).on('change', '.channels_row', function(){
        
        var id = $(this).attr("data-id");
        var channelID = $("#channel_"+id).val();

        var channelID = $(this).val();
        var post_m = "channelChange";
        $.ajax({
            method: "post",
            url:"common/ajax/ajax_data.php",
            dataType: 'text',
            data:{channelID:channelID,post_m:post_m},
            success:function(data){
              $(".stores_"+id).html(data);
            }
        }); 

    });
      $(document).on('change', '.owner', function(){
        if($(this).val() != ""){
            $('.newsubTask').show();
        }else if($(this).val() == ""){
            $('.newsubTask').hide();
        }
      });

       $(document).on('click', '.directMsg', function(){ 
            
              var msg = $('.message').val();
              var taskID = $('.taskID').val();
              var userID = $('.userID').val();
              var assignedTo = $('.assignedTo').val();
              var post_m = "directMessage";
              if(assignedTo == ""){
                  $('.error').text('Recipient is required').css("color",'Red');
              }else if(msg == ""){
                  $('.error').text('Message is required').css("color",'Red');
              }else{
               $.ajax({
                  method: "post",
                  url:"common/ajax/ajax_data.php",
                  dataType: 'text',
                  data:{msg:msg,taskID:taskID,userID:userID,assignedTo:assignedTo,post_m:post_m},
                  success:function(data){
                      if(data == '1'){
                       location.reload(true);
                      }
                    
                  }
               });   
              }

           });
                  
           $(document).on('click', '.close_btn', function(){
            $('.message').val('');
            $('.error').text('');
           });
           
           $(document).on('click', '.replyClick', function(){
            var messageID = $(this).attr('data-id');
            $('.replymessage_'+messageID).show().fade();
            
           });
           
           
           $(document).on('keypress', '#replymessage', function(){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var messageID = $(this).attr('data-id');
                var reply = $('.replymessage_'+messageID).val();
                var post_m = "replymessage";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{messageID:messageID,reply:reply,post_m:post_m},
                    success:function(data){
                        
                    }
                 });   
            }
             
           });   
      $(document).on('click', '.add_task_detail', function(){
          var taskID = $(this).attr('data-id');
          var ref_url = $('.ref_url').val();
          var purchasePrice = $('.purchasePrice').val();
          var productCode = $(".productCode").val();
          var amzPrice = $(".amzPrice").val();
          var ebayPrice = $(".ebayPrice").val();
          var webPrice = $(".webPrice").val();
          var quantity = $(".quantity").val();
          var storeSKU = $(".storeSKU").val();
          var linkedSKU = $(".linkedSKU").val();
          var EAN = $(".EAN").val();
          var ASIN = $(".ASIN").val();
          //var listingType = $(".listingType").val();

          var post_m = "task_prices_detail";
          var file = document.getElementById("file").files[0];
          var form_data = new FormData();
          form_data.append("file_name",file);
          form_data.append("ref_url",ref_url);
          form_data.append("taskID",taskID);
          form_data.append("purchasePrice",purchasePrice);
          form_data.append("productCode",productCode);
          form_data.append("amzPrice",amzPrice);
          form_data.append("ebayPrice",ebayPrice);
          form_data.append("webPrice",webPrice);
          form_data.append("quantity",quantity);
          form_data.append("storeSKU",storeSKU);
          form_data.append("linkedSKU",linkedSKU);
          form_data.append("EAN",EAN);
          form_data.append("ASIN",ASIN);
          //form_data.append("listingType",listingType);

          form_data.append("post_m",post_m);

          $.ajax({
                  method: "post",
                  url:"common/ajax/ajax_data.php",
                  data:form_data,
                  dataType:'text',
                  contentType: false,
                  cache: false,
                  processData:false,
                  success:function(data){
                     if(data =="0"){
                       alert('Sorry, your file is too large. Please upload less then 5MB');
                       console.log('Sorry, your file is too large. Please upload less then 5MB');    
                     }else{
                      swal({
                        title: 'RECORD ADDED!',
                        padding: '2em',
                        type: 'success'
                      }).then(function (result) {
                        location.reload(true);
                      })
                     }
                      
                  }
              });
            });

            $(document).on('click', '.replyPost', function(){ 
            
              var msg = $('.msg').val();
              var userID = $('.userID').val();
              var pID = $('.pID').val();
              var thread = $('.thread').val();
              var post_m = "replyPost";

              $.ajax({
                  method: "post",
                  url:"common/ajax/ajax_data.php",
                  dataType: 'text',
                  data:{msg:msg,userID:userID,pID:pID,thread:thread,post_m:post_m},
                  success:function(data){
                    $(".message-body").append(data);
                    $('.msg').val("");
                    //$('#messageModal').modal('hide');
                  }
               });

           });
           $(document).on('click', '.newsubTask', function(){
                
                var taskID = $('.task_id').val();
                var user = $('.owner').val();
                var supervisor = $('.supervisor').val();
                var subtask = $('.subtask').val();
                var channels = $("#channel_assign").val();
                var stores = $("#store_assign").val();
                var subDeadline = $("#subDeadline").val();
                var currentDate = $("#current_date").val();
                var des_new_aasignee = $("#des_new_aasignee").val();

                var post_m = "new_sub_Task"; 
                
                if(subtask == ""){
                    swal({
                      title: 'REQUIRED FIELD?',
                      text: "PLEASE SELECT SUB TASK",
                      type: 'question',
                      padding: '2em'
                    });
                }else if(supervisor == ""){
                    swal({
                      title: 'REQUIRED FIELD?',
                      text: "PLEASE SELECT SUPERVISOR",
                      type: 'question',
                      padding: '2em'
                    });
                }else if(subDeadline == ""){
                    swal({
                      title: 'REQUIRED FIELD?',
                      text: "PLEASE SELECT DEADLINE",
                      type: 'question',
                      padding: '2em'
                    });
                }else if(subDeadline < currentDate){
                    swal({
                      title: 'REQUIRED FIELD?',
                      text: "DEADLINE SHOULD NOT BE LESS THEN TASK CREATION DATE",
                      type: 'question',
                      padding: '2em'
                    });
                }else if(channels != '0' & stores == '0'){
                    swal({
                      title: 'REQUIRED FIELD?',
                      text: "STORE IS REQUIRED, IF YOU SELECT CHANNEL",
                      type: 'question',
                      padding: '2em'
                    });
                }else{
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{taskID:taskID,supervisor:supervisor,user:user,subtask:subtask,channels:channels,stores:stores,subDeadline:subDeadline,des_new_aasignee:des_new_aasignee,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'RECORD ADDED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    });    
                } 
                
           });
           $(document).on('change', '.supplier_type', function(){
              var supplier_type_name = $(this).val();
              var post_m = "supplier_type_name";
              if(supplier_type_name == "1"){
                $("#supplier").show();
              }else if(supplier_type_name == "2"){
                $("#supplier").hide();
              }
              $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{supplier_type_name:supplier_type_name,post_m:post_m},
                success:function(data){
                  $(".supplier").html(data);
                }
              });  
            });

           $(document).on('click', '#get_report', function(){
                var supplier_type = $(".supplier_type").val();
                var supplier = $(".supplier").val();
                var listing_type = $(".listing_status").val();
                var fromDate = $(".fromdate").val();
                var toDate = $(".todate").val();
                var post_m = "stm_supplier_report";
                $.ajax({
                method: "post",
                url:"common/ajax/stm_supplier_report.php",
                dataType: 'text',
                data:{supplier_type:supplier_type,supplier:supplier,listing_type:listing_type,fromDate:fromDate,toDate:toDate,post_m:post_m},
                    success:function(data){
                        console.log(data);
                    }
                });
                
            });
           $(document).on('click', '.editsubtask', function(){

                $('#edittask').modal('show');
                var subID = $(this).attr('data-id');
                var post_m = "edittask"; 
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{subID:subID,post_m:post_m},
                    success:function(data){
                        var data = $.parseJSON(data);
                        $(".assignedToSP").html(data['output']);
                        $(".channelsSP").text(data['channels']);
                        $(".storesSP").text(data['stores']);
                        $(".deadlineSpan").text(data['deadline']);
                        $(".subtaskSP").text(data['subtask']);
                        $(".sub_id").val(data['subID']);
                        $(".description_edit").val(data['description_edit']);
                    }
                }); 
            });
           

           // $(document).on('click', '.replyClick', function(){
           //  var messageID = $(this).attr('data-id');
           //  $('.replymessage_'+messageID).show().fade();
            
           // });
           
           
           // $(document).on('keypress', '#replymessage', function(){
           //  var keycode = (event.keyCode ? event.keyCode : event.which);
           //  if(keycode == '13'){
           //      var messageID = $(this).attr('data-id');
           //      var reply = $('.replymessage_'+messageID).val();
           //      var post_m = "replymessage";
           //      $.ajax({
           //          method: "post",
           //          url:"common/ajax/ajax_data.php",
           //          dataType: 'text',
           //          data:{messageID:messageID,reply:reply,post_m:post_m},
           //          success:function(data){
                        
           //          }
           //       });   
           //  }
             
           // });  
           // $(document).on('click', '.direct_msg_modal', function(){ 
            
           //    var msg = $('.message').val();
           //    var taskID = $('.taskID').val();
           //    var userID = $('.userID').val();
           //    var assignedTo = $('.assignedTo').val();
           //    var post_m = "directMessage";
           //    if(assignedTo == ""){
           //        $('.error').text('Recipient is required').css("color",'Red');
           //    }if(msg == ""){
           //        $('.error').text('Message is required').css("color",'Red');
           //    }else{
           //     $.ajax({
           //        method: "post",
           //        url:"common/ajax/ajax_data.php",
           //        dataType: 'text',
           //        data:{msg:msg,taskID:taskID,userID:userID,assignedTo:assignedTo,post_m:post_m},
           //        success:function(data){
           //            if(data == '1'){
           //             location.reload(true);
           //            }
                    
           //        }
           //     });   
           //    }

           // });
           
            $(document).on('change', '.channels', function(){
                
                var channelID = $(this).val();
                var post_m = "channelChange";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{channelID:channelID,post_m:post_m},
                    success:function(data){
                      $(".stores").html(data);
                    }
                }); 
            });
            $(document).on('click', '.rejected', function(){
                $("#add-reason").modal('show');
                var id = $(this).attr("data-id");
                $('.subID').val(id);
                 
            });
            // $(document).on('click', '.add_reason', function(){
            //     $('#add-reason').modal('show');
            //     var subID = $(this).attr('data-id');
            //     $('.subID').val(subID);
                
            // });
            $(document).on('click', '.saveReason', function(){
                var subID = $('.subID').val();
                var reason = $('.reason').val();
                var taskID = $('.taskID').val();
                var post_m = "rejected";
                if(reason != ""){
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{subID:subID,taskID:taskID,reason:reason,post_m:post_m},
                        success:function(data){
                              swal({
                                title: 'TASK REJECTED!',
                                padding: '2em',
                                type: 'success'
                              }).then(function (result) {
                                location.reload(true);
                              })
                        }
                    });
                }else{
                   swal({
                      title: 'REQUIRED FIELD?',
                      text: "PLEASE ADD REASON",
                      type: 'question',
                      padding: '2em'
                    }); 
                }
            });
            $(document).on('click', '.approved', function(){
                var id = $(this).attr("data-id");
                var taskID = $('.taskID').val();
                var post_m = "approved";
                swal({
                  title: 'Are you sure you want to approve?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Approve',
                  padding: '2em'
                }).then(function(result) {
                  if (result.value) {
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{id:id,taskID:taskID,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'TASK APPROVED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    }); 
                    
                  }
                })
            });    
            $(document).on('click', '.remsubTask', function(){
                var id = $(this).attr("data-id");
                var taskID = $(".task_id").val();
                var post_m = "remove_sub_task";
                swal({
                  title: 'Are you sure you want to delete?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Delete?',
                  padding: '2em'
                }).then(function(result) {
                  if (result.value) {
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{taskID:taskID,id:id,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'DELETED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    }); 
                    
                  }
                })
            });
            $(document).on('click', '.rem_detail', function(){
                if (confirm('Are you sure?')) {
                    var id = $(this).attr('data-id');
                    var post_m = "rem_detail";
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{id:id,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'DELETED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    });
                }
            });

            $(document).on('click', '.savesubTask', function(){
                
                var subID = $(this).attr('data-id');
                var taskID = $('.task_id').val();
                var user = $('.owner_'+subID).val();
                var subtask = $('.subtask_'+subID).val();
                var channels = $(".channels_"+subID).val();
                var supervisor = $(".supervisor_"+subID).val();
                var stores = $(".stores_"+subID).val();
                
                var subDeadline = $("#subDeadline_"+subID).val();
                var description_edit = $("#des_new_aasignee_"+subID).val();
                
                var post_m = "savesubTask"; 

                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{taskID:taskID,user:user,supervisor:supervisor,subtask:subtask,channels:channels,stores:stores,subDeadline:subDeadline,description_edit:description_edit,subID:subID,post_m:post_m},
                    success:function(data){
                        if(data != "0"){
                            // location.reload(true);
                            swal({
                              title: 'RECORD UPDATED',
                              padding: '2em',
                              type: "success",
                              timer:2000
                            });
                            $('.owner_'+subID).attr('disabled', 'disabled');
                            $('.subtask_'+subID).attr('disabled', 'disabled');
                            $(".channels_"+subID).attr('disabled', 'disabled');
                            $(".stores_"+subID).attr('disabled', 'disabled');
                            $("#subDeadline_"+subID).attr('disabled', 'disabled');
                            $("#des_new_aasignee_"+subID).attr('disabled', 'disabled');
                            $(".supervisor_"+subID).attr('disabled', 'disabled');
                            $("#save_task_"+subID).show();
                            $("#rem_clone_"+subID).show();
                            $("#copy_"+subID).show();
                            $("#update_clone_"+subID).hide();
                        }       
                    }
                });
            });

            $(document).on('change', '#channel_new', function(){
                var id = $(this).attr("data-id");
                var channelID = $(".channels_"+id).val();
                var post_m = "channelChange";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{channelID:channelID,post_m:post_m},
                    success:function(data){
                      $(".stores_"+id).html(data);
                    }
                }); 

            });

            $(document).on('change', '.select_filter', function(){

                filter_val = $(this).val();
                if(filter_val == "Category"){
                    $("#category").show();
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").hide();
                    $("#created_by").val("");
                    $(".flatpickr-input").show();
                    $("#status").show();
                    
                    $("#priority").hide();
                    $("#priority").val("");
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Created By"){
                    $("#category").hide();
                    $("#category").val("");
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").show();
                    $(".flatpickr-input").show();
                    $("#deadline").hide();
                    $("#deadline").val("");
                    $("#priority").hide();
                    $("#priority").val("");
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Status"){
                    $("#category").hide();
                    $("#category").val("");
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").hide();
                    $(".flatpickr-input").show();
                    $("#created_by").val("");
                    $("#deadline").hide();
                    $("#priority").hide();
                    $("#priority").val("");
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val();
                }else if(filter_val == "Priority"){
                    $("#category").hide();
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").hide();
                    $("#created_by").val("");
                    $("#category").val("");
                    $(".flatpickr-input").show();
                    $("#priority").show();
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Assignees"){
                    $("#assignees_filter").show();
                    $("#category").hide();
                    $("#created_by").hide();
                    $("#category").val("");
                    $("#created_by").val("");
                    
                    $(".flatpickr-input").show();
                    $("#priority").hide();
                    $("#priority").val("");
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Skype"){
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#skype").show();
                    $("#category").hide();
                    $("#created_by").hide();
                    $("#category").val("");
                    $(".flatpickr-input").show();
                    $("#created_by").val("");
                    $("#priority").hide();
                    $("#priority").val("");
                    $("#status").show();
                }
                $("#filterBy").show();
                $("#reset").show();
            });

            $(document).on('change', '#select_filter_task', function(){
                filter_val = $(this).val();
                if(filter_val == "Category"){
                    $("#category_task").show();
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").hide();
                    $("#created_by").val("");
                    $(".flatpickr-input").show();
                    $("#status").show();
                    $("#priority_task").hide();
                    $("#priority_task").val("");
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Created By"){
                    $("#category_task").hide();
                    $("#category_task").val("");
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by_task").show();
                    $(".flatpickr-input").show();
                    $("#priority_task").hide();
                    $("#priority_task").val("");
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val("");
                }else if(filter_val == "Priority"){
                    $("#category_task").hide();
                    $("#category_task").val("");
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#created_by").hide();
                    $(".flatpickr-input").show();
                    $("#created_by_task").val("");
                    $("#priority_task").show();
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val();
                }else if(filter_val == "Assignees"){
                    $("#category_task").hide();
                    $("#category_task").val("");
                    $("#assignees_filter").show();
                    $("#created_by_task").hide();
                    $(".flatpickr-input").show();
                    $("#created_by_task").val("");
                    $("#priority_task").hide();
                    $("#priority_task").val("");
                    $("#status").show();
                    $("#skype").hide();
                    $("#skype").val();
                }else if(filter_val == "Skype"){
                    $("#assignees_filter").hide();
                    $("#assignees_filter").val("");
                    $("#skype").show();
                    $("#category_task").hide();
                    $("#created_by_task").hide();
                    $("#category_task").val("");
                    $(".flatpickr-input").show();
                    $("#created_by_task").val("");
                    $("#priority_task").hide();
                    $("#priority_task").val("");
                    $("#status").show();
                }
                $("#filterBy_task").show();
                $("#reset").show();
            });
            
            $(document).on('change', '.mapping_area_filter', function(){
             var channelID = $(this).val();
             var post_m = "mapping_area_filter";
             $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{channelID:channelID,post_m:post_m},
                success:function(data){
                  $("#mapping_area_stores").html(data);
                }
             });
            });

          $(document).on('change', '#sync_sku', function(){
                
                var row_id = $(this).attr("data-id");
                var statusID = $(this).val();
                var post_m = "sync_sku";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{row_id:row_id,statusID:statusID,post_m:post_m},
                    success:function(data){
                      if(data == "Linked"){
                        $('.sync_sku_'+row_id).css("background-color","#0066cc");
                        $('.sync_sku_'+row_id).css("color","#fff");
                      }else if(data == "Unlinked"){
                        $('.sync_sku_'+row_id).css("background-color","#ffff66");
                        $('.sync_sku_'+row_id).css("color","#444");
                      }else if(data == "Issue"){
                        $('.sync_sku_'+row_id).css("background-color","#cc3300");
                        $('.sync_sku_'+row_id).css("color","#fff");
                      }
                    }
                }); 

            });

          $(document).on('keyup', '#issueNote', function(){
             var row_id = $(this).attr('data-id');
             var note = $('.issueNote_'+row_id).val();
             var post_m = "issueNote";
             $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{row_id:row_id,note:note,post_m:post_m},
                success:function(data){
                  console.log(data);
                }
             });
           });

           $(document).on('click', '.updateBrand', function(){
                $("#editBrand").modal('show');
                var id = $(this).attr('data-id');
                $("#brandID").val(id);
                var post_m = "updateBrand";
                $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{id:id,post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $('.supplier_ID').val(data['supplierID']);
                        $('.brandName').val(data['brandName']);
                        $('.supplierSP').text(data['supplierName']);
                    }
                });
            });

            $(document).on('click', '.saveBrand', function(){
                
                var brandID = $("#brandID").val();
                var brandName = $(".brandName").val();
                var supplier_ID = $(".supplier_ID").val();
                var supplier = $(".supplier").val();
                var post_m = "FinalupdateBrand";

                $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{brandID:brandID,brandName:brandName,supplier_ID:supplier_ID,supplier:supplier,post_m:post_m},
                success:function(data){
                   window.location = "stmbrands.php";
                }
                });
            });

            $(document).on('click', '#buttn', function(){
                
                var id = $(this).attr('data-id'); 
                var stmtasktypes = $(".task_type_"+id).val();
                
                var post_m = "stmtasktypes";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{stmtasktypes:stmtasktypes,id:id,
                        post_m:post_m},
                    success:function(data){
                        if(data == '1'){
                         window.location='stmtasktypes.php';    
                        }else if(data == '0'){
                            $('.error').html(data);
                        }
                    }
                }); 
            });
            
            $(document).on('click', '.updateSupplier', function(){
                $("#editSupplier").modal('show');
                var id = $(this).attr('data-id');
                $("#supplierID").val(id);
                var post_m = "updateSupplier";
                $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{id:id,post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $('.supplierType_ID').val(data['supplierTypeID']);
                        $('.supplierName').val(data['supplierName']);
                        $('.supplierTypeSP').text(data['supplierType']);
                        $("#editSupplier").modal('hide');
                    }
                });
            });

            $(document).on('click', '.save_supplier', function(){
                
                var supplierID = $("#supplierID").val();
                var supplierName = $(".supplierName").val();
                var supplierType = $(".supplier_Type").val();
                var supplierTypeID = $(".supplierType_ID").val();
                var post_m = "FinalupdateSupplier";

                $.ajax({
                method: "post",
                url:"common/ajax/ajax_data.php",
                dataType: 'text',
                data:{supplierID:supplierID,supplierName:supplierName,supplierType:supplierType,supplierTypeID:supplierTypeID,post_m:post_m},
                success:function(data){
                   window.location = "stmsuppliers.php";
                }

                });
            });

            $(document).on('click', '#update_subtask', function(){
                
                var id = $(this).attr('data-id'); 
                var stmtasktypes = $(".task_type_"+id).val();
                
                var post_m = "stmsubtask";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{stmtasktypes:stmtasktypes,id:id,
                        post_m:post_m},
                    success:function(data){
                        if(data == '1'){
                         window.location='stmsubtask.php';    
                        }else if(data == '0'){
                            $('.error').html(data);
                        }
                    }
                }); 
            });
      
    load_data_announcement(1);

      function load_data_announcement(page,query=''){
        $.ajax({
            url:"common/ajax/stm_annouce_data.php",
            method:"POST",
            data:{page:page,query:query},
            success:function(data)
            {
              $('.announceContainer').html(data);
            }
        });
      }
     
        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.searchAnnounce').val();
          load_data_announcement(page, query);
        });

        $(document).on('keyup', '.searchAnnounce', function(){
          var query = $('.searchAnnounce').val();
          load_data_announcement(1, query);
        });        


      load_data_skus(1);

      function load_data_skus(page,query='',channelID='',statusStore='',store_names=''){
        $.ajax({
            url:"common/ajax/stm_mapping.php",
            method:"POST",
            data:{page:page,query:query,channelID:channelID,statusStore:statusStore,
              store_names:store_names},
            success:function(data)
            {
              $('.mapping_area').html(data);
            }
        });
      }
      $(document).on('click', '.filterButton', function(){
          
          var channelID = $(".filterButton").attr('data-id');
          var statusStore = $('.statusStore').val();
          var store_names = $('.store_names').val();
          var query = $('.mapping_area_search').val();
          load_data_skus(1,query,channelID,statusStore,store_names);
      });

      $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.mapping_area_search').val();
          load_data_skus(page, query);
        });

        $(document).on('keyup', '.mapping_area_search', function(){
        // $('.task_comp_search').keyup(function(){
          var query = $('.mapping_area_search').val();
          load_data_skus(1, query);
        });

      $(document).on('click', '.subtaskDesc', function(){
                var descID = $(this).attr('data-id');
                $("#viewinfo").modal('show');
                var post_m = "taskDescription";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{descID:descID,post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $(".comments").val(data['subTaskDescription']);
                    }
                }); 
            });
            $(document).on('click', '.subtask_Desc', function(){
                var descID = $(this).attr('data-id');
                $("#viewinfo").modal('show');
                var post_m = "taskDescription";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{descID:descID,post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $(".comments").val(data['subTaskDescription']);
                    }
                }); 
            });
            $(document).on('click', '.postMessage', function(){ 
            
              var msg = $('.chat_message').val();
              var taskID = $('.taskID').val();
              var userID = $('.userID').val();
              var assignedTo = $('.assignedTo').val();
              var post_m = "postMessage";
              if(assignedTo == ''){
                swal({
                  title: 'REQUIRED FIELD?',
                  text: "Recipient is required",
                  type: 'question',
                  padding: '2em'
                });
              }else{
                $.ajax({
                  method: "post",
                  url:"common/ajax/ajax_data.php",
                  dataType: 'text',
                  data:{msg:msg,taskID:taskID,userID:userID,assignedTo:assignedTo,post_m:post_m},
                  success:function(data){
                    $(".message-body").prepend(data);
                    $('.chat_message').val("");
                    //$('#messageModal').modal('hide');
                  }
                });
              }

           });
           $(document).on('click', '.save_task', function(){
               var id = $(this).attr('data-id');
               $('.owner_'+id).removeAttr('disabled');
               $('.subtask_'+id).removeAttr('disabled');
               $('.channels_'+id).removeAttr('disabled');
               $('.stores_'+id).removeAttr('disabled');
               $('.supervisor_'+id).removeAttr('disabled');
               $('#subDeadline_'+id).removeAttr('disabled');
               $('#des_new_aasignee_'+id).removeAttr('disabled');
               
               $(this).hide();
               $("#rem_clone_"+id).hide();
               $("#copy_"+id).hide();
               $("#update_clone_"+id).show();
            });
           $(document).on('click', '.edit_detail_prelst', function(){
               var id = $(this).attr('data-id');
               $('.ref_url_'+id).removeAttr('disabled');
               $('.productCode_'+id).removeAttr('disabled');
               $('.purchasePrice_'+id).removeAttr('disabled');
               $('.quantity_'+id).removeAttr('disabled');
               $('#channel_'+id).removeAttr('disabled');
               $('#store_'+id).removeAttr('disabled');
               $('.salePrice_'+id).removeAttr('disabled');
               $('.storeSKU_'+id).removeAttr('disabled');
               $('.linkedSKU_'+id).removeAttr('disabled');
               $('.EAN_'+id).removeAttr('disabled');
               $('.ASIN_'+id).removeAttr('disabled');
               $('.listingType_'+id).removeAttr('disabled');
               $('.refTitle_'+id).removeAttr('disabled');
               $(this).hide();
               $("#clone_rem_"+id).hide();
               $("#update_detail_prelst_"+id).show();
            });
           $(document).on('click', '.edit_detail_ref', function(){
               var id = $(this).attr('data-id');
               $('.ref_url_'+id).removeAttr('disabled');
               $('.productCode_'+id).removeAttr('disabled');
               $('.purchasePrice_'+id).removeAttr('disabled');
               $('.quantity_'+id).removeAttr('disabled');
               $('.amzPrice_'+id).removeAttr('disabled');
               $('.ebayPrice_'+id).removeAttr('disabled');
               $('.webPrice_'+id).removeAttr('disabled');
               $('.storeSKU_'+id).removeAttr('disabled');
               $('.linkedSKU_'+id).removeAttr('disabled');
               $('.EAN_'+id).removeAttr('disabled');
               $('.ASIN_'+id).removeAttr('disabled');
               $('#update_file_'+id).removeAttr('disabled');
               $(this).hide();
               $("#clone_rem_"+id).hide();
               $("#update_detail_"+id).show();
            });
           $(document).on('click', '.update_detail', function(){
                
                var taskID = $('.task_ID').val();
                var taskdetailID = $(this).attr('data-id');
                var ref_url = $('.ref_url_'+taskdetailID).val();
                var purchasePrice = $('.purchasePrice_'+taskdetailID).val();
                var productCode = $(".productCode_"+taskdetailID).val();
                var amzPrice = $(".amzPrice_"+taskdetailID).val();
                var ebayPrice = $(".ebayPrice_"+taskdetailID).val();
                var webPrice = $(".webPrice_"+taskdetailID).val();
                var storeSKU = $(".storeSKU_"+taskdetailID).val();
                var linkedSKU = $(".linkedSKU_"+taskdetailID).val();
                var EAN = $(".EAN_"+taskdetailID).val();
                var ASIN = $(".ASIN_"+taskdetailID).val();
                //var listingType = $(".listingType_"+taskdetailID).val();
                var quantity = $(".quantity_"+taskdetailID).val();
                var old_file = $(".old_file_"+taskdetailID).val();
                var file = document.getElementById("update_file_"+taskdetailID).files[0];
                var post_m = "update_detail_row";

                var form_data = new FormData();
                form_data.append("file_name",file);
                form_data.append("ref_url",ref_url);
                form_data.append("taskdetailID",taskdetailID);
                form_data.append("purchasePrice",purchasePrice);
                form_data.append("productCode",productCode);
                form_data.append("amzPrice",amzPrice);
                form_data.append("ebayPrice",ebayPrice);
                form_data.append("webPrice",webPrice);

                form_data.append("storeSKU",storeSKU);
                form_data.append("linkedSKU",linkedSKU);
                form_data.append("EAN",EAN);
                form_data.append("ASIN",ASIN);
                //form_data.append("listingType",listingType);
                
                form_data.append("quantity",quantity);
                form_data.append("old_file",old_file);
                form_data.append("post_m",post_m);
                
                $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        data:form_data,
                        contentType: false,
                        cache: false,
                        processData:false,
                        success:function(data){
                            $('.file_show_'+taskdetailID).remove();
                            $('.image_'+taskdetailID).append(data);
                            swal({
                              title: 'RECORD UPDATED',
                              padding: '2em',
                              type: "success",
                              timer:2000
                            });
                            $("#clone_"+taskdetailID).show();
                           $("#clone_rem_"+taskdetailID).show();
                           $("#update_detail_"+taskdetailID).hide();
                           $('.ref_url_'+taskdetailID).attr('disabled', 'disabled');
                           $('.purchasePrice_'+taskdetailID).attr('disabled', 'disabled');
                           $(".productCode_"+taskdetailID).attr('disabled', 'disabled');
                           $(".amzPrice_"+taskdetailID).attr('disabled', 'disabled');
                           $(".ebayPrice_"+taskdetailID).attr('disabled', 'disabled');
                           $(".webPrice_"+taskdetailID).attr('disabled', 'disabled');
                           $(".storeSKU_"+taskdetailID).attr('disabled', 'disabled');
                           $(".linkedSKU_"+taskdetailID).attr('disabled', 'disabled');
                           $(".EAN_"+taskdetailID).attr('disabled', 'disabled');
                           $(".ASIN_"+taskdetailID).attr('disabled', 'disabled');
                           $(".quantity_"+taskdetailID).attr('disabled', 'disabled');
                           $('#update_file_'+taskdetailID).attr('disabled', 'disabled');
                        }
                    });
            });
 
            $(document).on('change', '.status', function(e){
            
                var statusID = $(".status").val();
                var subID = $(".subID").val();
                $(this).remove();
                var id = $(".id").val();
                var post_m = "changeStatus";

                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{statusID:statusID,subID:subID,
                        post_m:post_m,id:id},
                    success:function(data){
                        
                        data = $.parseJSON(data);
                        
                          if(data['start_date'] != "" && data['end_date'] == ""){
                            swal({
                              title: 'WORK STARTED',
                              padding: '2em',
                              type: "success",
                              timer:2000
                            });
                            $('.ended_on_'+subID).html(data['end_date']);
                            $('.started_on_'+subID).html(data['start_date']);
                            $(".status_assig_"+subID).html(data['output']);
                          }else if(data['end_date'] != "" && data['start_date'] != ""){
                            swal({
                              title: 'WORK DONE!',
                              padding: '2em',
                              type: 'success'
                            }).then(function (result) {
                              location.reload(true);
                            })  
                          }else if(data['end_date'] == "" && data['start_date'] == ""){
                            swal({
                              title: 'TASK RENEWED!',
                              padding: '2em',
                              type: 'success'
                            }).then(function (result) {
                              location.reload(true);
                            })

                          }
                           
                    }
                }); 
               
            });

            $(document).on('click', '.addInfo', function(){
                $('#info').modal('show');
            });

            $(document).on('click', '.saveInfo', function(){
                var subID = $('.subID').val();
                var URL = $('.url_view').val();
                var id = $('.taskID').val();
                
                var comments = $('.comments_view').val();
                var post_m = "saveInfo";

                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{URL:URL,id:id,subID:subID,comments:comments,
                        post_m:post_m},
                    success:function(data){  
                        location.reload(true);
                    }
                });    
                
                
            });
            
            $(document).on('click', '.inActive', function(){
                var taskID = $(this).attr('data-id');
                var post_m = "inActive";
                swal({
                  title: 'Are you sure you want to delete?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Delete',
                  padding: '2em'
                }).then(function(result) {
                  if (result.value) {
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{taskID:taskID,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'DELETED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    });
                  }
                })
            });
            $(document).on('click', '.active_task', function(){
                var taskID = $(this).attr('data-id');
                var post_m = "active";
                swal({
                  title: 'Are you sure you want to Active?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Active',
                  padding: '2em'
                }).then(function(result) {
                  if (result.value) {
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{taskID:taskID,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'TASK ACTIVATED!',
                            padding: '2em',
                            type: 'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    });
                  }
                })
            });
            $(document).on('click', '.viewInfo', function(){
                $('#info').modal('show');
                var subID = $(this).attr('data-id');
                var id = $(".id").val();
                var post_m = "viewInfo"; 
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{subID:subID,id:id,
                        post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $(".url_view").val(data['URL']);
                        $(".comments_view").val(data['comments']);
                         
                    }
                }); 
            });
            $(document).on('click', '.viewDetail', function(){
                $('#viewinfo').modal('show');
                var subID = $(this).attr('data-id');
                var post_m = "viewDetail"; 
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{subID:subID,
                        post_m:post_m},
                    success:function(data){
                        data = $.parseJSON(data);
                        $(".comments").val(data['comments']);         
                    }
                }); 
            });

            $(document).on('change', '.supplier', function(){
                
                var supplierID = $(this).val();
                var post_m = "supplierChange";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{supplierID:supplierID,post_m:post_m},
                    success:function(data){
                      $(".brands").html(data);
                    }
                }); 
            });
            $(document).on('change', '.channels_new', function(){
                
                var channelID = $(this).val();
                
                var post_m = "channelChange";
                $.ajax({
                    method: "post",
                    url:"common/ajax/ajax_data.php",
                    dataType: 'text',
                    data:{channelID:channelID,post_m:post_m},
                    success:function(data){
                      $(".stores_new").html(data);
                    }
                }); 

            });

            $(document).on('click', '.add_prelisting', function(){
                var taskID = $(this).attr('data-id');
                var subID = $(".subID").val();
                var ref_url = $('.ref_url').val();
                var ref_title = $('.ref_title').val();
                var purchasePrice = $('.purchasePrice').val();
                var productCode = $(".productCode").val();
                var channels = $(".channels_new").val();
                var stores = $(".stores_new").val();
                var salePrice = $(".salePrice").val();
                var quantity = $(".quantity").val();
                var storeSKU = $(".storeSKU").val();
                var linkedSKU = $(".linkedSKU").val();
                var EAN = $(".EAN").val();
                var ASIN = $(".ASIN").val();
                var listingType = $(".listingType").val();
                var post_m = "add_prelisting";
                if(channels != "0" && stores == "0" ){
                   swal({
                      title: 'REQUIRED FIELD?',
                      text: "STORE IS REQUIRED, IF YOU SELECT CHANNEL",
                      type: 'question',
                      padding: '2em'
                    });
                }else{
                    var form_data = new FormData();
                    form_data.append("ref_url",ref_url);
                    form_data.append("ref_title",ref_title);
                    form_data.append("taskID",taskID);
                    form_data.append("purchasePrice",purchasePrice);
                    form_data.append("productCode",productCode);
                    form_data.append("channels",channels);
                    form_data.append("stores",stores);
                    form_data.append("salePrice",salePrice);
                    form_data.append("quantity",quantity);
                    form_data.append("storeSKU",storeSKU);
                    form_data.append("linkedSKU",linkedSKU);
                    form_data.append("EAN",EAN);
                    form_data.append("ASIN",ASIN);
                    form_data.append("listingType",listingType);

                    form_data.append("post_m",post_m);

                    $.ajax({
                            method: "post",
                            url:"common/ajax/ajax_data.php",
                            data:form_data,
                            dataType:'text',
                            contentType: false,
                            cache: false,
                            processData:false,
                            success:function(data){
                              
                              swal({
                                title: 'RECORD ADDED!',
                                padding: '2em',
                                type: 'success'
                              }).then(function (result) {
                                location.reload(true);
                              })
                              
                            }
                    });    
                }
            });
            $(document).on('click', '.update_detail_prelst', function(){
                
                var taskID = $('.task_ID').val();
                var taskdetailID = $(this).attr('data-id');
                var ref_url = $('.ref_url_'+taskdetailID).val();
                var ref_title = $('.refTitle_'+taskdetailID).val();
                var purchasePrice = $('.purchasePrice_'+taskdetailID).val();
                var productCode = $(".productCode_"+taskdetailID).val();
                var channel = $("#channel_"+taskdetailID).val();
                var store = $("#store_"+taskdetailID).val();
                var salePrice = $(".salePrice_"+taskdetailID).val();
                var storeSKU = $(".storeSKU_"+taskdetailID).val();
                var linkedSKU = $(".linkedSKU_"+taskdetailID).val();
                var EAN = $(".EAN_"+taskdetailID).val();
                var ASIN = $(".ASIN_"+taskdetailID).val();
                var listingType = $(".listingType_"+taskdetailID).val();
                var quantity = $(".quantity_"+taskdetailID).val();
                
            
                var post_m = "update_detail_prelst";

                var form_data = new FormData();
                form_data.append("ref_url",ref_url);
                form_data.append("ref_title",ref_title);
                form_data.append("taskdetailID",taskdetailID);
                form_data.append("purchasePrice",purchasePrice);
                form_data.append("productCode",productCode);
                form_data.append("channel",channel);
                form_data.append("store",store);
                form_data.append("salePrice",salePrice);

                form_data.append("storeSKU",storeSKU);
                form_data.append("linkedSKU",linkedSKU);
                form_data.append("EAN",EAN);
                form_data.append("ASIN",ASIN);
                form_data.append("listingType",listingType);
                
                form_data.append("quantity",quantity);
                form_data.append("post_m",post_m);
                
                $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        data:form_data,
                        contentType: false,
                        cache: false,
                        processData:false,
                        success:function(data){
                            if(data){
                                swal({
                                  title: 'RECORD UPDATED',
                                  padding: '2em',
                                  type: "success",
                                  timer:2000
                                });
                                var data = $.parseJSON(data); 
                                if(data['type'] == "Parent"){
                                $(".listingType_"+taskdetailID).css('background-color','#4d79ff');
                                $(".listingType_"+taskdetailID).css('color','white');                                    
                                }
                                if(data['type'] == "Child"){
                                $(".listingType_"+taskdetailID).css('background-color','#9999ff');
                                $(".listingType_"+taskdetailID).css('color','white');                                    
                                } 
                                if(data['type'] == "Single"){
                                $(".listingType_"+taskdetailID).css('background-color','#70dbdb');
                                $(".listingType_"+taskdetailID).css('color','#3b3f5c');                                    
                                }
                                $('.ref_url_'+taskdetailID).attr('disabled','disabled');
                                $('.refTitle_'+taskdetailID).attr('disabled','disabled');
                               $('.productCode_'+taskdetailID).attr('disabled','disabled');
                               $('.purchasePrice_'+taskdetailID).attr('disabled','disabled');
                               $('.quantity_'+taskdetailID).attr('disabled','disabled');
                               $('#channel_'+taskdetailID).attr('disabled','disabled');
                               $('#store_'+taskdetailID).attr('disabled','disabled');
                               $('.salePrice_'+taskdetailID).attr('disabled','disabled');
                               $('.storeSKU_'+taskdetailID).attr('disabled','disabled');
                               $('.linkedSKU_'+taskdetailID).attr('disabled','disabled');
                               $('.EAN_'+taskdetailID).attr('disabled','disabled');
                               $('.ASIN_'+taskdetailID).attr('disabled','disabled');
                               $('.listingType_'+taskdetailID).attr('disabled','disabled');
                               $("#clone_"+taskdetailID).show();
                               $("#clone_rem_"+taskdetailID).show();
                               $("#update_detail_prelst_"+taskdetailID).hide();   
                            }
                            

                            // // location.reload(true);
                            // $("#home-tab").removeClass('active');
                            // $("#home").removeClass('in active');
                            // $("#listing-tab").addClass('active');
                            // $("#preListing").addClass('in active');
                        }
                    });   

            });
            $(document).on('click', '.prelst_rem_detail', function(){
                if (confirm('Are you sure?')) {
                    var id = $(this).attr('data-id');
                    var post_m = "prelst_rem_detail";
                    $.ajax({
                        method: "post",
                        url:"common/ajax/ajax_data.php",
                        dataType: 'text',
                        data:{id:id,post_m:post_m},
                        success:function(data){
                          swal({
                            title: 'DELETED!',
                            padding: '2em',
                            type:'success'
                          }).then(function (result) {
                            location.reload(true);
                          })
                        }
                    });
                }
            });
            $('.widget-content #saveANDclose').on('click', function () {
                  swal({
                    title: 'SAVED!',
                    padding: '2em',
                    type: 'success'
                  }).then(function (result) {
                    window.location = "stmtasks.php?opentask";
                  })
            
           });

     //pagination completed

        load_data_compl(1);
        function load_data_compl(page, query = '', category='',created_by='',from_task='',to_task='',status='',priority='',assignees='',skype ='')
        {
          $.ajax({
            url:"common/ajax/stm_completed.php",
            method:"POST",
            data:{page:page,query:query,category:category,created_by:created_by,
              from_task:from_task,to_task:to_task,status:status,priority:priority,assignees:assignees,
              skype:skype},
            success:function(data)
            {
              $('.compTab').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#task_comp_search').val();
          load_data_compl(page, query);
        });

        $(document).on('keyup', '#task_comp_search', function(){
        // $('.task_comp_search').keyup(function(){
          var query = $('#task_comp_search').val();
          load_data_compl(1, query);
        });
        
        $(document).on('click', '#filterBy_task', function(){
          var category = $('#category_task').val();
          var created_by = $('#created_by_task').val();
          var from_task = $('#fromFlatpickr').val();
          var to_task = $('#toFlatpickr').val();
          var status = $('#status').val();
          var priority = $('#priority_task').val();
          var assignees = $('#assignees_filter').val();
          var skype = $('#skype').val();
          var query = $('#task_comp_search').val();
          load_data_compl(1,query,category,created_by,from_task,to_task,status,priority,assignees,skype);
        });
    //pagination users

    load_data_users(1);

        function load_data_users(page, query = '')
        {
          $.ajax({
            url:"common/ajax/stm_users.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.usersTable').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          
          var query = $('#users_search_box').val();
          load_data_users(page, query);
        });

        $('#users_search_box').keyup(function(){
          var query = $('#users_search_box').val();
          load_data_users(1, query);
        });
        //Tasks Pagination
        load_data_tasks(1);
        function load_data_tasks(page, query = '', category='',created_by='',from='',to='',status='',priority='', assignees='',skype='')
        {
          $.ajax({
            url:"common/ajax/stm_tasks.php",
            method:"POST",
            dataType:"text",
            data:{page:page, query:query,category:category,created_by:created_by,
              from:from,to:to,status:status,priority:priority,assignees:assignees,skype:skype},
            success:function(data)
            {
              $('.tasksTable').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#tasks_search_box').val();
          load_data_tasks(page, query);
        });

        $('#tasks_search_box').keyup(function(){
          var query = $('#tasks_search_box').val();
          load_data_tasks(1, query);
        });
        
        $(document).on('click', '#filterBy', function(){
          var category = $('#category').val();
          var created_by = $('#created_by').val();
          var from = $('#fromFlatpickr').val();
          var to = $('#toFlatpickr').val();
          var status = $('#status').val();
          var priority = $('#priority').val();
          var assignees = $('#assignees_filter').val();
          var skype = $('#skype').val();

          var query = $('#tasks_search_box').val();
          load_data_tasks(1,query,category,created_by,from,to,status,priority,assignees,skype);
        });
        //stm_all_opentask Pagination
        all_assignedtome_open(1);
        function all_assignedtome_open(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_assignedtome_open.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_assignedtome_open').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#all_assignedtome_opensearch').val();
          all_assignedtome_open(page, query);
        });

        $('#all_assignedtome_opensearch').keyup(function(){
          var query = $('#all_assignedtome_opensearch').val();
          all_assignedtome_open(1, query);
        });
        
        //stm_all_opentask Pagination
        all_assignedbyme_open(1);
        function all_assignedbyme_open(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_assignedbyme_open.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_assignedbyme_open').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.all_assignedbyme_opensearch').val();
          all_assignedtome_open(page, query);
        });

        $('.all_assignedbyme_opensearch').keyup(function(){
          var query = $('.all_assignedbyme_opensearch').val();
          all_assignedbyme_open(1, query);
        });

        //stm_all_opentask Pagination
        all_forreview_open(1);
        function all_forreview_open(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_forreview_open.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_forreview_open').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.all_forreview_opensearch').val();
          all_forreview_open(page, query);
        });

        $('.all_forreview_opensearch').keyup(function(){
          var query = $('.all_forreview_opensearch').val();
          all_forreview_open(1, query);
        });
        
        //stm_all_closetask Pagination
        all_assignedtome_close(1);
        function all_assignedtome_close(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_assignedtome_close.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_assignedtome_close').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.all_assignedtome_closesearch').val();
          all_assignedtome_close(page, query);
        });

        $('.all_assignedtome_closesearch').keyup(function(){
          var query = $('.all_assignedtome_closesearch').val();
          all_assignedtome_close(1, query);
        });
        
        //stm_all_opentask Pagination
        all_assignedbyme_close(1);
        function all_assignedbyme_close(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_assignedbyme_close.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_assignedbyme_close').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.all_assignedbyme_closesearch').val();
          all_assignedtome_close(page, query);
        });

        $('.all_assignedbyme_closesearch').keyup(function(){
          var query = $('.all_assignedbyme_closesearch').val();
          all_assignedbyme_close(1, query);
        });

        //stm_all_opentask Pagination
        all_forreview_close(1);
        function all_forreview_close(page, query = '')
        {
          $.ajax({
            url:"common/ajax/all_forreview_close.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.all_forreview_close').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.all_forreview_closesearch').val();
          all_forreview_close(page, query);
        });

        $('.all_forreview_closesearch').keyup(function(){
          var query = $('.all_closereview_opensearch').val();
          all_forreview_open(1, query);
        });


        //User Tasks Pagination
        load_data_newtasks(1);

        function load_data_newtasks(page, query = '')
        {
          $.ajax({
            url:"common/ajax/stm_user_tasks.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.userTasksTable').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#tasks_search_box').val();
          load_data_newtasks(page, query);
        });

        $('#tasks_search_box').keyup(function(){
          var query = $('#tasks_search_box').val();
          load_data_newtasks(1, query);
        });
        // $('.status').change(function(){
        //   var query = $('.status').val();
        //   load_data_tasks(1, query);
        // });  

         //User Tasks Assigned by me
        load_data_assignedByme(1);

        function load_data_assignedByme(page, query = '')
        {
          $.ajax({
            url:"common/ajax/stm_userme_tasks.php",
            method:"POST",
            data:{page:page, query:query},
            success:function(data)
            {
              $('.taskMain').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#tasks_search_box').val();
          load_data_assignedByme(page, query);
        });

        $('#tasks_search_box').keyup(function(){
          var query = $('#tasks_search_box').val();
          load_data_assignedByme(1, query);
        });

        //User Tasks as supervisor
        load_data_supervisor(1);

        function load_data_supervisor(page, query = '')
        {
          $.ajax({
            url:"common/ajax/stm_supervisor.php",
            method:"POST",
            data:{page:page,query:query},
            success:function(data)
            {
              $('.super_Table').html(data);
            }
          });
        }

        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('#supervisr_search').val();
          load_data_supervisor(page, query);
        });
        $(document).on('keyup', '#supervisr_search', function(){
          var query = $('#supervisr_search').val();
          load_data_supervisor(1, query);
        });

        load_data_files(1);

        function load_data_files(page,query = '')
        {
          $.ajax({
            url:"common/ajax/stm_files.php",
            method:"POST",
            data:{page:page,query:query},
            success:function(data)
            {
              $('.directory').html(data);
            }
          });
        }
        $(document).on('click', '.page-link', function(){
          var page = $(this).data('page_number');
          var query = $('.directory').val();
          load_data_files(page, query);
        });
        $(document).on('keyup', '.searchFile', function(){
          var query = $('.searchFile').val();
          load_data_files(1,query);
        });
});
//Channel Listing Data
$(document).ready(function(){

  load_channel_listing(1);

  function load_channel_listing(page, query = '', supplier = '')
  {
    $.ajax({
      url:"common/ajax/channelListingajax.php",
      method:"POST",
      data:{page:page, query:query, supplier:supplier},
      beforeSend: function(){
        var html = "";        
        html += '<div id="imageloading" style="margin-left:34%; margin-top:5%;"><img src="images/loading-icon.gif" height="150" width="150"><br><h4 style="margin-left:7%;">Loading...</h3></div>';
        $(".channellisting").html(html);
      },
      success:function(data)
      {
        $('.channellisting').html(data);
      }
    });
  }

  $(document).on('click', '.page-link', function(){
    var page = $(this).data('page_number');
    var query = $("#search_listing").val();
    var supplier = $(".suppliers").val();
    load_channel_listing(page,query,supplier);
  });

  $(document).on('change', '.suppliers', function(){
    var supplier = $(this).val();   
    load_channel_listing(1,query = '',supplier);
  });
  
  $(document).on('keyup', '#search_listing', function(){
    
    var query = $(this).val();

    load_channel_listing(1, query,supplier = '');

  });

});

$(document).ready(function(){
  load_inventory1(1);
  function load_inventory1(page, supplier = '', inv='')
  {
    $.ajax({
      url:"common/ajax/load_inventory.php",
      method:"POST",
      dataType:"text",
      data:{page:page, supplier:supplier, inv:inv},
      beforeSend: function(){
        var html = "";        
        html += '<div id="imageloading" style="margin-left:34%; margin-top:5%;"><img src="images/loading-icon.gif" height="150" width="150"><br><h4 style="margin-left:7%;">Loading...</h3></div>';
        $(".inventory").html(html);
      },
      success:function(data)
      {
        $('.inventory').html(data);
      }
    });
  }

  $(document).on('click', '.page-link', function(){
    var page = $(this).data('page_number');
    var supplier = $('.suppliers').val();
    var inv = $("#inv").val();
    load_inventory1(page,supplier,inv);
  });

  $(document).on('change', '.suppliers', function(){
    var supplier = $(this).val();   
    load_inventory1(1,supplier,inv = '');
  });

  $(document).on('keyup', '#inv', function(){
    var inv = $(this).val();   
    load_inventory1(1,supplier = '',inv);
  });
});
$(document).ready(function(){
  load_inventory1(1);
  function load_inventory1(page, supplier = '', inv='')
  {
    $.ajax({
      url:"common/ajax/load_inv_test.php",
      method:"POST",
      dataType:"text",
      data:{page:page, supplier:supplier, inv:inv},
      beforeSend: function(){
        var html = "";        
        html += '<div id="imageloading" style="margin-left:34%; margin-top:5%;"><img src="images/loading-icon.gif" height="150" width="150"><br><h4 style="margin-left:7%;">Loading...</h3></div>';
        $(".inventory").html(html);
      },
      success:function(data)
      {
        $('.inventory1').html(data);
      }
    });
  }

  $(document).on('click', '.page-link', function(){
    var page = $(this).data('page_number');
    var supplier = $('.suppliers').val();
    var inv = $("#inv").val();
    load_inventory1(page,supplier,inv);
  });

  $(document).on('change', '.suppliers', function(){
    var supplier = $(this).val();   
    load_inventory1(1,supplier,inv = '');
  });

  $(document).on('keyup', '#inv', function(){
    var inv = $(this).val();   
    load_inventory1(1,supplier = '',inv);
  });
});