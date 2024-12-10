<!DOCTYPE html>
<html lang="en">
<head>
  <?=$head?>
</head>
<body>
  <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/hs.theme-appearance.js"></script>
  <section id="about">
      <div class="container" data-tm-padding-bottom="220px">
            <h4><?=(($page)?$page->page_name:'')?></h4>
         <div class="section-content">
            <div class="row">
               <div class="col-lg-12 col-xl-12 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.1s">
                  <div class="about-text-content mb-md-30">
                     <?=(($page)?$page->page_content:'')?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
  <!-- JS Implementing Plugins -->
  <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/vendor.min.js"></script>
  <!-- JS Front -->
  <script src="<?=env('ADMIN_ASSETS_URL')?>assets/js/theme.min.js"></script>
  <!-- JS Plugins Init. -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    (function() {
      window.onload = function () {
        // INITIALIZATION OF BOOTSTRAP VALIDATION
        // =======================================================
        HSBsValidation.init('.js-validate', {
          onSubmit: data => {
            data.event.preventDefault()
            alert('Submited')
          }
        })
        // INITIALIZATION OF TOGGLE PASSWORD
        // =======================================================
        new HSTogglePassword('.js-toggle-password')
      }
    })()
    $(function(){
      $('.autohide').delay(5000).fadeOut('slow');
    })
    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
    }
    $(document).ready(function() {
      $('.otp-input').on('keyup', function(e) {
          var key = e.which || e.keyCode;
          if (key >= 48 && key <= 57) { // Only allow numeric keys
              $(this).next('.otp-input').focus();
          } else if (key === 8) { // Handle backspace
              $(this).prev('.otp-input').focus();
          }
      });

      $('.otp-input').on('input', function() {
          if (this.value.length > 1) {
              this.value = this.value.slice(0, 1);
          }
      });

      $('.otp-input').on('paste', function(e) {
          var pasteData = (e.originalEvent || e).clipboardData.getData('text/plain');
          if (!isNaN(pasteData) && pasteData.length === 6) {
              var inputs = $('.otp-input');
              for (var i = 0; i < pasteData.length; i++) {
                  $(inputs[i]).val(pasteData[i]);
              }
              e.preventDefault();
              inputs.last().focus();
          }
      });
    });
  </script>
</body>
</html>