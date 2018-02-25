<?php
        namespace App\DomainHelper;



        /**
         * This Class Provide All Static Function That App Need For FrontEnd Like Create Modal For Specific Condation.
         */

        Class FrontEndHelper 
        {
             /**
              * @method reauthUserModal Resonsaible For Return Model Style For User To Authincate With Twitter Again.
              * @param url&&msg
              */
              public static function reauthUserModal(string $url,string $msg){
                $re_oauth = REAOUTH_SESHAT;
                return <<<MODAL
$(".navbar").hide();                   
$.sweetModal({
content:"$msg<br><a href='$url' class='btn btn-block btn-social btn-twitter'>$re_oauth</a>",
theme: $.sweetModal.THEME_DARK,
showCloseButton: false,
blocking:true	
});                                     
MODAL;
            }
            /**
             * @method notify responsable to give user notification in specific time.
             */
            public static function notify(array $messages,string $location = "top",string $direction = "right",string $append ="body",string $type="danger",int $timeOut = 100000){
                $return = [];   
                foreach ($messages as $key => $msg) {
                        $return[] = "globalMethod.showNotification('$type','$location','$direction','$msg','$append',$timeOut);";
                }
                return $return;
            }

        }