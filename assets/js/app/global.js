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
            },
            initDataTable : function($table){
                $($table).DataTable();
        }, 
        repsonseError : function(data,element='body'){
                if(data.error.reauth !== undefined){
                        //Reauth Logic.
                        $("#tweetModal").modal("hide");
                        $(".navbar").hide();
                        $("<script>").text(data.error.reauth).appendTo("body");           
                   }else{
                         //Simple Error.
                         globalMethod.showNotification('danger','top','right',data.error[0],element,100000);
                   }      
        },
        clearInput : function () {
                $('form').find('input[type=text], input[type=password], input[type=number], input[type=email], input[type=file] ,textarea').val('');
        },
        onPageLoad : function(){
                //Spinner Here!.
        },
        copyTweet : function($selector){
                $($selector).on("click",function(event){
                        event.preventDefault();
                        //Init the editor text.
                        globalMethod.clearInput();
                        $('.emojionearea-editor').text('');        
                        //Open Modal.
                        $key = $(this).data("key");
                        $content_to_share = $("#"+$key).val();
                        $(".emojionearea-editor").text($content_to_share);
                        //Focus on editor emojionarea.
                        $(".emojionearea-editor").focus();
                        $("#tweetModal").modal();                      

                });
        },
        share : function($selector){
              //Share Logic Here.  
        },
        seshatView : function($selector,$url){
                /*$($selector).on("click",function(event){
                        event.preventDefault();
                        $tweet_id = $(this).data("tweet-id");
                        $screenName = $(this).data("screen-name");
                        $($selector).attr("disabled",true);
                        $.ajax({
                             "url":$url,
                             "type":"get",
                             "dataType":"json",
                             "data":"tweet_id="+$tweet_id+"&seshatView=true&screenName="+$screenName,
                             "success":function(data){
                                        if(data.seshatView != undefined){
                                                $("<script>").text(data.seshatView).appendTo("body");
                                                globalMethod.initTable("#seshatTable");
                                                         
                                        }else{
                                                alert("Something wrong ..");
                                        }
                                        $($selector).attr("disabled",false);
                             }   
                        });

                });*/
        },seshatAnalytic : function($chartSelector , $analtyicData){
            $labels = [];
            $analtyicData.forEach(function(element,index){
                        $labels[index] = element + "%";
            });    
            var dataPreferences = {
                labels: $labels,
                series: $analtyicData
            };

            var optionsPreferences = {
                height: '230px'
            };
            Chartist.Pie($chartSelector, dataPreferences, optionsPreferences);

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
        });

        //reset modal after user close it.
        $('#composeTweet').on('click', function(){
                globalMethod.clearInput();
                $('.emojionearea-editor').empty();
        });
        //Check The Vaildation Of The Form.
        $.validator.messages.extension = '';
        $.validator.messages.accept = '';
        $("#composeTweetForm").validate({
                rules:{
                        tweetContent: {
                                required: true,
                                minLength : 1,
                                maxLength : 280
                        },
                        tweetMedia:{
                                extension : "jpg|jpeg|png|JPG|JPEG|PNG"
                        }
                }           
        });

        //Share button.
        globalMethod.copyTweet(".tweetThis");
        //End Share Button.

        //seshat analytic view.
        globalMethod.seshatAnalytic("#tweetStatics",[$("#rt_precent").val(),$("#like_precent").val()]);
        //End seshat analytic v view.

          
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
                                        //Only One Error Come To This Not Need Any Array.
                                        if(data.error != undefined){
                                                globalMethod.repsonseError(data,'#tweetModal');
                                        }else if (data.success != undefined){
                                                //Success.
                                                $("#tweetModal").modal("hide");
                                                globalMethod.showNotification('success','top','right',data.success,'body',3000);
                                        }
                                }
                        });
                                
                }
                
        });

        //Quick replay same logic as compose tweet in global.js File.
        $(".quickReplayForm").on('submit',function(event){
                event.preventDefault();
                $(".quickReplayButton").attr("disabled",true);
                
                $.ajax({
                    "url": twitterActionUrl+"composeTweet",
                    "type": "POST",            
                    "data": new FormData(this),
                    "contentType": false,
                    "dataType":"json",
                    "cache": false,
                    "processData":false,                    
                    "success": function(data)  {
                        //Only One Error Come To This Not Need Any Array.
                        if(data.error != undefined){
                                globalMethod.repsonseError(data);
                        }else if (data.success != undefined){
                                //Success.
                                globalMethod.showNotification('success','top','right',data.success,'body',3000);
                        }
                            //Re-enable quickButton.
                            $(".quickReplayButton").attr("disabled",false);
                        }
                    });
        });

        //End Quick replay.

        /**
         * fancy Library Settings.
         */
        $(".fancybox").fancybox({
                buttons : [
                        'slideShow',
                        'fullScreen',
                        'thumbs',
                        'share',
                        'download',
                        'zoom',
                        'close'
                ]                
        });
      
        
        /**
         * emo Library Box for Quick Replay && Tweet Compose.
         */
        $(".quickReplay").emojioneArea({
                pickerPosition: "bottom"
        });
});