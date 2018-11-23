<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="<?=ASSESTS_URI?>img/seshat.png" rel="icon" type="image/png">
	<title><?=NAME;?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />

	<link href="<?=ASSESTS_URI."css"?>/lib/iziToast.min.css" rel="stylesheet" type='text/css' />
	<!--     Fonts and icons     -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700' rel='stylesheet' type='text/css' />
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">
	<link href="<?=ASSESTS_URI."css"?>/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" type='text/css' />
	<link type="text/css" href="<?=ASSESTS_URI?>/vendor/highlight.js/styles/atom-one-dark.css" rel="stylesheet">
	<!-- Theme CSS -->
	<link id = "mainView" href="<?=ASSESTS_URI?>css/theme.min.css" rel="stylesheet">
	<link href="<?=ASSESTS_URI."css"?>/app/seshat.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/lib/sweetAlert/jquery.sweet-modal.min.css" rel="stylesheet" type='text/css' />
	<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
	<link href="<?=ASSESTS_URI."css"?>/lib/charts.css" rel="stylesheet" type='text/css'>
	<link rel="stylesheet" href="<?=ASSESTS_URI."css"?>/lib/emojionearea.min.css">
</head>
<?php //Loading Spinner removed bu jquery in global.js file.?>
<div style='padding:10px;margin: auto;top:30%;left:40%;position:fixed;' class='ai-spinner'>
	<img src='/seshat/assets/spinner/ai_spinner.apng' alt='loading' />
</div>
<?php if($this->session->getSession('id') !== false) : 
$twitter_screen_name = $this->session->getSession("username");
//$license           = $this->session->
?>

<body ng-app="seshatApp" ng-controller = "appCtrl">
<div class="modal fade bd-example-modal-lg" id="tweetModal" tabindex="-1" role="dialog" aria-labelledby="modal_1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
						<form id="composeTweetForm" method="post" action="http://127.0.0.1/seshat/!twitterAction/composeTweet" enctype="multipart/form-data">
									<div class="row">
										<div class="col-md-2" style="float-left">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail img-no-padding" style="max-width: 370px; max-height: 250px;">
													<img src="http://127.0.0.1/seshat/assets/img/image_placeholder.jpg" alt="...">
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail img-no-padding" style="max-width: 370px; max-height: 250px;"></div>
												<div> <span class="btn btn-outline-default btn-round btn-file"><span class="fileinput-new"><?=SELECT_IMAGE?></span><span class="fileinput-exists"><?=CHANGE?></span>
													<input type="file" id="tweetMedia" name="tweetMedia" accept="image/*">
													</span> <a href="#" class="btn btn-link btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?=REMOVE?></a>
												</div>
											</div>
											<div class="col-md-12" style="float-left" id="tags-2">
												<select name="category"  class="form-control" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Search ...">
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
										</div>
										<div class="col-md-12">
											<div  class="form-group"> 
												<textarea  class="form-control quickReplay" id="tweetContent" name="tweetContent" placeholder="<?=LIMITED_TEXT_AREA?>" rows="13" , maxlength="280"></textarea>
												<h5><small><span id="textarea-limited-message" class="pull-right"><?=CHARS_LEFT?></span></small></h5>
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
																	<div class="input-group-text"><i class="fas fa-calendar-alt" style="height:30px;font-size: 1.5em"></i>
																	</div>
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
											<button class="btn btn-outline-danger btn-block btn-round" data-dismiss="modal">
												<?=CANCEL?>
											</button>
										</div>
										<div class="col-md-4 col-sm-4">
											<button type="submit" id="scheduleButton" value="true" name="schedule" class="btn btn-outline-primary btn-block btn-round">
												<?=SCEHDULE?>
											</button>
										</div>
										<div class="col-md-4 col-sm-4">
											<button type="submit" id="publishNow" value="true" name="publishNow" class="btn btn-info btn-block btn-round">
												<?=PUBLISH_NOW?>
											</button>
										</div>
									</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header ng-controller = "layoutCtrl">
	<nav-area></nav-area>
</header>
<header class="header-account-page bg-gradient-primary d-flex align-items-end">
            <div class="container">
                <div class="row">
				<div class="col-md-4 float-right">
					<button type="button" data-toggle="modal" data-target="#tweetModal" class="btn btn-danger" styly="padding-bottom:40px;"><?=NEW_POST;?></button>
				</div>

                    <div class=" col-lg-12">
                        <!-- Salute + Small stats -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-5 mb-4 mb-md-0">
                                <span class="h2 mb-0 text-white d-block"><?= $twitter_screen_name; ?></span>
                                <span class="text-light"><?=NICE_DAY?></span>
                            </div>
						</div>
                        <!-- Account navigation -->
                        <div class="d-flex">
                            <a href="<?=BASE_URL?>!profile/twitter/<?=$twitter_screen_name?>" class="btn btn-icon btn-group-nav shadow btn-secondary btn-dark">
                                <span class="btn-inner--icon"><i class="far fa-user"></i></span>
                                <span class="btn-inner--text d-none d-md-inline-block"><?=YOUR_PROFILE;?></span>
							</a>
							<a href="<?=BASE_URL?>!seshatTimeline" class="btn btn-icon btn-group-nav shadow btn-secondary btn-dark">
                                <span class="btn-inner--icon"><i class="fas fa-rss-square"></i></span>
                                <span class="btn-inner--text d-none d-md-inline-block"><?=TWITTER_TIME_LINE;?></span>
                            </a>
                            <div class="btn-group btn-group-nav shadow ml-auto" role="group" aria-label="Basic example">
                                <div class="btn-group" role="group">
                                    <button id="btn-group-boards" type="button" class="btn btn-secondary btn-dark btn-icon dropdown-toggle" data-toggle="dropdown" data-offset="0,8" aria-haspopup="true" aria-expanded="false">
                                        <span class="btn-inner--icon"><i class="fas fa-address-book"></i></span>
                                        <span class="btn-inner--text d-none d-sm-inline-block"><?=FEATURES?></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" aria-labelledby="btn-group-boards">
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/statistics"><?=STATISTICS?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/checkFriends/twitter"><?=CHECK_FREINDS?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/controlFollowers/twitter/nonFollowers"><?=UNFOLLOW;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/controlFollowers/twitter/recentUnfollow"><?=RECENT_UNFOLLOW;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/controlFollowers/twitter/recentFollowers"><?=RECENT_FOLLOWERS;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/controlFollowers/twitter/fans"><?=FANS;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/tasks"><?=TASKS;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!seshat/createReport/hashtag"><?=TRACK_HASHTAG;?></a>
										<a class="dropdown-item" href="<?=BASE_URL?>!followTree"><?=FOLLOW_TREE;?></a>
                                    </div>
                                </div>
                                <div class="btn-group" role="group">
                                    <button id="btn-group-settings" type="button" class="btn btn-secondary btn-dark btn-icon dropdown-toggle" data-toggle="dropdown" data-offset="0,8" aria-haspopup="true" aria-expanded="false">
                                        <span class="btn-inner--icon"><i class="fas fa-cogs"></i></span>
                                        <span class="btn-inner--text d-none d-sm-inline-block"><?=SETTINGS?></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow" aria-labelledby="btn-group-settings">
                                        <a class="dropdown-item" href="account-profile.html">Profile</a>
                                        <a class="dropdown-item" href="account-settings.html">Settings</a>
                                        <a class="dropdown-item" href="account-billing.html">Billing</a>
                                        <a class="dropdown-item" href="account-notifications.html">Notifications</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
</div>
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