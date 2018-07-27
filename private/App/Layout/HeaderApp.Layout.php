<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Paper Kit 2 PRO by Creative Tim</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<link href="<?=ASSESTS_URI."css"?>/lib/bootstrap.min.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/app/now-ui-kit.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/lib/iziToast.min.css" rel="stylesheet" type='text/css' />
	<!--     Fonts and icons     -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700' rel='stylesheet' type='text/css' />
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<?=ASSESTS_URI."css"?>/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/app/nucleo-icons.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/app/seshat.css" rel="stylesheet" type='text/css' />
	<link href="<?=ASSESTS_URI."css"?>/lib/sweetAlert/jquery.sweet-modal.min.css" rel="stylesheet" type='text/css' />
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css">
	<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
	<link href="<?=ASSESTS_URI."css"?>/lib/charts.css" rel="stylesheet" type='text/css'>
	<link rel="stylesheet" href="<?=ASSESTS_URI."css"?>/lib/emojionearea.min.css">
</head>
<?php //Loading Spinner removed bu jquery in global.js file.?>
<div style='padding:10px;margin: auto;top:30%;left:40%;position:fixed;' class='ai-spinner'>
	<img src='/seshat/assets/spinner/ai_spinner.apng' alt='loading' />
</div>
<?php if($this->session->getSession('id') !== false) : ?>

<body ng-app="seshatApp" ng-controller = "appCtrl">
	<div class="modal fade bd-example-modal-lg" id="tweetModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="wrapper">
					<div class="main">
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
												<div> <span class="btn btn-outline-default btn-round btn-file"><span class="fileinput-new"><?=SELECT_IMAGE?></span><span class="fileinput-exists"><?=CHANGE?></span>
													<input type="file" id="tweetMedia" name="tweetMedia" accept="image/*">
													</span> <a href="#" class="btn btn-link btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?=REMOVE?></a>
												</div>
											</div>
											<div id="tags-2">
												<select name="category" class="selectpicker" data-style="btn-link btn-primary btn-round" data-menu-style="dropdown-success">
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
										<div class="col-md-7 col-sm-7">
											<div class="form-group">
												<textarea class="form-control textarea-limited quickReplay" id="tweetContent" name="tweetContent" placeholder="<?=LIMITED_TEXT_AREA?>" rows="13" , maxlength="280"></textarea>
												<h5><small><span id="textarea-limited-message" class="pull-right"><?=CHARS_LEFT?></span></small></h5>
											</div>
											<div class="form-check">
												<label class="form-check-label">
													<input class="form-check-input" name="seshatPublicAccess" type="checkbox" value="true">
													<?=SESHAT_PUBLIC_ACCESS?> <span class="form-check-sign"></span>
												</label>
											</div>
											<div class="col-md-12">
												<div class="title">
													<h3><?=SCEHDULE?></h3>
												</div>
												<div clas="row">
													<div class="col-md-8">
														<div class='form-group'>
															<div class="input-group date" id="datetimepicker">
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
	</div>
	<!--         default navbar with notifications     -->

<body style="" ng-app="seshatApp" ng-class="{
    bootstrapped: bootstrapped,
    dark: theme === 'dark',
    menuOpen: menuOpen }" class="halfView feed-all-muzli bootstrapped loading-muzli-complete">
		<div id="overlay"></div>
		<!-- Main wrapper -->
		<div id="container">
			<header>
				<div class="pull-left">
					<a><?=NAME;?></a>
				</div>
				<nav class="navbar navbar-expand-lg" style="background-color:black;" >
                                <div class="container">
								<div class="navbar-translate">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#example-navbar-icons" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-bar bar1"></span>
                                        <span class="navbar-toggler-bar bar2"></span>
                                        <span class="navbar-toggler-bar bar3"></span>
                                    </button>
								</div>	
                                    <div class="collapse navbar-collapse" id="example-navbar-icons">
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#pablo"><i class="now-ui-icons ui-1_send" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#pablo"><i class="now-ui-icons users_single-02" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown">
                                                    <i class="now-ui-icons ui-1_settings-gear-63" aria-hidden="true"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                                    <a class="dropdown-header">Dropdown header</a>
                                                    <a class="dropdown-item" href="#">Action</a>
                                                    <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a>
                                                    <div class="divider"></div>
                                                    <a class="dropdown-item" href="#">Separated link</a>
                                                    <div class="divider"></div>
                                                    <a class="dropdown-item" href="<?=BASE_URL?>!index/logout"><?=LOGOUT;?></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
			</header>
			<!---->
			<!-- Sidebar -->
			<nav id="sidebar" class="sidebar">
				<header>
					<!-- Header -->
					<a class="logo" href="#"></a>
					<a class="sidebarMenu" href="#" title="">
						<!---->EDIT</a>
				</header>
				<!-- /Header -->
				<div class="sourceSearch" ng-hide="sourcesDragged">
					<div class="searchContainer">
						<input type="text" placeholder="Search for feeds" class="ng-pristine ng-valid ng-empty ng-touched">
					</div>
				</div>
				<ul class="sourcesConstant" muzli-home-switch="homeSwitched" ng-hide="searchSources">
					<li class="enabled"> <i class="source all-sources-icon"></i>
						<span>All your feeds</span>
					</li>
					<li class="enabled"> <i class="source _muzli"></i>
						<span>Our Picks</span>
					</li>
				</ul>
				<ul class="sources ng-pristine ng-untouched ng-valid ui-sortable ng-not-empty">
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _muzli_blog"></i>
						<span>Muzli blog</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _dribbble"></i>
						<span>Dribbble</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _producthunt"></i>
						<span>Product Hunt</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _designer_news"></i>
						<span>Designer News</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _techcrunch"></i>
						<span>Techcrunch</span>
						<!---->
						<a class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _behance"></i>
						<span>Behance</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _ted"></i>
						<span>TED</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _vlogs"></i>
						<span>Vlogs</span>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _fubiz"></i>
						<span>Fubiz</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _sidebar"></i>
						<span>Sidebar</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title=""></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _awwwards"></i>
						<span>Awwwards</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fawwwards.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _the_next_web"></i>
						<span>The Next Web</span>
						<!---->
						<a ng-href="https://app.muz.li/go?link=http%3A%2F%2Fthenextweb.com/" target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fthenextweb.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _css_winner"></i>
						<span>CSS Winner</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fcsswinner.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _abduzeedo"></i>
						<span>Abduzeedo</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fabduzeedo.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _design_milk"></i>
						<span>Design Milk</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fdesign-milk.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _designboom"></i>
						<span>Designboom</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fdesignboom.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled unread ui-sortable-handle"> <i href="#" class="source _colossal"></i>
						<span>Colossal</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fthisiscolossal.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _webdesigner_depot"></i>
						<span>Webdesigner Depot</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fwebdesignerdepot.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _fast_co_design"></i>
						<span>Fast Co. Design</span>
						<!---->
						<a tabindex="-1" target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Ffastcodesign.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _designspiration"></i>
						<span>Designspiration</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fdesignspiration.net/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _99u"></i>
						<span>99u</span>
						<!---->
						<a target="_blank" class="feedLink icon-link" title="" href="https://app.muz.li/go?link=http%3A%2F%2F99u.com/"></a>
						<!---->
					</li>
					<!---->
					<li class="enabled ui-sortable-handle"> <i href="#" class="source _design_you_trust"></i>
						<span>Design You Trust</span>
						<!---->
						<a ng-if="::source.url" tabindex="-1" ng-href="https://app.muz.li/go?link=http%3A%2F%2Fdesignyoutrust.com/" target="_blank" class="feedLink icon-link" ng-click="events.sidebar.clickLink(source.name, $event)" title="" href="https://app.muz.li/go?link=http%3A%2F%2Fdesignyoutrust.com/"></a>
						<!---->
					</li>
					<!---->
				</ul>
				<div class="moreFeeds ng-hide" ng-click="alert('Hello More Feeds!.')" ng-show="searchSources"> <a href="#">More feeds</a>
				</div>
			</nav>
		</div>
        <?php //Here mean that user not authincation with seshat a button of authication must be here. 
else: ?>
<body ng-app="seshatApp" style="background-color:#343c55;padding-top: 20px;">
			<div class="col-4" style="margin: auto;width: 50%;padding: 10px;">
<a href='<?=BASE_URL.LINK_SIGN.' index/signin '?>' style="margin: auto;width: 50%;padding: 10px;" class='btn btn-twitter'><i class="fab fa-twitter"></i> <?= SIGN_TWITTER ?></a>
			</div>
<?php endif; ?>