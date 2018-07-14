<?php
        namespace App\DomainHelper;
        //use Framework\Lib\Security\Data\StringFilter as Filter;



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

            /**
             * @method extractTweetData responsable for extract needed data from tweet that seshat need .. organize tweet to appear to user.
             * @param tweet object of the tweet.
             * @param analytic just flag to extract data that for user that retweet or reacted to tweet not owner of tweet itself.
             * @return array.
             */
            public static function extractTweetData($tweet,bool $analytic = false){
                /**
                 * global variables.
                */
                $links_in_this_tweet=[];//reset links tweet global array.
                $mentions_users = [];//Reset global mentions users.
                $mentions_in_tweet = [];//Reset hashtag global array.
                $hash_tag_in_tweets = [];
                //End  global variables.
                //Some Logic Changed When The Tweet Is Retweeted From Another User.
                $retweeted = isset($tweet->retweeted_status) ? true:false;
                if($retweeted && $analytic === false):
                        //var_dump($tweet);
                        //exit;
                        $like_count = $tweet->retweeted_status->favorite_count;
                        $retweet_count = $tweet->retweeted_status->retweet_count;
                        $org_text   = $tweet->retweeted_status->full_text;
                        $full_text  = $tweet->retweeted_status->full_text;
                        $screen_name = $tweet->retweeted_status->user->screen_name;//orgin screenName which tweet specific tweets.
                        $user_retweeted_tweet = " @".$tweet->user->screen_name; //User Which retweeted this tweet.
                        $name = $tweet->retweeted_status->user->name;
                        $user_profile = $tweet->retweeted_status->user->profile_image_url;
                        $media =  isset($tweet->retweeted_status->extended_entities->media[0]) ? $tweet->retweeted_status->extended_entities->media[0] : null;
                        $hash_tag_tweets = (isset($tweet->retweeted_status->entities->hashtags) && !empty($tweet->retweeted_status->entities->hashtags))?$tweet->retweeted_status->entities->hashtags:false;
                        $mentions_in_this_tweet = (isset($tweet->retweeted_status->entities->user_mentions) && !empty($tweet->retweeted_status->entities->user_mentions))?$tweet->retweeted_status->entities->user_mentions:false;
                        $links_in_tweet = (isset($tweet->retweeted_status->entities->urls) && !empty($tweet->retweeted_status->entities->urls))?$tweet->retweeted_status->entities->urls:false;
                        $following = $tweet->retweeted_status->user->following;
                        $tweet_id = $tweet->retweeted_status->id_str;
                        $user_id  = $tweet->retweeted_status->user->id_str;
                        $lang = $tweet->retweeted_status->lang;
                        
                else:  
                        $like_count = $tweet->favorite_count;
                        $retweet_count = $tweet->retweet_count;
                        $org_text = $tweet->full_text;
                        $full_text = $tweet->full_text;
                        $screen_name = $tweet->user->screen_name;
                        $name = $tweet->user->name;
                        $user_profile = $tweet->user->profile_image_url;
                        $media = isset($tweet->extended_entities->media[0]) ? $tweet->extended_entities->media[0]: null;
                        $hash_tag_tweets = (isset($tweet->entities->hashtags) && !empty($tweet->entities->hashtags))?$tweet->entities->hashtags:false;
                        $mentions_in_this_tweet = (isset($tweet->entities->user_mentions) && !empty($tweet->entities->user_mentions))?$tweet->entities->user_mentions:false;
                        $links_in_tweet = (isset($tweet->entities->urls) && !empty($tweet->entities->urls)) ? $tweet->entities->urls : false;
                        $following = $tweet->user->following;
                        $tweet_id  = $tweet->id_str;
                        $user_id   = $tweet->user->id_str;
                        $lang = $tweet->lang;

                endif;

                $ar = ($lang == 'ar') ? true : false;  
                $dir = ($ar)? "rtl" :"";
                $replay_screen_name = "@" . $screen_name;//For replay logic.
                $screenName = ($ar) ? $screen_name."@":"@".$screen_name;
                $retweet_button_style  = ($tweet->retweeted)?'btn  btn-link btn-success retweet_unretweet':'btn btn-link retweet_unretweet';//User retweeted This tweet. class="btn  btn-link btn-success tweet_retweet"
                $retweet_type = ($tweet->retweeted)?"unretweet":"retweet";
                $like_status   = ($tweet->favorited)?'btn btn-danger btn-link like_unlike':'btn btn-link  like_unlike';//User Liked This Tweet or not.
                $like_type = ($tweet->favorited)?"unlike":"like";
                
                /**
                 * links color section.
                */


                //add expanded links if found.
                if($links_in_tweet !== false):
                        foreach ($links_in_tweet as $key => $link) :
                                /*if($screen_name == "YinneAdrianaM" && $link->expanded_url != "https://twitter.com/canalspace/status/992539401287684096"){
                                                var_dump($key,$link);
                                                exit;
                                                
                                }*/
                                $links_in_this_tweet[] =  [$link->url,"<a class='link-danger' target='_blank' href=\"$link->expanded_url\">$link->display_url</a>"];//Links In text.
                        endforeach;

                       
                        
                endif;
                //End links section.
                /**
                 * Hashtag color section.
                 */
                if($hash_tag_tweets !== false):
                        foreach ($hash_tag_tweets as $key => $hashtag) :
                                $colored_tag = '#'.$hashtag->text;
                                $hash_tag_in_tweets[] =  [$hashtag->text,"<a href='' style='color:DarkViolet;'>".$colored_tag."</a>"];//hashtag In text.
                        endforeach;
                endif;
                //End Hashtag section. 
                        $full_text = str_ireplace(['@','#'],'',$full_text);
                /**
                 * Mentions color section.
                 */
                if($mentions_in_this_tweet !== false):
                        foreach ($mentions_in_this_tweet as $key => $mention) :
                                $colored_mentions = ($ar) ? $mention->screen_name.'@': '@'.$mention->screen_name;
                                $mentions_in_tweet[] =  [$mention->screen_name,"<a href='' class='link-info'>".$colored_mentions."</a>"];//hashtag In text.
                        endforeach;
                endif;
                //End mentions color sesction.
                //mention section. 
                if(isset($mentions_in_tweet) && is_array($mentions_in_tweet) && !empty($mentions_in_tweet)):
                        foreach ($mentions_in_tweet as $key => $user) :
                                $full_text  = str_ireplace($user[0],$user[1],$full_text);
                        endforeach;
                endif;
                //End mention.
                //Links.
                if(isset($links_in_this_tweet) && is_array($links_in_this_tweet) && !empty($links_in_this_tweet)):
                        
                        foreach ($links_in_this_tweet as $key => $link) :
                                $full_text  = str_ireplace($link[0],$link[1],$full_text);
                        endforeach;
                endif;

                //Remove media links.
                $links_inside_tweet_full_text = (preg_match_all('#https:\/\/t.co\/.+\S+#',$full_text,$links))? true : false;
                if($links_inside_tweet_full_text === true){
                        //Remove links.
                        foreach ($links[0] as $key => $media_link) :
                                $full_text = str_ireplace($media_link,'',$full_text); //This links is image or media links not wanted.     
                        endforeach;
                }
                //End Links.

                //Hashtags.
                if(isset($hash_tag_in_tweets) && is_array($hash_tag_in_tweets) && !empty($hash_tag_in_tweets)):
                        foreach ($hash_tag_in_tweets as $key => $hashtag) :
                                $full_text  = str_ireplace($hashtag[0],$hashtag[1],$full_text);
                        endforeach;
                endif;
                //End Hashtags.


                //Media section.
                
                /**
                 * This must be refactor to display all images Twitter can upload 4 media type image in every tweet so must be refactor.
                 * Display One Media Only Type Video Or Image.
                 * Note : media_url Must Be Changed To media_url_https In production to support https.
                 */
                if(is_null($media) === false):
                        if($media->type == 'video'):
                            $video_link = $media->video_info->variants[0]->url;
                            $poster = $media->media_url;
                             $media = (object)['type'=>'video','src'=>$video_link,'poster'=>$poster];
                        else:
                            $media = (object)['type'=>'image','src'=>$media->media_url];
                        //End if of stripos Condition. 
                        endif;
                //End if of media is_null condition.   
                endif;
                //End Media section.

                //Following status.
                if($following == false):
                        $following =   '<button class="btn btn-just-icon btn-round btn-outline-danger btn-tooltip" rel="tooltip" title="'.FOLLOW_BUTTON.'"><i class="fa fa-plus"></i></button>';
                else : 
                        $following =   '<button class="btn btn-just-icon btn-round btn-outline-danger btn-tooltip" rel="tooltip" title="'.UNFOLLOW_BUTTON.'"><i class="fas fa-minus-circle"></i></button>';                       
                endif;        

                //End Following status.
                
                //Uses for analtyic only.
                $followers_count = $tweet->user->followers_count;
                $friends_count   = $tweet->user->friends_count;//Number of person the user follows.
                //End uses of analtyic only.

                //Fake impression beacuse really impression data about 3000$ per month in paid api of twitter.
                $impression = (int)(($like_count + $retweet_count + ($followers_count * (rand(5,20)/100))));

                $total_reacted   = (int)($retweet_count + $like_count);
                $reacted_times = ($total_reacted <= 0)? 1 : $total_reacted; //To prevent divide by zero.
                    
                /**
                 * This calculation must be in another helper.
                 */

                $statics = (object)['total_reacted'=>$total_reacted,'retweet_precent'=>ceil(($retweet_count/$reacted_times)*100) , 'like_precent'=>floor(($like_count/$reacted_times)*100)];
                return ['created_at'=>$tweet->created_at,'like_count'=>(int)$like_count,'org_text'=> $org_text,'full_text'=>$full_text,'retweeted'=>$retweeted,'screenName'=>$screenName,
                        'name'=>$name,'screen_name'=>$screen_name,'user_profile'=>$user_profile,'media'=>$media,'retweet_count'=>(int)$retweet_count,
                        'dir'=>$dir,'lang'=>$lang,'user_id'=>$user_id,'following'=>$following,'replay_screen_name'=>$replay_screen_name,'retweet_button_style'=>$retweet_button_style,'retweet_type'=>$retweet_type,'like_status'=>$like_status,
                        'like_type'=>$like_type,'tweet_id'=>$tweet_id,'links_in_this_tweet'=>$links_in_this_tweet,'hash_tag_in_tweets'=>$hash_tag_in_tweets,
                        'mentions_in_tweet'=>$mentions_in_tweet,'statics'=>$statics,'impression'=>$impression,'followers_count'=>$followers_count,'friends_count'=>$friends_count,'favourites_count'=>$tweet->user->favourites_count,'statuses_count'=>$tweet->user->statuses_count,'user_retweeted_tweet'=>(isset($user_retweeted_tweet))?$user_retweeted_tweet:''];        
            }
            /**
             * This method responsable for loop through tweets and return all tweets in an array format.
             * @method tweetsStyle.
             * @return array.
             */
            public static function tweetsStyle ( array $tweets ):array {
                $styled_tweets = [];
                $count_tweets = count($tweets);
                if ($count_tweets >= 1 && isset($tweets[0]->full_text)) { 
                        for ($i=0;$i<$count_tweets;$i++) {
                                $styled_tweets[] = self::extractTweetData($tweets[$i]);
                        }
                }
                return $styled_tweets;
            }

            /**
             * this method create the modal of fake followers report.
             * @method fakeFollowersReport.
             * @return string.
             */
            public static function fakeFollowersReport ( array $report_data ) {
                $profile_img = $report_data['profile_img'];
                $screen_name = $report_data['screen_name'];
                $totalFollowers = $report_data['totalFollowers'];
                $fake_followers = $report_data['fakeNumbers'];
                $fake_followers_percentage = $report_data['fakePercentage'];
                $sample = $report_data['sample'];
                //Translate.
                $TOTAL_NUMBER_OF_FOLLOWERS = TOTAL_NUMBER_OF_FOLLOWERS;
                $FAKE_FOLLOWERS_NUMBER     = FAKE_FOLLOWERS_NUMBER;
                $FAKE_FOLLOWERS_PERCENTAGE = FAKE_PERCENTAGE;
                $SAMPLE                    = SAMPLE;
                $CANCEL                    = CANCEL;
                $TWEET_THIS                = TWEET_THIS;
                $TOLERANCE                 = TOLERANCE;
return <<<FAKE_FOLLOWERS_REPORT
<div class="modal fade" id="fakeAccountsReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-notice">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="now-ui-icons ui-1_simple-remove"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="instruction">
            <div>
                <div class="col-md-12 float-md-left">
                        <img style="border-radius:50%;" src="$profile_img" /><br>
                        <span style="color:red;">@$screen_name</span><br>
                        <strong>$TOTAL_NUMBER_OF_FOLLOWERS : </strong> <strong style="color:red">$totalFollowers</strong>  <br>
                        <strong>$FAKE_FOLLOWERS_NUMBER : </strong> <strong style="color:red">$fake_followers</strong>  <br>
                        <strong>$FAKE_FOLLOWERS_PERCENTAGE : </strong> <strong style="color:red">$fake_followers_percentage</strong>  <br>
                        <strong>$SAMPLE : </strong> <strong style="color:red">$sample</strong>  <br>
                        <strong>$TOLERANCE : </strong> <strong style="color:green"> ± 10%</strong>  
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
FAKE_FOLLOWERS_REPORT;
            }

        }