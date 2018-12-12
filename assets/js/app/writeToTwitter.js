var twitterAction = {
    twitterResponse: function(data){//uses in (retweet,unretweet,like,unlike,deleteTweet).
        if(data.success !== undefined){
            globalMethod.showNotification('success','top','Right',data.success,'body',5000);
        }else if(data.error !== undefined){
           globalMethod.repsonseError(data);
        }else{
            globalMethod.showNotification('danger','top','Right',"Something error happen pleas try again later.",'body',1000);
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
    },retweetLogic : function (element,cached = false) {
            //Retweet && unRetweet Section.
            $tweet_id = $(element).data("twid");//Tweet Id.
           
            //Indentifier. 
            $indentifier = "#retweet_unretweet_"+$tweet_id;            
            
            $(element).attr("disabled",true);
            $type = $($indentifier).attr('data-action').toLowerCase();
            $retweet_counter = $($indentifier).find('.retweet_counter');
            $data_key = $($indentifier).data("key");
            $replay_context = $($indentifier).attr("data-replay-context");
            
            /**
             * Divided To Two Logic Beacause retweet Can Be Upgrade To Be retweet with Qoutes So Modal Must Appear In It's Logic.
             * Some Fixes Can Do Here.
             */
            if($type == 'retweet'){
                    //Retweet Logic.
                    twitterAction.doLogic($indentifier,$retweet_counter,"btn btn-success retweet_unretweet",'unretweet');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key+"&replay_context="+$replay_context+"&cached="+cached,
                        "dataType":"json",
                        "success":function(data){
                        if(data.success == undefined){
                            twitterAction.unDoLogic($indentifier,$retweet_counter,"btn-link retweet_unretweet",'retweet');
                        }
                        twitterAction.twitterResponse(data);
                        $(element).attr("disabled",false);

                    }
                });        
            }else if ($type == 'unretweet'){
                //Unretweet Logic.
                twitterAction.unDoLogic($indentifier,$retweet_counter,"btn btn-link retweet_unretweet",'retweet');
                
                $.ajax({
                    "type":"POST",
                    "url":writeToTwitter,
                    "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key+"&replay_context="+$replay_context+"&cached="+cached,
                    "dataType":"json",
                    "success":function(data){
                        if(data.success == undefined){
                            twitterAction.doLogic($indentifier,$retweet_counter,"btn btn-success retweet_unretweet",'unretweet');
                        }
                            twitterAction.twitterResponse(data);
                            $(element).attr("disabled",false);
                    }
                });
            }
        //End Retweet && unRetweet Section.
    },likeLogic : function (element,cached = false) {
        //like && unlike Section.
            $tweet_id = $(element).data("twid");//Tweet Id.
            //Indentifier. 
            $indentifier = "#like_unlike_"+$tweet_id;            
            $(element).attr("disabled",true);
            $type = $($indentifier).attr('data-action').toLowerCase();
            $like_counter = $($indentifier).find('.like_counter');
            $data_key = $($indentifier).data("key");
            $replay_context = $($indentifier).attr("data-replay-context");
            if($type == 'like'){
                    //Like Logic.
                    twitterAction.doLogic($indentifier,$like_counter,"btn btn-danger like_unlike",'unlike');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key+"&replay_context="+$replay_context+"&cached="+cached,
                        "dataType":"json",
                        "success":function(data){
                            if(data.success == undefined){
                                twitterAction.unDoLogic($indentifier,$like_counter,'btn btn-link like_unlike','like');
                            }
                                twitterAction.twitterResponse(data);
                                $(element).attr("disabled",false);
                        }
                    });
            }else if($type == 'unlike'){
                    //unlike logic.
                    twitterAction.unDoLogic($indentifier,$like_counter,'btn btn-link like_unlike','like');
                    $.ajax({
                        "type":"POST",
                        "url":writeToTwitter,
                        "data":"type="+$type+"&tweet_id="+$tweet_id+"&key="+$data_key+"&replay_context="+$replay_context+"&cached="+cached,
                        "dataType":"json",
                        "success":function(data){
                            if(data.success == undefined){
                                twitterAction.doLogic($indentifier,$like_counter,"btn btn-link btn-danger like_unlike",'unlike');
                            }
                                twitterAction.twitterResponse(data);
                                $(element).attr("disabled",false);
                        }
                    });
            }            
    },replayLogic : function ($tweet_id , $replay_to){
        //replay area.
            //init screenName.
            //create hidden twitter id input.
            $("<input>").attr("type","hidden").attr("name","tweet_id").attr("value",$tweet_id).appendTo("#composeTweetForm");        
            $(".emojionearea-editor").text($replay_to);
            $(".tweetModal").modal();                    
        //End replay area.
    },deleteTweet : function ($tweet_id , element){
            $.ajax({
                "url"  : writeToTwitter,
                "type" : "POST",
                "data" : "type=deleteTweet&&tweet_id=" + $tweet_id + "&&cached=false",
                "dataType" : "json",
                "success" : function ( data ) {
                    twitterAction.twitterResponse(data);
                    if( data.success !== undefined ){
                        //remove tweet element from the dom.
                        $(element).remove();
                    }
                }
            });
    },createRelation ( $relationType , $user_name , async ) {
        var $response = {};
        $.ajax({
            "url"  : twitterActionUrl + "createRelation",
            "type" : "POST",
            "data" : "type=" + $relationType + "&&with=" + $user_name,
            "dataType" : "json",
            "async"    : async,
            "success" : function ( data ) {
                if(data.error !== undefined){
                    globalMethod.repsonseError(data);
                }else {
                    $response = data;
                }
            }
        });
        return $response; //return ['request_sent'=>true || 'follow'=>true || 'unfollow'=>true ];
    }        
};
var writeToTwitter = twitterActionUrl+"do";
/*$(document).ready(function(){

        //twitterAction.retweetLogic(".retweet_unretweet",true);
        //twitterAction.likeLogic(".like_unlike",true);
});*/