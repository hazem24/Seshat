<!DOCTYPE html>
<html lang="<?=$_COOKIE['lang'] ?? 'en'?>">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="<?=ASSESTS_URI?>img/seshat.png" rel="icon" type="image/png">
	<title><?=NAME;?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />

	<link href="<?=ASSESTS_URI?>/lib/iziToast.min.css" rel="stylesheet" type='text/css' />
	<!--     Fonts and icons  -->
	<link rel="stylesheet" href="<?=ASSESTS_URI?>fonts/feather/feather.min.css" type='text/css'>
  <link rel="stylesheet" href="<?=ASSESTS_URI?>lib/highlight.js/styles/vs2015.css" type='text/css'>
  <link rel="stylesheet" href="<?=ASSESTS_URI?>lib/quill/dist/quill.core.css" type='text/css'>
  <link rel="stylesheet" href="<?=ASSESTS_URI?>lib/select2/dist/css/select2.min.css" type='text/css'>
  <link rel="stylesheet" href="<?=ASSESTS_URI?>lib/flatpickr/dist/flatpickr.min.css" type='text/css'>
  <link href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link href="<?=ASSESTS_URI?>/lib/jasny-bootstrap.min.css" rel="stylesheet" type='text/css' />
	<link href="//cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">
	<link href="<?=ASSESTS_URI?>/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" type='text/css' />
	<link href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.min.css" rel="stylesheet"/>
	<!-- Theme CSS -->
	<link id="stylesheetDark" href="<?=ASSESTS_URI?>css/app/theme-dark.min.css" rel="stylesheet">
	<link id="stylesheetLight" href="<?=ASSESTS_URI?>css/app/theme.min.css" rel="stylesheet">

	<link href="<?=ASSESTS_URI?>lib/sweetAlert/jquery.sweet-modal.min.css" rel="stylesheet" type='text/css' />
	<link rel="stylesheet" href="<?=ASSESTS_URI?>lib/emojionearea.min.css" type='text/css'>
	<style>body { display: none; }</style>
	<script>
  		var colorScheme = ( localStorage.getItem('dashkitColorScheme') ) ? localStorage.getItem('dashkitColorScheme') : 'light';
  </script>
      <!-- Styles -->
      <style>

button.close {
    position: absolute;
    top: 0px;
    right: 6px;
    background-color: #333;
    border-radius: 0 0 6px 6px;
    opacity: 1;
    padding: 3px;
    z-index:  9;
}

.modal-body {
    padding-bottom: 0;
}

.fileinput .form-control {
    border: none;
    resize: vertical;
    box-shadow: none;
    font-size: 0.8rem;
}

.modal-footer {
    padding: 8px 0 0;
}

.modal-footer .btn {
    padding: 0.3vw 2vw;
    font-size: .7rem;
}

.modal-footer button.btn-default {
    background-color: transparent;
    border-color:#999;
}

.modal-footer button:nth-of-type(2):hover{
    background-color: #ddd;
}

.modal-footer button.btn-danger {
        color: #333;
        background-color: transparent;
        border-color: #dc3545;
}

.modal-footer button:first-of-type:hover {
    background-color: #dc3545;
    color: #fff;
}

.modal-footer .btn-file {
    position: absolute;
    left: 10px;
}

</style>
</head>
<?php //Loading Spinner removed bu jquery in global.js file.?>
<div style='padding:10px;margin: auto;top:30%;left:40%;position:fixed;' class='ai-spinner'>
	<img src='/seshat/assets/spinner/ai_spinner.apng' alt='loading' />
</div>
<?php if($this->session->getSession('id') !== false) : 
$twitter_screen_name = $this->session->getSession("username");
$license             = $this->session->getSession("license_type");
?>
<body ng-app="seshatApp" ng-controller = "appCtrl">
<div class="modal fade bd-example-modal-lg tweetModal"  tabindex="-1" role="dialog" aria-labelledby="modal_1" aria-hidden="true">
      <div class="modal-dialog" role="document">
            <div class="modal-body">
                    <form id="composeTweetForm" method="post" action="http://127.0.0.1/seshat/!twitterAction/composeTweet" enctype="multipart/form-data">
                    <div class="modal-content">
                        <!-- Close Button At The Top Right Corner -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <!-- Note:
                        The <div> .fileInput is Important pic of code can't be changed :) -->
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label class="sr-only">textarea</label>
                                    <textarea class="form-control quickReplay"  id="tweetContent" name="tweetContent" placeholder="<?=LIMITED_TEXT_AREA?>" rows="13" , maxlength="280"></textarea>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
												            <input class="custom-control-input" id="seshatPublicAccess" name="seshatPublicAccess" type="checkbox" value="true">
												            <label class="custom-control-label" for="seshatPublicAccess"><?=SESHAT_PUBLIC_ACCESS?></label>
                                </div>
                                <div class="col-md-12">
                                <div class="title">
                                  <h3><?=SCEHDULE?></h3>
                                </div>
                                <div class="row">
                                  <div class="col-md-8">
                                    <div class='form-group'>
                                      <div class="input-group input-group-transparent mb-4" id="datetimepicker">
                                        <input type="text" style="height:30px" id="scehduleTime" name="scehduleTime" class="form-control datetimepicker-input" data-target="#datetimepicker" />
                                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fas fa-calendar-alt"></i>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                                <!-- Upload Photo section -->
                                <div class="fileinput-new thumbnail"></div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 100px;"></div>
                                <div class="col-md-12" style="padding-bottom:20px;" id="tags-2">
                                  <select name="category" class="form-control" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Search ...">
                                    <option disabled selected>
                                      <?=CATEGORIES?>
                                    </option>
                                    <?php foreach(TWEET_CATEGORY as $key=>$category): ?>
                                    <option value="<?=$key?>">
                                      <?=$category?>
                                    </option>
                                    <?php endforeach;?>
                                  </select>
											          </div>
                                <div>
                                    <div class="modal-footer options">
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">
                                                <img src="<?=ASSESTS_URI . 'img/addimg.png'?>" alt="icon" width="25px">
                                            </span>
                                            <!-- File Input -->
                                            <input type="file" id="tweetMedia" name="tweetMedia">
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Change</a>
                                        </span>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><?=CANCEL?></button>
                                        <button type="submit" id="scheduleButton" value="true" name="schedule" class="btn btn-default"><?=SCEHDULE?></button>
                                        <!-- Submit Button -->
                                        <button type="submit" id="publishNow" value="true" name="publishNow" class="btn btn-primary"><?=PUBLISH_NOW?></button>
                                    </div><!-- .modal-footer -->
                                </div>

                            </div><!-- .modal-body -->
                        </div>
                    </div><!-- .modal-content -->
                </form>
        </div>
    </div>
</div>
<!-- Perview -->
<div class="modal fade fixed-right modalDemo"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-vertical" role="document">
      <form class="modal-content" id="demoForm">
        <div class="modal-body">
      
          <!-- Close -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>

          <div class="text-center">
            <img src="<?=ASSESTS_URI?>img/seshat.png" alt="..." class="img-fluid mb-3">
          </div>

          <h2 class="text-center mb-2">
            Make Seshat Your Own
          </h2>

          <p class="text-center mb-4">
            Set preferences that will be cookied for your live preview demonstration.
          </p>

          <hr class="mb-4">

          <h4 class="mb-1">
            Color Scheme
          </h4>

          <p class="small text-muted mb-3">
            Overall light or dark presentation.
          </p>

          <div class="btn-group-toggle d-flex mb-4" data-toggle="buttons">
            <label class="btn btn-white active col">
              <input type="radio" name="colorScheme" id="colorSchemeLight" value="light" checked> <i class="fe fe-sun mr-2"></i>
            </label>
            <label class="btn btn-white col ml-2">
              <input type="radio" name="colorScheme" id="colorSchemeDark" value="dark"> <i class="fe fe-moon mr-2"></i>
            </label>
          </div>

          <h4 class="mb-1">
            Navigation Position
          </h4>

          <p class="small text-muted mb-3">
            Select the primary navigation paradigm for your app.
          </p>

          <div class="btn-group-toggle d-flex mb-4" data-toggle="buttons">
            <label class="btn btn-white active col">
              <input type="radio" name="navPosition" id="navPositionSidenav" value="sidenav" checked> Sidenav
            </label>
            <label class="btn btn-white col ml-2">
              <input type="radio" name="navPosition" id="navPositionTopnav" value="topnav"> Topnav
            </label>
            <label class="btn btn-white col ml-2">
              <input type="radio" name="navPosition" id="navPositionCombo" value="combo"> Combo
            </label>
          </div>

          <h4 class="mb-1">
            Sidebar Color
          </h4>

          <p class="small text-muted mb-3">
            Usually dictated by the color scheme, but can be overriden. 
          </p>

          <div class="btn-group-toggle d-flex mb-4" data-toggle="buttons">
            <label class="btn btn-white active col">
              <input type="radio" name="sidebarColor" id="sidebarColorDefault" value="default" checked> Default
            </label>
            <label class="btn btn-white col ml-2">
              <input type="radio" name="sidebarColor" id="sidebarColorVibrant" value="vibrant"> Vibrant
            </label>
          </div>
    
        </div>
        <div class="modal-footer border-0">
      
          <button type="submit" class="btn btn-block btn-primary mt-auto">
            Preview
          </button>

        </div>
      </form>
    </div>
  </div>
<!-- End Perview -->

      <!-- Modal: Activity -->
	  <div class="modal fade sidebarModalActivity"  tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-vertical" role="document">
        <div class="modal-content">
          <div class="modal-header">

            <!-- Title -->
            <h4 class="modal-title">
				<?=NOTIFICATIONS?>
            </h4>

            <!-- Close -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                &times;
              </span>
            </button>
          </div>
          <notify-area-modal ng-if='navPostion == "sidenav"' ng-controller='layoutCtrl'></notify-area-modal> 
        </div>
      </div>
	</div>
	<!-- End Activity Modal -->
<!-- NAVIGATION
================================================== -->
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light" id="sidebar">
            <div class="container-fluid">
              <!-- Toggler -->
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
    
              <!-- User (xs) -->
              <div class="navbar-user d-md-none">
    
                <!-- Dropdown -->
                <div class="dropdown">
            
                  <!-- Toggle -->
                  <a href="" id="sidebarIcon" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-sm">
                      	<i class="fas fa-cogs"></i>
                    </div>
                  </a>
    
                  <!-- Menu -->
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sidebarIcon">
                    <a href="#!/settings" class="dropdown-item">Settings</a>
                    <hr class="dropdown-divider">
                    <a href="<?=BASE_URL?>!index/logout" class="dropdown-item"><?=LOGOUT;?></a>
                  </div>
    
                </div>
    
              </div>
    
              <!-- Collapse -->
              <div class="collapse navbar-collapse" id="sidebarCollapse">
                <!-- Navigation -->
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" href=".sidebarDashboards" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                      <i class="fe fe-home"></i><?=HOME;?>
                    </a>
                    <div class="collapse sidebarDashboards" >
                      <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                          <a href="#!/profile/twitter/<?=$twitter_screen_name?>" class="nav-link ">
						  		<?=YOUR_PROFILE;?>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#!/timeline" class="nav-link ">
						  		<?=TWITTER_TIME_LINE;?>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href=".sidebarPages" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarPages">
					 	<i class="fe fe-book-open"></i><?=FEATURES?>
                    </a>
                    <div class="collapse show sidebarPages" >
                      <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                          <a href=".sidebarProfile" class="nav-link" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="sidebarProfile">
                            <?=PROFILE?>
                          </a>
                          <div class="collapse show sidebarProfile">
                            <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                <a href="#!statistics" class="nav-link">
									<?=STATISTICS?>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="#!/checkFriends/twitter" class="nav-link ">
									<?=CHECK_FREINDS?>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="#!/tasks" class="nav-link ">
									<?=TASKS;?>
                                </a>
                              </li>
                            </ul>
                          </div>
						</li>
						</li>
                        <li class="nav-item">
                          <a href=".sidebarProject"  class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProject">
                            <?=FOLLOWERS_FEATURES;?>
                          </a>
                          <div class="collapse sidebarProject">
                            <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                <a href="#!controlfollowers/nonFollowers"   class="nav-link ">
									<?=UNFOLLOW;?>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="#!controlfollowers/recentUnfollow"  class="nav-link ">
									<?=RECENT_UNFOLLOW;?>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="#!controlfollowers/recentFollowers"  class="nav-link ">
									<?=RECENT_FOLLOWERS;?>
                                </a>
                              </li>
                              <li class="nav-item">
                                <a href="#!controlfollowers/fans"  class="nav-link ">
									<?=FANS;?>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </li>
                        <li class="nav-item">
                          <a href=".sidebarTeam" class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTeam">
                            <?=INFLUENCER_FEATURES;?>
                          </a>
                          <div class="collapse sidebarTeam">
                            <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                <a href="#!followTree" class="nav-link ">
									<?=FOLLOW_TREE;?>
                                </a>
                              </li>
                            </ul>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li class="nav-item">
                          <a href=".seshatactivity"  class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProject">
                          <span class="fe fe-activity mr-4"></span><?=ACTIVITY;?>
                          </a>
                          <div class="collapse seshatactivity">
                            <ul class="nav nav-sm flex-column">
                              <li class="nav-item">
                                <a href="#!/activity"   class="nav-link ">
									<?=SESHAT_ACTIVITY;?>
                                </a>
                            </li>    
                        </ul>
                      </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href=".sidebarAuth" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAuth">
                      <i class="fe fe-user"></i> <?=ADD_MEDIA;?>
                    </a>
                    <div class="collapse sidebarAuth" >
                      <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                          <a href="#sidebarSignIn" readonly class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignIn">
                            Instagram (Soon).
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#sidebarSignUp" readonly class="nav-link" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSignUp">
                            Youtube(Soon).
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li class="nav-item d-md-none">
                    <a class="nav-link" href=".sidebarModalActivity" data-toggle="modal">
                    <span id="notify_counter"></span><span class="icon"><i style="color:red;" 
                           class="fe fe-bell"></i></span> Notifications
                      
                    </a>
                  </li>
                </ul>
                <!-- Push content down -->
				<div class="mt-auto"></div>
				<a href=".tweetModal" class="btn btn-block btn-primary mb-4" data-toggle="modal">
                  <i class="fe fe-pencil"></i> <?=NEW_POST;?>
                </a>
                <!-- Customize -->
                <a href=".modalDemo" class="btn btn-block btn-primary mb-4" data-toggle="modal">
                  <i class="fe fe-sliders mr-2"></i> <?=CUSTOMIZE?>
                </a>
                 <!-- Customize -->
                <?php 
                if ($license == 0):
                ?>
                 <a href="." class="btn btn-block btn-danger mb-4" data-toggle="modal">
                  <i class="fas fa-arrow-up"></i> <?=UPGRADE_BUTTON?>
                </a>
                <?php
                endif;
                ?>
                <!-- User (md) -->
                <div class="navbar-user d-none d-md-flex" id="sidebarUser">
            
                  <!-- Icon -->
                  <a href=".sidebarModalActivity" class="navbar-user-link" data-toggle="modal">
                    <span class="icon">
                      <i style="color:red;"class="fe fe-bell"></i>
                    </span>
                  </a>
    
                  <!-- Dropup -->
                  <div class="dropup">
              
                    <!-- Toggle -->
                    <a href="" id="sidebarIconCopy" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="icon">
					  		<i class="fas fa-cogs"></i>
                      </div>
                    </a>
    
                    <!-- Menu -->
                    <div class="dropdown-menu" aria-labelledby="sidebarIconCopy">
                      <a href="#!/settings" class="dropdown-item">Settings</a>
                      <hr class="dropdown-divider">
                      <a href="<?=BASE_URL?>!index/logout" class="dropdown-item"><?=LOGOUT;?></a>
                    </div>
    
                  </div>
    
            </div>
              </div> <!-- / .navbar-collapse -->
    
            </div>
          </nav>
        
        
          <nav class="navbar navbar-expand-lg navbar-light" id="topnav">
            <div class="container">
              <!-- Toggler -->
              <button class="navbar-toggler mr-auto" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <!-- User -->
              <div class="navbar-user">
          
                <!-- Dropdown -->
                <div class="dropdown mr-4 d-none d-lg-flex">
            
                  <!-- Toggle -->
                  <a href="" class="text-muted" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="icon">
                    <span id="notify_counter"></span>
                      <i style="color:red;"class="fe fe-bell"></i>
                    </span>
                  </a>
    
                  <!-- Menu -->
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col">
                    
                          <!-- Title -->
                          <h5 class="card-header-title">
                            Notifications
                          </h5>
    
                        </div>
                        <div class="col-auto">
                    
                          <!-- Link -->
                          <a href="#!/activity" class="small">
                            View all
                          </a>
    
                        </div>
                      </div> <!-- / .row -->
                    </div> <!-- / .card-header -->
                    <notify-area ng-if='navPostion == "topnav"' ng-controller = "layoutCtrl"></notify-area>
                    </div> <!-- / .dropdown-menu -->
                </div>

                <!-- Dropdown -->
                <div class="dropdown">
                  <!-- Toggle -->
                  <a href="" style="padding:10px;" class="avatar avatar-sm dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  	<span class="icon">
					  <i class="fas fa-cog"></i>
                    </span>
                  </a>
                  <!-- Menu -->
                  <div class="dropdown-menu dropdown-menu-right">
                    <a href="settings.html" class="dropdown-item">Settings</a>
                    <hr class="dropdown-divider">
                    <a href="<?=BASE_URL?>!index/logout" class="dropdown-item"><?=LOGOUT;?></a>
                  </div>
                </div>
              </div>
              <!-- Collapse -->
              <div class="collapse navbar-collapse mr-auto order-lg-first" id="navbar">
                <!-- Navigation -->
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle " href="" id="topnavDashboards" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?=HOME;?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="topnavDashboards">
                      <li>
                        <a class="dropdown-item " href="#!/profile/twitter/<?=$twitter_screen_name?>">
							<?=YOUR_PROFILE;?>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item " href="#!timeline">
							<?=TWITTER_TIME_LINE;?>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" id="topnavPages" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fe fe-book-open"></i><?=FEATURES?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="topnavPages">
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle" href="" id="topnavProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?=PROFILE?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnavProfile">
                          <a class="dropdown-item" href="#!statistics">
						  	<?=STATISTICS?>
                          </a>
                          <a class="dropdown-item " href="#!/checkFriends/twitter">
						  		<?=CHECK_FREINDS?>
                          </a>
                          <a class="dropdown-item " href="#!/tasks">
						  		<?=TASKS;?>
                          </a>
                        </div>
                      </li>
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle " href="" id="topnavProject" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?=FOLLOWERS_FEATURES;?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnavProject">
                          <a class="dropdown-item "  href="#!controlfollowers/nonFollowers">
						  	<?=UNFOLLOW;?>
                          </a>
                          <a class="dropdown-item "  href="#!controlfollowers/recentUnfollow">
						  	<?=RECENT_UNFOLLOW;?>
                          </a>
                          <a class="dropdown-item "  href="#!controlfollowers/recentFollowers">
						  	<?=RECENT_FOLLOWERS;?>
                          </a>
                          <a class="dropdown-item "  href="#!controlfollowers/fans">
						  		<?=FANS;?>
                          </a>
                        </div>
                      </li>
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle " href="" id="topnavTeam" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?=ACTIVITY;?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnavTeam">
                          <a class="dropdown-item" href="#!followTree">
						  	<?=SESHAT_ACTIVITY;?>
                          </a>
                        </div>
                      </li>
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle " href="" id="topnavTeam" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?=INFLUENCER_FEATURES;?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnavTeam">
                          <a class="dropdown-item" href="#!followTree">
						  	<?=FOLLOW_TREE;?>
                          </a>
                        </div>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" id="topnavAuth" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?=ADD_MEDIA;?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="topnavAuth">
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle" href="" id="topnavSignIn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Instagram (Soon).
                        </a>
                      </li>
                      <li class="dropright">
                        <a class="dropdown-item dropdown-toggle" href="" id="topnavSignUp" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Youtube(Soon).
                        </a>
                      </li>
                    </ul>
				  </li>
				  <li class="nav-item">
                		<a class="nav-link" href=".modalDemo" data-toggle="modal">
                  			<?=CUSTOMIZE?>
                		</a>
					</li>
					<li class="nav-item">
                		<a class="nav-link" href=".tweetModal" data-toggle="modal">
                  			<?=NEW_POST?>
                		</a>
                    </li>
                    <li class="nav-item">
                		<a class="nav-link" style="color:red;" href="." data-toggle="modal">
                  			<?=UPGRADE_BUTTON?>
                		</a>
              	  	</li>
                </ul>
    
              </div>
    
            </div> <!-- / .container -->
          </nav>
        
    
        <!-- MAIN CONTENT
        ================================================== -->
        <div class="main-content">
            <nav class="navbar navbar-expand-md navbar-light d-none d-md-flex" id="topbar">
              <div class="container-fluid">
                <!-- User -->
                <div class="navbar-user">
          
                  <!-- Dropdown -->
                  <div class="dropdown mr-4 d-none d-md-flex">
            
                    <!-- Toggle -->
                    <a href="" class="text-muted" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="icon"><span id="notify_counter"></span>
                        <i style="color:red;"class="fe fe-bell"></i>
                      </span>
                    </a>
    
                    <!-- Menu -->
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-card">
                      <div class="card-header">
                        <div class="row align-items-center">
                          <div class="col">
                    
                            <!-- Title -->
                            <h5 class="card-header-title">
                              Notifications
                            </h5>
    
                          </div>
                          <div class="col-auto">
                    
                            <!-- Link -->
                            <a href="#!/activity" class="small">
                              View all
                            </a>
    
                          </div>
                        </div> <!-- / .row -->
                      </div> <!-- / .card-header -->
                      <notify-area ng-if='navPostion == "combonav"' ng-controller = "layoutCtrl"></notify-area>
                    </div> <!-- / .dropdown-menu -->
                  </div>
                  <!-- Dropdown -->
                  <div class="dropdown">
                    <!-- Toggle -->
                    <a href="" class="avatar avatar-sm dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="icon">
					  		<i class="fas fa-cogs"></i>
                      </div>
                    </a>
                    <!-- Menu -->
                    <div class="dropdown-menu dropdown-menu-right">
                      <a href="settings.html" class="dropdown-item">Settings</a>
                      <hr class="dropdown-divider">
                      <a href="<?=BASE_URL?>!index/logout" class="dropdown-item"><?=LOGOUT;?></a>
                    </div>
                  </div>
                </div>
              </div>
            </nav>

<main class="ng-view">
</main>

<?php //Here mean that user not authincation with seshat a button of authication must be here. 
else: ?>
<body ng-app="seshatApp" style="background-color:white;padding-top: 20px;">
<div class="col-4" style="margin: auto;width: 50%;padding: 10px;">
<a href='<?=BASE_URL.LINK_SIGN.'index/signin '?>' class="btn btn-twitter btn-icon-label">
    <span class="btn-inner--icon"><i class="fab fa-twitter"></i></span>
    <span class="btn-inner--text"><?= SIGN_TWITTER ?></span>
</a>
</div>
<?php endif; ?>