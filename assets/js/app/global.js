var globalMethod = {
        //type => ['info', 'success', 'warning', 'danger', 'rose', 'primary']
        showNotification: function(type, from, align, msg,element,timer) {
                $.notify({
                    icon: "notifications",
                    message: msg
                }, {
                    type: type,
                    element: element,
                    timer: timer,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            }        
};



var twitterActionUrl = "http://127.0.0.1/seshat/!twitterAction/";
$(document).ready(function(){
        //By Default Disabled Buttons.
        $("#scheduleButton").attr("disabled",true);
        //Init, datetimepicker.
        $('#datetimepicker').parent().css('position', 'relative');
        $("#datetimepicker").datetimepicker({
            minDate : new Date(),
            stepping : 5
        });

        //Check The Vaildation Of The Form.
        $.validator.messages.extension = '';
        $.validator.messages.accept = '';
        $("#composeTweetForm").validate({
                rules:{
                        tweetContent: {
                                required: true
                        },
                        tweetMedia:{
                                extension : "jpg|jpeg|png|JPG|JPEG|PNG"
                        }
                }           
        });

        
          
        $("#datetimepicker").on('dp.change',function(){
                $scheduleDate = $("#scehduleTime").val();
                if(Date.parse($scheduleDate)-Date.parse(new Date())  > 0){
                        //enable scheduleButton.
                        $("#scheduleButton").attr("disabled",false);
                }else{
                        $("#scheduleButton").attr("disabled",true);
                }
        });

        $("#scheduleButton").click(function(event){
                //Remove Old hidden If Exists.
                $("#tweetType").remove();
                $("<input>").attr("type","hidden").attr("id","tweetType").attr("name","schedule").attr("value","true").appendTo("#composeTweetForm");
        });

        $("#publishNow").click(function(event){
                $("#tweetType").remove();
                $("<input>").attr("type","hidden").attr("id","tweetType").attr("name","publish").attr("value","true").appendTo("#composeTweetForm");        
        });

        $("#composeTweetForm").on("submit",function(event){
                event.preventDefault();
                if($("#composeTweetForm").valid()){
                        /**
                         * 1 - Loading Style Must Be Here !
                         * 2-  Send Ajax Request To Publish The Content.
                         */
                        $.ajax({
                                "url": twitterActionUrl+"composeTweet",
                                "type": "POST",            
                                "data": new FormData(this),
                                "contentType": false,
                                "dataType":"json",
                                "cache": false,
                                "processData":false,                    
                                "success": function(data)  { 
                                        console.log(data);
                                        //Only One Error Come To This Not Need Any Array.
                                        if(data.error != undefined){
                                                if(data.error.reauth !== undefined){
                                                     //Reauth Logic.
                                                     $("#tweetModal").modal("hide");
                                                     $(".navbar").hide();
                                                     $("<script>").text(data.error.reauth).appendTo("body");           
                                                }else{
                                                      //Simple Error.
                                                      globalMethod.showNotification('danger','top','right',data.error[0],'#tweetModal',100000);
                                                }
                                        }else if (data.success != undefined){
                                                   //Success.
                                                   $("#tweetModal").modal("hide");
                                                   globalMethod.showNotification('success','top','right',data.success,'body',3000);
                                        }
                                }
                        });
                                
                }
                
        });
});