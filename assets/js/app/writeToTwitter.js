var twitterAction = {
    twitterResponse: function(data){
        if(data.success !== undefined){
            globalMethod.showNotification('success','bottom','center',data.success,'body',1000);
        }else if(data.error !== undefined){
           globalMethod.repsonseError(data);
        }
    },
    //do logic For(retweet-like).
    doLogic : function($indentifier,$counter,$class,$action){
        $counter.text(parseInt($counter.text()) + 1);
        //Delete Old Class.
        $($indentifier).removeAttr("class");
        //Re-init Class.
        $($indentifier).attr("class",$class);
        //Create New Action.
        $($indentifier).removeAttr('data-action');
        $($indentifier).attr('data-action',$action);
    },
    //unDoLogic for (unretweet-unlike).
    unDoLogic : function($indentifier,$counter,$class,$action){
            //unsuccessed process return it back. 
            $counter.text(parseInt($counter.text()) - 1);
            //Delete Old Class.
            $($indentifier).removeAttr("class");
            //Re-init Class.
            $($indentifier).attr("class",$class);
            //Create New Action.
            $($indentifier).removeAttr('data-action');
            $($indentifier).attr('data-action',$action);                                
    }        
};
var writeToTwitter = twitterActionUrl+"do";
$(document).ready(function(){

        //Retweet && unRetweet Section.
        $(".retweet_unretweet").off('click').on('click',function(event){
            event.preventDefault();
            
            $tweet_id = $(this).data("twid");//Tweet Id.
           
            //Indentifier. 
            $indentifier = "#retweet_unretweet_"+$tweet_id;            
            
            $type = $($indentifier).attr('data-action').toLowerCase();
            $retweet_counter = $($indentifier).find('.retweet_counter');
            $data_key = $($indentifier).data("key");
            
            /**
             * Divided To Two Logic Beacause retweet Can Be Upgrade To Be retweet with Qoutes So Modal Must Appear In It's Logic.
             * Some Fixes Can Do Here.
             */
            if($type == 'retweet'){
                    //Retweet Logic.
                    twitterAction.doLogic($indentifier,$retweet_counter,"btn btn-link btn-success retweet_unretweet",'unretweet');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key,
                        "dataType":"json",
                        "success":function(data){

                        if(data.success == undefined){
                            twitterAction.unDoLogic($indentifier,$retweet_counter,"btn-link retweet_unretweet",'retweet');
                        }
                        twitterAction.twitterResponse(data);
                    }
                });        
            }else if ($type == 'unretweet'){
                //Unretweet Logic.
                twitterAction.unDoLogic($indentifier,$retweet_counter,"btn-link retweet_unretweet",'retweet');
                
                $.ajax({
                    "type":"POST",
                    "url":writeToTwitter,
                    "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key,
                    "dataType":"json",
                    "success":function(data){

                        if(data.success == undefined){
                            twitterAction.doLogic($indentifier,$retweet_counter,"btn btn-link btn-success retweet_unretweet",'unretweet');
                        }
                            twitterAction.twitterResponse(data);
                    }
                });
            }

        });
        //End Retweet && unRetweet Section.

        //like && unlike Section.
        $(".like_unlike").off("click").on("click",function(event){
            event.preventDefault();
            $tweet_id = $(this).data("twid");//Tweet Id.
            //Indentifier. 
            $indentifier = "#like_unlike_"+$tweet_id;            
            
            $type = $($indentifier).attr('data-action').toLowerCase();
            $like_counter = $($indentifier).find('.like_counter');
            $data_key = $($indentifier).data("key");
            if($type == 'like'){
                    //Like Logic.
                    twitterAction.doLogic($indentifier,$like_counter,"btn  btn-link  btn-danger like_unlike",'unlike');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key,
                        "dataType":"json",
                        "success":function(data){
                            if(data.success == undefined){
                                twitterAction.unDoLogic($indentifier,$like_counter,'btn btn-link like_unlike','like');
                            }
                                twitterAction.twitterResponse(data);
                        }
                    });
            }else if($type == 'unlike'){
                    //unlike logic.
                    twitterAction.unDoLogic($indentifier,$like_counter,'btn btn-link like_unlike','like');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key,
                        "dataType":"json",
                        "success":function(data){
                            if(data.success == undefined){
                                twitterAction.doLogic($indentifier,$like_counter,"btn btn-link btn-danger like_unlike",'unlike');
                            }
                                twitterAction.twitterResponse(data);
                        }
                    });
            }
            
        });
        //End like && unlike Section.
});