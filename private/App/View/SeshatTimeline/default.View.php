<?php
    //var_dump($userTimeLine[0]);
    //exit;
    if(isset($userTimeLine) && !empty($userTimeLine)  && is_array($userTimeLine)):
?>
<div class='container  col-8' style="float:right" > 
<?php                
        foreach($userTimeLine as $key => $value):
?> 

<!-- Tweet Card -->
        <div class="card  col-md-8" style="background-color:white;">
                <div class="card-body">
                    <h6 class="stats stats-right category-social">
                        <i class="fab fa-twitter"></i>
                    </h6>
                    <div class="author">
                            <img src="<?=$this->htmlSafer($value->user->profile_image_url)?>" alt="..." class="avatar img-raised">
                            <a href="" style="color:blue;"><span><?=$this->htmlSafer($value->user->name)?>  <span style="color:red;">@<?=$this->htmlSafer($value->user->screen_name)?></span></span></a>
                        </div>
           
                    <p <?= ($value->lang == 'ar')?"dir=rtl" :''?>>
                        <?=$this->htmlSafer($value->text)?>
                    </p>
                    <?php if(isset($value->entities->media[0])):?>
                        <img src="<?=$value->entities->media[0]->media_url;?>" alt="Rounded Image" class="img-rounded img-tweet">
                        <img src="<?=$value->entities->media[0]->media_url;?>" alt="Rounded Image" class="img-rounded img-tweet">
                    <?php endif;?>
                    <div class="media-footer" style="float:right">
                            <a href="#paper-kit" class="btn btn-link">
                                 <i class="fa fa-reply"></i>
                            </a>
                            <a href="#paper-kit" class="btn btn-success btn-link">
                                 <i class="fa fa-retweet"></i> <?=$this->htmlSafer($value->retweet_count)?>
                            </a>
                            <a href="#paper-kit" class="btn btn-danger btn-link">
                                 <i class="fa fa-heart"></i> <?=$this->htmlSafer($value->favorite_count)?>
                            </a>
                            <div class="btn-group dropup" style="float:right" >
                                    <button id="dLabel" type="button" class="btn btn-just-icon btn-link btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <ul class="dropdown-menu" style="background-color:red;overflow:visible;">
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
