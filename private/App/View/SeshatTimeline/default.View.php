<?php
    //var_dump($userTimeLine[0]);
    //exit;
    if(isset($userTimeLine) && !empty($userTimeLine)  && is_array($userTimeLine)):
?>
<div class='container  col-8' style="float:right" > 
<?php                
        foreach($userTimeLine as $key => $value): 
            //Some Logic Changed When The Tweet Is Retweeted From Another User.
            if(isset($value->retweeted_status)):
                       $like_count = $value->retweeted_status->favorite_count;
                       $full_text = $value->retweeted_status->full_text;
                       $screenName = $value->retweeted_status->user->screen_name;//orgin screenName which tweet specific tweets.
                       $name = $value->retweeted_status->user->name;
                       $user_profile = $value->retweeted_status->user->profile_image_url;
                       $media =  isset($value->retweeted_status->extended_entities->media[0]) ? $value->retweeted_status->extended_entities->media[0] : null;
            else:  
                       $like_count = $value->favorite_count;
                       $full_text  = $value->full_text;
                       $screenName = $value->user->screen_name;
                       $name = $value->user->name;
                       $user_profile = $value->user->profile_image_url;
                       $media = isset($value->extended_entities->media[0]) ? $value->extended_entities->media[0]: null;
            endif;  
             $dir = ($value->lang == 'ar')? "dir=rtl" :"";
             $screenName = ($value->lang == 'ar')?$screenName."@":"@".$screenName;
             $retweet_button_style  = ($value->retweeted)?'class="btn btn-link btn-success retweet_unretweet"':'class="btn btn-link retweet_unretweet"';//User retweeted This tweet. class="btn  btn-link btn-success tweet_retweet"
             $retweet_type = ($value->retweeted)?"unretweet":"retweet";
             $like_status   = ($value->favorited)?'btn  btn-link  btn-danger like_unlike':'btn  btn-link like_unlike';//User Liked This Tweet or not.
             $like_type = ($value->favorited)?"unlike":"like";
             $tweet_id = $value->id_str;
?> 

<!-- Tweet Card -->
        <div class="card  col-md-8" style="background-color:white;">
                <div class="card-body">
                    <h6 class="stats stats-right category-social">
                        <i class="fab fa-twitter"></i>
                    </h6>
                    <div class="author">
                            <img src="<?=$this->htmlSafer($user_profile)?>" alt="..." class="avatar img-raised">
                            <a href="" style="color:blue;"><span><?=$this->htmlSafer($name)?>  <span style="color:red;" <?=$dir;?> ><?=$this->htmlSafer($screenName)?></span></span></a>
                        </div>         
           
                    <p style="color:black; font-family: 'Montserrat', sans-serif;"<?=$dir;?>>
                        <?=$this->htmlSafer($full_text)?>
                    </p>
                    
                    <?php 
                    /**
                     * This must be refactor to display all images Twitter can upload 4 media type image in every tweet so must be refactor.
                     * Display One Media Only Type Video Or Image.
                     * Note : media_url Must Be Changed To media_url_https In production to support https.
                     */
                    if(is_null($media) === false):
                            if($media->type == 'video'):
                                $video_link = $media->video_info->variants[0]->url;
                                $poster = $media->media_url;
                    ?>         
                                    <video class ="afterglow" width="720" height="400" class="col-md-12" poster="<?=$poster?>" controls>
                                        <source src="<?=$video_link;?>" type="video/mp4">
                                    Your browser does not support HTML5 video.
                                    </video>
                            <?php
                            else:
                            ?>
                                <img  src="<?=$media->media_url;?>" alt="Rounded Image" class="img-rounded img-tweet">
                            <?php 
                            //End if of stripos Condition. 
                            endif;
                            //End if of media isset condition.   
                            endif;
                            ?>
                    <div class="media-footer" style="float:right;">
                            <a href=""  data-twid = "<?=$tweet_id;?>" data-key="<?=$key;?>" class="btn btn-link tweet_replay">
                                 <i class="fa fa-reply"></i>
                            </a>
                            <a href=""  id="retweet_unretweet_<?=$tweet_id;?>" data-twid = "<?=$tweet_id;?>" data-action = '<?=$retweet_type;?>' data-key="<?=$key;?>" <?=$retweet_button_style;?>>
                                 <i class="fa fa-retweet"></i> <span class='retweet_counter'><?=$this->htmlSafer($value->retweet_count)?></span>
                            </a>
                            <a href=""  id="like_unlike_<?=$tweet_id;?>" data-twid = "<?=$tweet_id;?>" data-action = '<?=$like_type;?>' data-key="<?=$key;?>" class="<?=$like_status;?>">
                                
                                 <i class="fa fa-heart"></i> <span class='like_counter'><?=$this->htmlSafer($like_count)?></span>
                            </a>
                            <div class="btn-group dropup" style="float:right">
                                    <button id="dLabel" type="button" class="btn btn-just-icon btn-link btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="background-color:#FFFFF0;overflow:visible;">
                                          <li class="dropdown-item">
                                              <a href="#paper-kit">
                                                  <div class="row">
                                                      <div class="col-sm-2">
                                                          <span class="icon-simple"><i class="fa fa-envelope"></i></span>
                                                      </div>
                                                      <div class="col-sm-9">Direct Message</div>
                                                  </div>
                                              </a>
                                          </li>
                                          <div class="dropdown-divider"></div>
                                          <li class="dropdown-item">
                                              <a href="#paper-kit">
                                                  <div class="row">
                                                      <div class="col-sm-2">
                                                          <span class="icon-simple"><i class="fa fa-microphone-slash"></i></span>
                                                      </div>
                                                      <div class="col-sm-9">Mute</div>
                                                  </div>
                                              </a>
                                          </li>
                                          <div class="dropdown-divider"></div>
                                          <li class="dropdown-item">
                                              <a href="#paper-kit">
                                                  <div class="row">
                                                      <div class="col-sm-2">
                                                          <span class="icon-simple"><i class="fa fa-exclamation-circle"></i></span>
                                                      </div>
                                                      <div class="col-sm-9">Report</div>
                                                  </div>
                                              </a>
                                          </li>
                                      </ul>
                                  </div>
                              </div>
                        </div>        
                    </div>
<?php                    
endforeach;    
?>
</div>                     
<?php
elseif(isset($userTimeLine) && empty($userTimeLine)):
?>
<div class='container col-8' style="float:right" > 
            <div class="alert alert-info">
                <div class="container">
                    <span><?=NO_TWEET_TIME_LINE?> </span>
                </div>
            </div>

</div>
<?php
endif;
?>                    
        <!-- End Tweet Cards -->  
                <!-- Profile Card -->
                <div class="card  col-6 col-md-4" style="float:left">
                        <div class="card-body  text-center">
                            <span class="category-social text-info pull-right">
                                <i class="fab fa-twitter"></i>
                            </span>
                            <div class="clearfix"></div>
                            <div class="author">
                                <a href="#pablo">
                                   <img src="https://pbs.twimg.com/profile_images/956816588870684672/IPMPeO0W_400x400.jpg" alt="..." class="avatar-big img-raised border-gray">
                                </a>
                                <h5 class="card-title">Kaci Baum</h5>
                                <p class="category"><a href="#twitter" class="text-danger">@kacibaum</a></p>
                            </div>
                            <p class="card-description">
                                "Less, but better – because it concentrates on the essential aspects, and the products are not burdened with non-essentials."
                            </p>
                        </div>
                    </div>          
