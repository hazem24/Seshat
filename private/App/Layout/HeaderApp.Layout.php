<!DOCTYPE html>
<html  lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" >
        <title>Paper Kit 2 PRO by Creative Tim</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <link href="<?=ASSESTS_URI."css"?>/lib/bootstrap.min.css" rel="stylesheet"  type='text/css'/>
        <link href="<?=ASSESTS_URI."css"?>/app/paper-kit.css?v=2.1.0" rel="stylesheet" type='text/css'/>
        <link href="<?=ASSESTS_URI."css"?>/app/notifyStyle.css" rel="stylesheet" type='text/css'/>
        <!--     Fonts and icons     -->
        <link href='//fonts.googleapis.com/css?family=Roboto:700' rel='stylesheet' type='text/css'/>
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="//use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet" type='text/css'/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />        
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
        <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="<?=ASSESTS_URI."css"?>/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" type='text/css'/>
        <link href="<?=ASSESTS_URI."css"?>/app/nucleo-icons.css" rel="stylesheet" type='text/css'/>
        <link href="<?=ASSESTS_URI."css"?>/lib/sweetAlert/jquery.sweet-modal.min.css" rel="stylesheet" type='text/css'/>
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <link href="<?=ASSESTS_URI."css"?>/lib/charts.css" rel="stylesheet" type='text/css'>
        <link rel="stylesheet" href="<?=ASSESTS_URI."css"?>/lib/emojionearea.min.css">
    </head> 
    <body style="background-color:#343c55;padding-top: 125px;">
        <div class="modal fade bd-example-modal-lg" id="tweetModal"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content" >
                    <div class="wrapper">
                        <div class="main" >
                            <div class="section" style="background-color:white;">
                                <div class="container">
                                    <form id="composeTweetForm" method="post" action="http://127.0.0.1/seshat/!twitterAction/composeTweet" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-5 col-sm-5">
                                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail img-no-padding" style="max-width: 370px; max-height: 250px;">
                                                        <img src="http://127.0.0.1/seshat/assets/img/image_placeholder.jpg" alt="...">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail img-no-padding" style="max-width: 370px; max-height: 250px;"></div>
                                                    <div>
                                                        <span class="btn btn-outline-default btn-round btn-file"><span class="fileinput-new"><?=SELECT_IMAGE?></span><span class="fileinput-exists"><?=CHANGE?></span><input type="file" id = "tweetMedia" name="tweetMedia" accept="image/*"></span>
                                                        <a href="#" class="btn btn-link btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?=REMOVE?></a>
                                                    </div>
                                                </div>
                                                <div id="tags-2">
                                                <select name="category" class="selectpicker" data-style="btn-link btn-primary btn-round" data-menu-style="dropdown-success">
                                                    <option disabled selected><?=CATEGORIES?></option>
                                                    <?php foreach(TWEET_CATEGORY as $key => $category):
                                                    ?>
                                                        <option value="<?=$key?>"><?=$category?></option>
                                                    <?php       
                                                    endforeach;?>
                                                    
                                                </select>

                                                </div>
                                            </div>
                                            <div class="col-md-7 col-sm-7">
                                                <div class="form-group">
                                                    <textarea class="form-control textarea-limited quickReplay" id="tweetContent" name="tweetContent" placeholder="<?=LIMITED_TEXT_AREA?>" rows="13", maxlength="280" ></textarea>
                                                    <h5><small><span id="textarea-limited-message" class="pull-right"><?=CHARS_LEFT?></span></small></h5>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input class="form-check-input" name="seshatPublicAccess" type="checkbox" value="true">
                                                    <?=SESHAT_PUBLIC_ACCESS?>
                                                    <span class="form-check-sign"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="title">
                                                        <h3><?=SCEHDULE?></h3>
                                                    </div>
                                                    <div clas="row">
                                                    <div  class="col-md-8">  
                                                    <div class='form-group'>
                                                            <div class="input-group date"   id="datetimepicker">
                                                            <input type="text" style="height:30px"id="scehduleTime" name="scehduleTime" class="form-control datetimepicker-input" data-target="#datetimepicker"/>
                                                            <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                                            <div class="input-group-text" ><i  class="fas fa-calendar-alt" style="height:30px;font-size: 1.5em"></i></div>
                                                            </div>
                                                     </div>
                                                    </div>
                                                    </div>
                                                </div>    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row buttons-row">
                                            <div class="col-md-4 col-sm-4">
                                                <button class="btn btn-outline-danger btn-block btn-round" data-dismiss="modal"><?=CANCEL?></button>
                                            </div>
                                            
                                            <div class="col-md-4 col-sm-4">
                                                <button  type="submit" id="scheduleButton" value="true"name="schedule" class="btn btn-outline-primary btn-block btn-round"><?=SCEHDULE?></button>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <button  type="submit" id="publishNow" value="true" name="publishNow" class="btn btn-primary btn-block btn-round"><?=PUBLISH_NOW?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--         default navbar with notifications     -->
        <nav class="navbar navbar-expand-sm fixed-top" style='background-color:#485274;'>
            <div class="container">
                <a class="navbar-brand" href="#paper-kit" style='color:white;'><?=NAME?></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <form class="form-inline ml-auto">
                        <input class="form-control mr-sm-2 no-border" type="text" placeholder="Search">
                        <button type="submit" class="btn btn-primary btn-just-icon btn-round"><i class="nc-icon nc-zoom-split" aria-hidden="true"></i></button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <button  class="nav-link btn   btn-round"  data-toggle="modal" data-target=".bd-example-modal-lg" id="composeTweet" style="background-color: #e3f2fd;" data-toggle="tooltip" data-placement="bottom" title="Create Tweet" href="#paper-kit">
                            <?=TWEET;?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn  btn-round" style="background-color:white;" data-toggle="tooltip" data-placement="bottom" title="<?=TWITTER_TIME_LINE;?>" href="<?=BASE_URL.LINK_SIGN."seshatTimeline"?>">
                            <?=TIMELINE;?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn  btn-round" style="background-color:white;" data-toggle="tooltip" data-placement="bottom" title="<?=YOUR_PROFILE?>" href="#paper-kit">
                            <?=PROFILE;?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-just-icon btn-warning  "  data-placement="bottom"  data-toggle="dropdown">
                            <i class="nc-icon nc-sound-wave"></i>
                            </a>
                            <span class="label label-danger notification-bubble">+99</span>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-notification">
                                <li class="no-notification">
                                    You're all clear!
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="btn btn-just-icon btn-dark  "  data-placement="bottom"data-toggle="dropdown">
                            <i class="nc-icon nc-email-85"></i>
                            </a>
                            <span class="label label-danger notification-bubble">+99</span>   
                            <ul class="dropdown-menu dropdown-menu-right dropdown-wide dropdown-notification">
                                <li class="dropdown-header">
                                    You have 7 unread notifications
                                </li>
                                <li >
                                    <ul class="dropdown-notification-list scroll-area">
                                        <a href="#paper-kit" class="notification-item">
                                            <div class="notification-text">
                                                <span class="label label-icon label-success"><i class="nc-icon nc-chat-33"></i></span>
                                                <span class="message"><b>Patrick</b> mentioned you in a comment.</span>
                                                <br />
                                                <span class="time">20min ago</span>
                                                <button class="btn btn-just-icon read-notification btn-round">
                                                <i class="nc-icon nc-check-2"></i>
                                                </button>
                                            </div>
                                        </a>
                                        <a href="#paper-kit" class="notification-item">
                                            <div class="notification-text">
                                                <span class="label label-icon label-info"><i class="nc-icon nc-alert-circle-i"></i></span>
                                                <span class="message">Our privacy policy changed!</span>
                                                <br />
                                                <span class="time">1day ago</span>
                                            </div>
                                        </a>
                                        <a href="#paper-kit" class="notification-item">
                                            <div class="notification-text">
                                                <span class="label label-icon label-warning"><i class="nc-icon nc-ambulance"></i></span>
                                                <span class="message">Please confirm your email address.</span>
                                                <br />
                                                <span class="time">2days ago</span>
                                            </div>
                                        </a>
                                        <a href="#paper-kit" class="notification-item">
                                            <div class="notification-text">
                                                <span class="label label-icon label-primary"><i class="nc-icon nc-paper"></i></span>
                                                <span class="message">Have you thought about marketing?</span>
                                                <br />
                                                <span class="time">3days ago</span>
                                            </div>
                                        </a>
                                    </ul>
                                </li>
                                <!--      end scroll area -->
                                <li class="dropdown-footer">
                                    <ul class="dropdown-footer-menu">
                                        <li>
                                            <a href="#paper-kit">Mark all as read</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#paper-kit" class="nav-link navbar-brand" data-toggle="dropdown" width="30" height="30">
                                <div class="profile-photo-small">
                                    <img src="https://pbs.twimg.com/profile_images/956816588870684672/IPMPeO0W_400x400.jpg" alt="Circle Image" class="img-circle img-responsive img-no-padding">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right dropdown-danger">
                                <div class="dropdown-header">Dropdown header</div>
                                <a class="dropdown-item" href="#paper-kit">Action</a>
                                <a class="dropdown-item" href="#paper-kit">Another action</a>
                                <a class="dropdown-item" href="#paper-kit">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#paper-kit">Separated link</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?=BASE_URL.LINK_SIGN.'index/logout'?>"><?=LOGOUT;?></a>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>