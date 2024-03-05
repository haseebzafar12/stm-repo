        <!-- <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="terms-conditions">© 2022 All Rights Reserved.<br><a href="https://swiftitsol.net/stm/">SWIFT TASK MANAGEMENT.</a></p>
                </div>
                
            </div>
        </div> -->
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->       
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<!-- END GLOBAL MANDATORY SCRIPTS -->
     <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
     <script src="https://cdn.ckeditor.com/4.16.1/standard/ckeditor.js"></script>
     <script src="assets/js/authentication/form-1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="plugins/sweetalerts/sweetalert2.min.js"></script>
    <script src="plugins/sweetalerts/custom-sweetalert.js"></script>
    <script src="plugins/flatpickr/flatpickr.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/apps/mailbox-chat.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="assets/js/dashboard/dash_1.js"></script>
    <!-- <script src="plugins/editors/quill/quill.js"></script>
    <script src="plugins/editors/quill/custom-quill.js"></script> -->
      
    <script src="assets/scripts.js"></script>
      
    <script src="assets/js/scrollspyNav.js"></script>
    <script src="plugins/bootstrap-maxlength/bootstrap-maxlength.js"></script>
    <script src="plugins/bootstrap-maxlength/custom-bs-maxlength.js"></script>
    <script src="plugins/file-upload/file-upload-with-preview.min.js"></script>
    <script src="plugins/select2/select2.min.js"></script>
    <script src="plugins/select2/custom-select2.js"></script>
    <script src="assets/js/components/ui-accordions.js"></script>
    
    <script>
        var firstUpload = new FileUploadWithPreview('myFirstImage');
        
         function myFunction3(id){
            var el = document.getElementById('clone_'+id);
            var clone_rem = document.getElementById('clone_rem_'+id);
            el.style.display = "inline-block";
            clone_rem.style.display = "inline-block";
         }
         function myFunction4(id){
            var el = document.getElementById('update_clone_'+id);
            var clone_rem = document.getElementById('rem_clone_'+id);
            el.style.display = "inline-block";
            clone_rem.style.display = "inline-block";
         }
         function function5(id){
            var el = document.getElementById('update_clone_'+id);
            var clone_rem = document.getElementById('rem_clone_'+id);
            el.style.display = "inline-block";
            clone_rem.style.display = "inline-block";
         }
        function myFunction1(){
          
          var el = document.getElementById('clone_row');
          var ref_val = document.getElementById('ref_url').value;
          var code_val = document.getElementById('productCode').value;
          var pur_val = document.getElementById('purchasePrice').value;
          var qty_val = document.getElementById('quantity').value;
          var storeSKU = document.getElementById('storeSKU').value;
          var linkedSKU = document.getElementById('linkedSKU').value;
          
          var ASIN = document.getElementById('ASIN').value;

          if(ref_val != "" || code_val != "" || pur_val != "" || qty_val != "" || storeSKU != "" || linkedSKU != "" || ASIN != ""){
                el.style.display = "block";
            }else if(ref_val == "" || code_val == "" || pur_val == "" || qty_val == "" || storeSKU == "" || linkedSKU == "" || EAN == "" || ASIN == ""){

                el.style.display = "none";
                
            }
        }    
        function myFunction(){
            var el = document.getElementById('clone_row');
            var saveANDclose = document.getElementById('saveANDclose');
            var ref_val = document.getElementById('ref_url').value;
            var code_val = document.getElementById('productCode').value;
            var pur_val = document.getElementById('purchasePrice').value;
            var qty_val = document.getElementById('quantity').value;

            var amzPrice = document.getElementById('amzPrice').value;
            var EAN = document.getElementById('EAN').value;
            var ASIN = document.getElementById('ASIN').value;

            if(ref_val != "" || code_val != "" || pur_val != "" || qty_val != "" || amzPrice != "" || EAN != "" || ASIN != ""){
                el.style.display = "block";
                saveANDclose.style.display = "none";
            }else if(ref_val == "" || code_val == "" || pur_val == "" || qty_val == "" || amzPrice == "" || EAN == "" || ASIN == ""){
                el.style.display = "none";
                saveANDclose.style.display = "block";
            }
            
        }
       
        
        </script>
    </body>

</html>