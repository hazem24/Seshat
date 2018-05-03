<?php
    //var_dump($userTimeLine[2]->entities->urls);
    //exit;
    $user_screenName = "@".$this->session->getSession("username"); //username eqv. to screenname
    //var_dump($user_screenName);
    // exit;
    if(isset($userTimeLine) && !empty($userTimeLine)  && is_array($userTimeLine)):
?>
<div class='container col-8' style="float:right" > 
<?php     
       

        foreach($userTimeLine as $tweet_key => $tweet):

            $tweet = (array)$FrontEndHelper::extractTweetData($tweet);
            foreach($tweet as $var_name => $extracted_data):
                $$var_name = $extracted_data;      
            endforeach;        
?> 

<!-- Tweet Card -->
<div  class="card  col-md-8" style="background-color:white;">
                <div class="card-body">
                    <h6 class="stats stats-right category-social">
                        <i class="fab fa-twitter" style="font-size: 25px;"></i>
                    </h6>
                    <a href="<?=BASE_URL.LINK_SIGN."seshat/analytic".DS."$screen_name".DS."$tweet_id"?>" class='seshatTweetView' data-screen-name= "<?=$screenName?>" data-tweet-id="<?=$tweet_id;?>"><h6 class="stats stats-right category-social" style="margin: auto;width: 50%;padding: 10px;">
                    <i class="fas fa-chart-line" style="font-size: 25px;color:#343c55"></i>
                    </h6></a>
                  
                    <div class="author">
                            <?php
                                if($retweeted === true) : 
                            ?>
                                    <i class="fas fa-retweet"></i><?=" ".RETWEETED_BY;?><a href ='#' style='color:green;'><?=$user_retweeted_tweet;?></a>
                            <?php
                                endif;
                            ?>
                            <br>
                            <img src="<?=$this->htmlSafer($user_profile)?>" alt="..." class="avatar img-raised">
                            <a href="" style="color:blue;"><span><?=$name;?>  <span style="color:red;" <?=$dir;?> ><?=$this->htmlSafer($screenName)?></span></span></a>

                        </div>         
                        <p  style="color:black; font-family: 'Montserrat', sans-serif;"<?=$dir;?>>
                                <?=$full_text;?>
                        </p> 
                        <input type="hidden" value="<?=$org_text?>" id ="content<?=$tweet_key?>"> 
                    <?php
							//Echo media if found.
							if(is_null($media) === false):
									echo $media;
							endif;			
					?>
                        
                    <div class="media-footer" style="float:right;">
                            <a href=""   data-twid = "<?=$tweet_id;?>" data-replay-to = "<?=($retweeted === true)?$replay_screen_name." ".$user_retweeted_tweet:$replay_screen_name;?>"  class="btn btn-link tweet_replay">
                                 <i class="fa fa-reply"></i>
                            </a>
                            <button   id="retweet_unretweet_<?=$tweet_id;?>" data-twid = "<?=$tweet_id;?>" data-action = '<?=$retweet_type;?>' data-key="<?=$tweet_key;?>" data-replay-context="0" <?=$retweet_button_style;?>>
                                    <i class="fas fa-retweet"></i> <span class='retweet_counter'><?=$retweet_count;?></span>
                            </button>
                            <button   id="like_unlike_<?=$tweet_id;?>" data-twid = "<?=$tweet_id;?>" data-action = '<?=$like_type;?>' data-key="<?=$tweet_key;?>" data-replay-context="0"  class="<?=$like_status;?>">
                                
                            <i class="far fa-heart"></i> <span class='like_counter'><?=$this->htmlSafer($like_count)?></span>
                            </button>
                            <div class="btn-group dropup" style="float:right">
                                    <button id="dLabel" type="button" class="btn btn-just-icon btn-link btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="background-color:#FFFFF0;overflow:visible;">
                                          <li class="dropdown-item">
                                              <a href="#paper-kit">
                                                  <div class="row">
                                                      <div class="col-sm-2">
                                                          <span class="icon-simple"><i class="fas fa-chart-line"></i></span>
                                                      </div>
                                                      <div class="col-sm-9 seshatTweetView" data-screen-name= "<?=$screenName?>" data-tweet-id="<?=$tweet_id;?>"><a href="<?=BASE_URL.LINK_SIGN."seshat/analytic".DS."$screen_name".DS."$tweet_id"?>"><?=SESHAT_ANALYTIC_TWEET;?></a></div>
                                                  </div>
                                              </a>
                                          </li>
                                          <div class="dropdown-divider"></div>
                                          <li class="dropdown-item">
                                              <a href="#paper-kit">
                                                  <div class="row">
                                                      <div class="col-sm-2">
                                                          <span class="icon-simple"><i class="fab fa-twitter-square"></i></span>
                                                      </div>
                                                      <div class="col-sm-9 tweetThis" data-key='content<?=$tweet_key?>'><?=TWEET_THIS;?></div>
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
                                          <?php
                                            if(strtolower($screenName) == strtolower($user_screenName)):
                                          ?>
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
                                          <?php
                                          endif;
                                          ?>
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
<div class='container col-8' style="margin: auto;width: 50%;border: 3px solid blue;padding: 10px;" > 
            <div class="alert alert-info">
                <div class="container">
                    <span><?=NO_TWEET_TIME_LINE?> </span>
                </div>
            </div>

</div>
<?php
endif;
?>                    
