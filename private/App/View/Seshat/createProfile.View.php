<?php
        $name = (isset($user->data->name) && is_null($user->data->name) === false) ? $this->htmlSafer($user->data->name) : '';
        $email = (isset($user->data->email)&& is_null($user->data->email) === false) ? $this->htmlSafer($user->data->email) : '';
        $profile_image = (isset($user->data->profile_image_url_https) && is_null($user->data->profile_image_url_https) === false)? $user->data->profile_image_url_https : '';
?>
<!doctype html>
<html  lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="<?=ASSESTS_URI."wizard/"?>img/apple-icon.png" />
	<link rel="icon" type="image/png" href="<?=ASSESTS_URI."wizard"?>/img/favicon.png" />
	<title>Paper Bootstrap Wizard by Creative Tim</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!-- CSS Files -->
    <link href="<?=ASSESTS_URI."wizard/"?>css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?=ASSESTS_URI."wizard/"?>css/paper-bootstrap-wizard.css" rel="stylesheet" />
	<!-- Fonts and Icons -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="<?=ASSESTS_URI."wizard/"?>css/themify-icons.css" rel="stylesheet">
    
    <!-- Sweet Alert Modal Lib Css !-->
    <link href="<?=ASSESTS_URI?>css/lib/sweetAlert/jquery.sweet-modal.min.css" rel="stylesheet">
</head>

<body>
	<div class="image-container set-full-height" style="background-image: url('<?=ASSESTS_URI."wizard"?>/img/paper-1.jpeg')">
	    <!--   Creative Tim Branding   -->
	    <a href="http://creative-tim.com">
	         <div class="logo-container">
	            <div class="logo">
	                <img src="<?=ASSESTS_URI."wizard"?>/img/new_logo.png">
	            </div>
	            <div class="brand">
	                <?=NAME;?>
	            </div>
	        </div>
	    </a>
	    <!--   Big container   -->
	    <div class="container">
	        <div class="row">
		        <div class="col-sm-8 col-sm-offset-2">

		            <!--      Wizard container        -->
		            <div class="wizard-container">

		                <div class="card wizard-card" data-color="blue" id="wizardProfile">
		                    <form action="" method="POST">

		                    	<div class="wizard-header text-center">
		                        	<h3 class="wizard-title"><?=CREATE_PROFILE?></h3>
									<p class="category"><?=MORE_INFO?></p>
		                    	</div>

								<div class="wizard-navigation">
									<div class="progress-with-circle">
									     <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" style="width: 21%;"></div>
									</div>
									<ul>
			                            <li>
											<a href="#about" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-user"></i>
												</div>
												<?=ABOUT;?>
											</a>
										</li>
			                            <li>
											<a href="#account" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-twitter-alt"></i>
												</div>
												<?=ACCOUNT_TYPE;?>
											</a>
										</li>
			                            <li>
											<a href="#describe" data-toggle="tab">
												<div class="icon-circle">
													<i class="ti-comment"></i>
												</div>
												<?=DESCRIBE_YOUR_ACCOUNT;?>
											</a>
										</li>
			                        </ul>
								</div>
		                        <div class="tab-content">
		                            <div class="tab-pane" id="about">
		                            	<div class="row">
											<h5 class="info-text"><?=TWITTER_DATA?></h5>
											<div class="col-sm-4 col-sm-offset-1">
												<div class="picture-container">
													<div class="picture">
														<img src="<?=$profile_image;?>" alt="profile image" class="picture-src"  />
													</div>
												</div>
                                            </div>
											<div class="col-sm-6">
												<div class="form-group">
													<label><?=YOUR_NAME?></label>
													<input name="firstname" value="<?=$name;?>" maxLength="40" type="text" class="form-control" placeholder="Seshat...">
												</div>
												<div class="form-group">
													<label><?=EMAIL?></label>
													<input name="email" type="email" value="<?=$email;?>"  class="form-control" placeholder="seshat005@gmail.com">
												</div>
											</div>
										</div>
		                            </div>
		                            <div class="tab-pane" id="account">
                                        <h5 class="info-text"><?=ACCOUNT_TYPE_ASK?></h5>
		                                <div class="row">
		                                    <div class="col-sm-8 col-sm-offset-2">
		                                        <div class="col-sm-4">
		                                            <div class="choice active" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="1" checked="checked"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-user"></i>
															<p><?=PERSONAL?></p>
		                                                </div>
		                                            </div>
		                                        </div>
		                                        <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="3"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-star"></i>
															<p><?=STAR?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="4"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-write"></i>
															<p><?=WRITTER?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="2"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-package"></i>
															<p><?=BRAND_PRODUCT?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="5"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-search"></i>
															<p><?=READER?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="6"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-pencil-alt"></i>
															<p><?=STUDENT?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="7"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-video-clapper"></i>
															<p><?=CONTENT_MAKER?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="9"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-desktop"></i>
															<p><?=PROGRAMMER?></p>
		                                                </div>
		                                            </div>
                                                </div>
                                                <div class="col-sm-4">
		                                            <div class="choice" data-toggle="wizard-radio">
		                                                <input type="radio" name="account_type" value="8"/>
		                                                <div class="card card-checkboxes card-hover-effect">
		                                                    <i class="ti-help"></i>
															<p><?=OTHER?></p>
		                                                </div>
		                                            </div>
                                                </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="tab-pane" id="describe">
		                                <div class="row">
		                                    <div class="col-sm-12">
                                                <h5 class="info-text"><?=HELP_PEOPLE_TO_FIND_YOU?></h5>
                                                <div class="form-group">
                                                        <textarea id='account_decribe' minlength="20" maxlength = "300" name = 'account_describe' class="form-control" rows="9" required></textarea>
                                                </div>

		                                    </div>
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        <div class="wizard-footer">
		                            <div class="pull-right">
		                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd' name='next' value="<?=NEXT?>" />
		                                <input type='submit' id="finish_form"class='btn btn-finish btn-fill btn-warning btn-wd' name='finish' value="<?=FINISH?>" />
		                            </div>

		                            <div class="pull-left">
		                                <input type='button' class='btn btn-previous btn-default btn-wd' name='previous' value="<?=PERVIOUS?>" />
		                            </div>
		                            <div class="clearfix"></div>
                                </div>
                                <?=$this->protectFormView;?> 
		                    </form>
		                </div>
		            </div> <!-- wizard container -->
		        </div>
	    	</div><!-- end row -->
		</div> <!--  big container -->

	    <div class="footer">
	        <div class="container text-center">
	            Made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>. Free download <a href="http://www.creative-tim.com/product/paper-bootstrap-wizard">here.</a>
	        </div>
	    </div>
	</div>

</body>

	<!--   Core JS Files   -->
	<script src="<?=ASSESTS_URI."wizard/"?>js/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="<?=ASSESTS_URI."wizard/"?>js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=ASSESTS_URI."wizard/"?>js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="<?=ASSESTS_URI."wizard/"?>js/paper-bootstrap-wizard.js" type="text/javascript"></script>

    <!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
    <script src="<?=ASSESTS_URI."wizard/"?>js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?=ASSESTS_URI?>js/lib/sweetAlert/jquery.sweet-modal.min.js" type="text/javascript"></script>
    <?php
//Check If User Need Reauthincate or any Error Exists.
if(is_array($user) && isset($user['error']) && is_object($user) === false  && is_array($user['error']) && !empty($user['error'])):
?>
<script>
<?php    
    foreach ($user['error'] as $key => $value) :
?>

        <?=$value;?>

<?php
    endforeach;
?>
</script>
<?php    
endif;
?>

</html>
