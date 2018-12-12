var twitterActionUrl = "http://127.0.0.1/seshat/!twitterAction/";
var extraFeatures    = "http://127.0.0.1/seshat/!extraFeatures/";
var globalMethod = {
        //type => ['info', 'success', 'warning', 'danger', 'rose', 'primary']
        showNotification: function(type, from, align, msg,element,timer) { 
              switch (type.toLowerCase()) {
                      case 'danger':
                        iziToast.error({
                                message: msg,
                                position:from+align,
                                timeout : timer
                        });
                                break;
                        case 'success':
                        iziToast.success({
                                message: msg,
                                position:from+align,
                                timeout:timer
                        });
                                break;
                        case 'question':
                                break;
                      default:
                              break;
              } 
        },initDataTable : function($table){
                $($table).DataTable();
        },
        destoryNotify : function (){
                iziToast.destroy();//destory the izi toast.
        },repsonseError : function(data, element='body'){
                if(data.error.reauth !== undefined ){
                        //Reauth Logic.
                        $(".tweetModal").modal("hide");
                        $(".navbar").hide();
                        $("<script>").text(data.error.reauth).appendTo("body");           
                }else if (data.AppError !== undefined){
                        //AppError.   
                        globalMethod.showNotification('danger','top','Right',data.AppError,element,20000);
                }else{
                        //Simple Error (loop can be presented here to loop through all errors). 
                        globalMethod.showNotification('danger','top','Right',data.error[0],element,20000);
                }      
        },
        clearInput : function () {
                $('form').find('input[type=text], input[type=password], input[type=number], input[type=email], input[type=file] ,textarea').val('');
        },
        createTree : function ( $element , $data ) {
                  var nodes = [];
                  var edges = [];
                  for (var $index in $data) {                        
                        nodes[$index] = { id:$index , shape:'circularImage' , image:"http://127.0.0.1/seshat/assets/img/seshat.png" , label:'@'+$data[$index].subscriber , color: {highlight: {border: '#2B7CE9',background: '#D2E5FF'}} ,font: {color: 'red' , size: 14}};
                        edges[$index] = {from:parseInt($index), to:parseInt($index) + 1};// create connections between people
                  }         
                  // create a network
                  var container = document.getElementById($element);
                  var data = {
                    nodes: nodes,
                    edges: edges
                  };
                  
                  var options = {
                    height: '800px',
                    nodes: {
                      borderWidth:4,
                      size:30,
                      color: {
                        border: '#222222',
                        background: '#666666'
                      },
                      font:{color:'#eeeeee'}
                    },
                    edges: {
                      color: 'lightgray'
                    }
                  };
                  network = new vis.Network(container, data, options);
        },
        navLocation : function ($navLocation){
                $navLocation = $navLocation.toLowerCase();
                //disabled The location which user stop at it.
                if($navLocation.indexOf("!seshattimeline") > 0){
                        $("#yourTimeline").remove();
                }else if ($navLocation.indexOf("yourTimeline") > 0){
                        //Your Profile Here.
                }

        },copyTweet : function(element){
                //Init the editor text.
                globalMethod.clearInput();
                $('.emojionearea-editor').first().text('');        
                //Open Modal.
                $key = $(element).data("key");
                $content_to_share = $("#"+$key).val();
                $(".tweetModal").modal();
                $(".emojionearea-editor").first().focus();
                $('.emojionearea-editor').first().typetype($content_to_share);
                //Focus on editor emojionarea.
        },
        shareContent : function($content){
              //Share Logic Here.
              //Init the editor text.
                globalMethod.clearInput();
                $('.emojionearea-editor').first().text('');        
                //Open Modal.
                $(".tweetModal").modal();
                $(".emojionearea-editor").first().focus();
                $('.emojionearea-editor').first().typetype($content);
                //Focus on editor emojionarea.
        },
        screenShot :  function ($selector){
                html2canvas(document.querySelector($selector), {
                        onrendered: function (canvas) {
                          let pngUrl = canvas.toDataURL();
                          let img = document.querySelector(".screen"); //Not Ready .screen not found.
                          img.src = pngUrl;
                          // here you can send 'pngUrl' to server
                          console.log(img.src);
                          return img.src;
                        },
                });                    
        },
        translateTweet : function ($selector){
              //Translate Logic.
                        /**
                         * 1 - create modal.--Done.
                         * 2 - Add content style.--Done.
                         * 3 - Ajax .. To Be Contianed.
                         */
                        $from = $selector.data("from");
                        $.sweetModal({
                                title   : '<div style="overflow:hidden;width:auto;height:auto;"><button value="en" class="btn btn-info translateTo">English</button> <button value="ru" class="btn btn-info translateTo">русский язык</button> <button value="tr" class="btn btn-info translateTo">Türkçe</button> <button value="de"class="btn btn-info translateTo">Deutsch</button> <button value="es"class="btn btn-info translateTo">Español</button> <button value="fr"class="btn btn-info translateTo">Français</button> <button value="ar" class="btn btn-info translateTo">العربية</button></div>',
                                content : '<div style="float:left;width: 100%;padding: 20px;"><form><textarea id="translated_tweet" data-org_tweet="'+$selector.val()+'" class="form-control border-input"  rows="3"></textarea><button type="button" id="tweet_translated_tweeta" style=" margin-top:10px;width: 50%;padding-top: 5px;" class="btn btn-danger" disabled>'+$tweet_translated_tweet_lang+'</button></div>',
                                theme: $.sweetModal.THEME_DARK,
                        });
                        //init Emoj.
                        globalMethod.initEmoj("#translated_tweet");

                        //Ajax Button for tweet translated tweeta.
                        $("#tweet_translated_tweeta").on("click",function(event){
                                event.preventDefault();
                                $button_clicked = this;
                                $button_text = $($button_clicked).text();
                                spinner.button($button_clicked,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');
                                //send Ajax to tweet this tweet.
                                $.ajax({
                                        "url": twitterActionUrl+"composeTweet",
                                        "type": "POST",            
                                        "data": "publish=true&tweetContent="+$(".emojionearea-editor").last().text(),
                                        "dataType":"json",
                                        "success": function(data)  { 
                                                spinner.remove($button_clicked,$button_text);
                                                //Only One Error Come To This Not Need Any Array.
                                                if(data.error != undefined){
                                                        globalMethod.repsonseError(data);
                                                }else if (data.success != undefined){
                                                        //Success.
                                                        globalMethod.showNotification('success','top','Right',data.success,'body',3000);
                                                }
                                        }
                                });


                        });

                        //Ajax Button for choose lang to translate to it and send request to server and get the translated response.
                        $(".translateTo").click(function(event){
                                event.preventDefault();
                                
                                $(".translateTo").attr("disabled",true);
                                $button_clicked = this;
                                $button_text = $($button_clicked).text();
                                //ajaxSpinner Must Be Here.
                                spinner.button($button_clicked,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');
                                $.ajax({
                                        "url":extraFeatures+'translate',
                                        "type":"POST",
                                        "dataType":"json",
                                        "data":"content_to_translate="+$("#translated_tweet").data("org_tweet")+"&to="+$($button_clicked).val()+"&from="+$from,
                                        "success":function(data){
                                               spinner.remove($button_clicked,$button_text); 
                                               if(data.error != undefined){
                                                        globalMethod.repsonseError(data);
                                               }else if (data.translated_content != undefined){
                                                        $(".emojionearea-editor").text(data.translated_content);
                                                        $("#tweet_translated_tweeta").attr("disabled",false);
                                               }
                                               //enable buttons.
                                               $('.translateTo').attr("disabled",false);
                                        } 
                                });
                        });
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

        },clearStorage : function (){
                localStorage.clear();  
        },initEmoj : function($text_areaSelector){
                $($text_areaSelector).emojioneArea({
                        pickerPosition: "bottom"
                });        
        }          
};
//Nav Location.
globalMethod.navLocation(String(document.location));
//End Nav Location.
$(document).ready(function(){
        //Remove spinner when page loaded.
        spinner.removeSpinner('.ai-spinner');
        //By Default Disabled Buttons.
        $("#scheduleButton").attr("disabled",true);
        //Init, datetimepicker.
        $('#datetimepicker').parent().css('position', 'relative');
        $("#datetimepicker").datetimepicker({
            minDate : new Date(),
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }        
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
        //globalMethod.copyTweet();
        //End Share Button.

        //seshat analytic view.
        //globalMethod.seshatAnalytic("#tweetStatics",[$("#rt_precent").val(),$("#like_precent").val()]);
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
                        $publish_now_button = $("#publishNow");
                        $publish_button_text = $publish_now_button.text();

                        $schedule_button    = $("#scheduleButton");
                        $schedule_button_text = $schedule_button.text();

                        spinner.button($publish_now_button,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');
                        spinner.button($schedule_button,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');

                        $.ajax({
                                "url": twitterActionUrl+"composeTweet",
                                "type": "POST",            
                                "data": new FormData(this),
                                "contentType": false,
                                "dataType":"json",
                                "cache": false,
                                "processData":false,                    
                                "success": function(data)  { 
                                        spinner.remove($publish_now_button,$publish_button_text);
                                        if($("#tweetType").attr('name').toLowerCase() == 'publish'){
                                                spinner.remove($schedule_button,$schedule_button_text,true);
                                        }else{
                                                spinner.remove($schedule_button,$schedule_button_text);
                                        }
                                        //Only One Error Come To This Not Need Any Array.
                                        if(data.error != undefined){
                                                globalMethod.repsonseError(data,'.tweetModal');
                                        }else if (data.success != undefined){
                                                //Success.
                                                $(".tweetModal").modal("hide");
                                                globalMethod.showNotification('success','top','Right',data.success,'body',3000);
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
                                globalMethod.showNotification('success','top','Right',data.success,'body',3000);
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
                btnTpl: {
                        download:
                          '<a download data-fancybox-download class="fancybox-button fancybox-button--download" title="{{DOWNLOAD}}" href="javascript:;">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.62 17.09V19H5.38v-1.91zm-2.97-6.96L17 11.45l-5 4.87-5-4.87 1.36-1.32 2.68 2.64V5h1.92v7.77z"/></svg>' +
                          "</a>",
                    
                        zoom:
                          '<button data-fancybox-zoom class="fancybox-button fancybox-button--zoom" title="{{ZOOM}}">' +
                          '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.7 17.3l-3-3a5.9 5.9 0 0 0-.6-7.6 5.9 5.9 0 0 0-8.4 0 5.9 5.9 0 0 0 0 8.4 5.9 5.9 0 0 0 7.7.7l3 3a1 1 0 0 0 1.3 0c.4-.5.4-1 0-1.5zM8.1 13.8a4 4 0 0 1 0-5.7 4 4 0 0 1 5.7 0 4 4 0 0 1 0 5.7 4 4 0 0 1-5.7 0z"/></svg>' +
                          "</button>"
                }                
        });
      
        
        /**
         * emo Library Box for Quick Replay && Tweet Compose.
         */
       globalMethod.initEmoj(".quickReplay");
});