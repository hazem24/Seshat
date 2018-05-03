

<!-- Core JS Files -->

<script src="<?=ASSESTS_URI."js"?>/lib/jquery-3.2.1.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/jquery-ui-1.12.1.custom.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/popper.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/jquery.validate.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>


<!-- Switches -->
<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap-switch.min.js"></script>

<!--  Plugins for Slider -->
<script src="<?=ASSESTS_URI."js"?>/lib/nouislider.js"></script>

<!--  Photoswipe files -->
<script src="<?=ASSESTS_URI."js"?>/lib/photo_swipe/photoswipe.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/photo_swipe/photoswipe-ui-default.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/photo_swipe/init-gallery.js"></script>

<!--  Plugins for Select -->

<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap-select.js"></script>

<!--  for fileupload -->
<script src="<?=ASSESTS_URI."js"?>/lib/jasny.min.js"></script>
<!--  Plugins for Tags -->
<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap-tagsinput.js"></script>
<!--  Plugins for DateTimePicker -->
<script src="//momentjs.com/downloads/moment.js"></script>
<script type="text/javascript" src="<?=ASSESTS_URI."js"?>/lib/bootstrap-datetimepicker.min.js"></script>
<!-- charlist app -->
<script src="<?=ASSESTS_URI."js"?>/lib/chartist.min.js"></script>

<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap-notify.js" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/paper-kit.js?v=2.1.0"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/sweetAlert/jquery.sweet-modal.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/afterglowplayer@1.1"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="<?=ASSESTS_URI."js"?>/lib/emojionearea.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/global.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/app/writeToTwitter.js"></script>

<?php

//Check If User Need Reauthincate or any Error Exists.
if(isset($error) && is_object($error) === false && is_array($error) && !empty($error)):
?>
<script>
<?php  
  
    foreach ($error['error'] as $key => $value) :  
?>

    <?=$value;?>

<?php
    endforeach;
?>
</script>
<?php    
endif;
?>

</html>
    