var src = "/seshat/assets/spinner/";
var spinner = {
            button : function($selector,$spinner_to_load = '',$disabled = true , $html = false){
                    //button spinner here.
                    $($selector).attr("disabled",$disabled);
                    if($html === false){
                        $button_text = $($selector).text();
                    }else {
                        $button_text = $($selector).html();
                    }
                    $($selector).html($spinner_to_load + $button_text);
            },remove : function ($selector,$text='',$disabled = false , $html){ // Used For Buttons.
                    //Remove spinner after Request End. 
                    $($selector).html(''); 
                    if($html === false){
                        $button_text = $($selector).text($text);
                    }else {
                        $button_text = $($selector).html($text);
                    }
                    //return back the button.
                    $($selector).attr("disabled",$disabled);
            },onPageLoad : function(body = false){
                if(body === true){
                    //At body Only.
                    $("body").append('<div style="padding:10px;margin: auto;top:30%;left:40%;position:fixed;" class="lds-css ng-scope spinner"><div style="width:100%;height:100%" class="lds-magnify"><div><div><div></div><div></div></div></div></div><style type="text/css">@keyframes lds-magnify{0%{-webkit-transform:translate(2px,2px);transform:translate(2px,2px)}33.33%{-webkit-transform:translate(102px,2px);transform:translate(102px,2px)}66.66%{-webkit-transform:translate(42px,102px);transform:translate(42px,102px)}100%{-webkit-transform:translate(2px, 2px);transform:translate(2px, 2px)}}@-webkit-keyframes lds-magnify{0%{-webkit-transform:translate(2px,2px);transform:translate(2px,2px)}33.33%{-webkit-transform:translate(102px,2px);transform:translate(102px,2px)}66.66%{-webkit-transform:translate(42px,102px);transform:translate(42px,102px)}100%{-webkit-transform:translate(2px,2px);transform:translate(2px,2px)}}.lds-magnify{position:relative}.lds-magnify>div{-webkit-transform:scale(0.8);transform:scale(0.8);-webkit-transform-origin:100px 100px;transform-origin:100px 100px}.lds-magnify>div>div{-webkit-animation:lds-magnify 1s linear infinite;animation:lds-magnify 1s linear infinite;position:absolute}.lds-magnify > div > div div:nth-child(1){width:96px;height:96px;border-radius:50%;border:12px solid #302323;background:#f0f5f6}.lds-magnify > div > div div:nth-child(2){width:17px;height:51px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);background:#302323;border-radius:0 0 8px 8px;position:absolute;top:68px;left:85px}.lds-magnify{width:200px !important;height:200px !important;-webkit-transform:translate(-100px, -100px) scale(1) translate(100px, 100px);transform:translate(-100px, -100px) scale(1) translate(100px, 100px)}</style></div>');
                }
            },removeSpinner(spinner){//Used For Any Element.
                    $(spinner).html('').remove();
            },
};