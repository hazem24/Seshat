var src = "/seshat/assets/spinner/";
var spinner = {
            button : function($selector,$spinner_to_load = '',$disabled = true){
                    //button spinner here.
                    $($selector).attr("disabled",$disabled);
                    $button_text = $($selector).text();
                    $($selector).html($spinner_to_load + $button_text);
            },remove : function ($selector,$text='',$disabled = false){
                    //Remove spinner after Request End. 
                    $($selector).html(''); 
                    //Return back the text.
                    $($selector).text($text);  
                    //return back the button.
                    $($selector).attr("disabled",$disabled);
            }
};