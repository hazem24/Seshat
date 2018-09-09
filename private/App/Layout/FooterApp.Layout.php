
</body>
<!-- Core JS Files -->

<script src="<?=ASSESTS_URI."js"?>/lib/jquery-3.2.1.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/jquery-ui-1.12.1.custom.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/popper.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/html2canvas.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/iziToast.min.js" type="text/javascript"></script>
<script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.9/angular-animate.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.6/angular-route.min.js"></script>
<script src="//cdn.rawgit.com/eligrey/FileSaver.js/e9d941381475b5df8b7d7691013401e171014e89/FileSaver.min.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/exporterJson.js" type="text/javascript"></script>


<!-- Switches -->
<script src="<?=ASSESTS_URI."js"?>/lib/bootstrap-switch.min.js"></script>

<!--  Plugins for Slider -->
<script src="<?=ASSESTS_URI."js"?>/lib/nouislider.js"></script>


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


<script src="<?=ASSESTS_URI."js"?>/lib/jquery.typetype.min.js"></script>

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/now-ui-kit.js"></script>
<script src="<?=ASSESTS_URI."js"?>/lib/sweetAlert/jquery.sweet-modal.min.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/npm/afterglowplayer@1.x"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="<?=ASSESTS_URI."js"?>/lib/emojionearea.min.js"></script>
<!-- Angular section -->
<script src="<?=ASSESTS_URI."js"?>/app/modules/app.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/reports/report-controller-services.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/reports/hashtag/hashtag-report.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/shared/twitter/global-twitter-controller.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/profile/twitter/twitter-profile.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/profile/profile-controller-services.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/search/search-app.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/seshat/seshat.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/modules/followTree/follow-tree.js"></script>

<!-- End Angular Section. -->
<script src="<?=ASSESTS_URI."js"?>/app/lang/en.lang.js"></script>
<script src="<?=ASSESTS_URI."js"?>/app/loadingSpinner.js"></script>
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
    