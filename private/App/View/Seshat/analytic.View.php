<?php
		/**
		 * @var analyticData array consists of three parts : ['tweet'=>'contains tweet data.','replies'=>'contains replies of tweet.','reacted user'=>'information of users.']
		 */
		if(isset($analyticData) && !empty($analyticData)):
			$tweet =  (object)$FrontHelperClass::extractTweetData($analyticData['tweet']);
?>

<div class="col-lg-12 container" >
			<div class="card col-sm-4" style="margin: auto;width: 50%;border: 3px solid blue;padding: 10px;">
				<div class="card-body">
					<h6 class="author pull-left">
						<div class="author">
							<img src="<?=$tweet->user_profile;?>" class="avatar img-raised" alt="Avatar">
								<a href="" class="link-danger" <?=$tweet->dir;?>><?=$tweet->screenName;?></a>
							</div>
						</h6>
						<span class="category-social text-info pull-right">
							<i class="fa fa-twitter"></i>
						</span>
						<div class="clearfix"></div>
						<p class="card-description"  <?=$tweet->dir;?>>
		                                    "<?=$tweet->full_text;?>"
						</p>
						<?php
							//Echo media if found.
							if(is_null($tweet->media) === false):
									echo $tweet->media;
							endif;			
						?>
					</div>
				</div>
				<div class="col-md-3" style="margin:auto;width:100%;padding:10px;float:left;" >
					<div class="card">
						<div class="card-header card-header-icon" data-background-color="red">
							<i class="material-icons">pie_chart</i>
							
							<h5 style="text-align: center;">
								<?=TWEET_STATICS?>
							</h5>
						</div>
						<div class="card-content">
							<h5 class="card-title"></h5>
							<span style="font-weight: bold;">
								<ul>
									<li>
										<?=TOTAL_FOLLOWER;?> (
																	
										<a href="" class="link-danger" <?=$tweet->dir;?>><?=$tweet->screenName;?></a>) : <?=$tweet->followers_count;?>
																
									</li>
									<li>
										<?=ACTIVE_TWEET_USER;?> : <?=$tweet->statics->total_reacted;?>
																
									</li>
									<li>
										<?=TWEET_IMPRESSION;?> : <?=$tweet->impression;?>
																
									</li>
								</ul>
							</span>
						</div>
						<div id="tweetStatics" class="ct-chart"></div>
						<input type="hidden" id="rt_precent" value="<?=$tweet->statics->retweet_precent;?>" />
						<input type="hidden" id="like_precent" value="<?=$tweet->statics->like_precent;?>" />
						<div class="card-footer">
							<h6>
								<?=LEGEND;?>
							</h6>
							<i class="fa fa-circle text-info"></i>
							<?=RETWEET;?>
							<i class="fa fa-circle text-danger"></i>
							<?=LIKE;?>

						</div>
					</div>
				</div>
			
				<!-- start acordeon -->
				<div class="float-right" id="acordeon" style="margin: auto;width: 75%;padding:25px;">
					<div id="accordion" role="tablist" aria-multiselectable="true">
						<div class="card no-transition">
							<div class="card-header card-collapse" role="tab" id="headingOne">
								<h5 class="mb-0 panel-title" style="text-align:center;">
									<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		                                    <?=REACTED_USER_TO_TWEET;?>
										<i class="nc-icon nc-minimal-down"></i>
									</a>
								</h5>
							</div>
							<div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
								<div class="card-body">
									<!--- Start Active users. -->
									<div class="col-md-12">
										<div>
											<div class="card-header card-header-icon" data-background-color="green">
                                                    <i class="fas fa-users" style="font-size:25px;"></i>
												<h5 style="text-align: center;">
													<?=ACTIVE_TWEET_USERS;?>
												</h5>
											</div>
											<div class="card-content">
												<div class="section section-white section-search">
													<div class="container">
														<div class="row">
															<div class="col-md-6 col-12 ml-auto mr-auto text-center">
																<ul class="list-unstyled follows">
			<?php												
			//Reacted sections.
			if(is_array($analyticData['reacted_user']) && !empty($analyticData['reacted_user'])) :
					//There is reacted users.
					
					foreach ($analyticData['reacted_user'] as $key => $user) :
						$reacted_user = (object)$FrontHelperClass::extractTweetData($user,true);
			?>
							<li>
									<div class="row">
										<div class="col-md-2 col-3">
											<img src="<?=$reacted_user->user_profile;?>" alt="Circle Image" class="img-circle img-no-padding img-responsive">
										</div>
										<div class="col-md-6 col-4 description">
											<h5><a href="" class="link-danger" <?=$reacted_user->dir;?>><?=$reacted_user->screenName;?></a>											
												<br>
												<small>								
												<?=FOLLOWERS_COUNTER?> : <b><?=$reacted_user->followers_count;?></b><br>
												<?=FOLLOWING_COUNTER?> : <b><?=$reacted_user->friends_count;?></b>												
												</small>
											</h5>
										</div>
										<div class="col-md-2 col-2">
											<?=$reacted_user->following;?>
										</div>
									</div>
							</li>


			<?php			
					endforeach;
					
			?>

					
			<?php
			else : 
					//No reacted users.
			?>
				<p style="text-align:center;font-size:large;color:red;"><?=NO_USERS_REACTED?></p>
			<?php		
			endif;

			//End of reacted sections.
			?>
			</ul>
																					<div class="text-missing">
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--- End Active Users. -->
													</div>
												</div>
												<div class="card-header card-collapse" role="tab" id="headingTwo">
													<h5 class="mb-0 panel-title" style="text-align:center;">
														<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
		                                                    <?=TWEET_REPLIES;?>
		                                                    
															
															<i class="nc-icon nc-minimal-down"></i>
														</a>
													</h5>
												</div>
												<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
													<div class="card-body">
														<!--- Replies Table -->
														<div class="col-md-12" style="margin:auto;width:100%;border:2px solid grey;padding: 10px;text-align:center;">
															<div class="card-header card-header-icon" style="margin:auto;width:100%;border:2px solid grey;padding: 10px;text-align:center;">
																<i class="fas fa-reply-all" style="font-size:25px;"></i>
															</div>
															<div class="card-body col-md-12">
																<div class="table-responsive">
																	<table id="tweetReplies" class="table" style="width:100%;">
															<?php
																//Replies Sections.
																if(is_array($analyticData['replies']) && array_key_exists('noReplies',$analyticData['replies']) === false): 
															?>
															<thead>
																<tr>
																	<th class="text-center">#</th>
																		<th>ScreenName</th>
																		<th>Replay</th>
																		<th class="text-right">Quick Replay</th>
																		<th class="text-right">
																			<i class="fab fa-twitter"></i>
																		</th>
																	</tr>
															</thead>
																<tbody>

															<?php		
																	//There is replies in this tweet.
																	
																	foreach ($analyticData['replies'] as $replayKey => $replay) :
																		$replies = (object)$FrontHelperClass::extractTweetData($replay);
															?>	
																		<tr>
																			<td class="text-center"><?=(int)$replayKey+1;?></td>
																				<td>
																					<div class="author">
																						<img src="<?=$replies->user_profile;?>" class="avatar img-raised" alt="Avatar">
																							<span><?=$replies->name;?></span><br>
																							<a href="" style="color:red;"><?=$replies->replay_screen_name;?></a>
																					</div>
																					</td>
																					<td>
																						<?php
																							echo $replies->full_text;
																							if(is_null($replies->media) === false):
																								echo $replies->media;
																							endif;	
																						
																						?>
																						
																					
																					</td>
																					<td>
																						<form class='quickReplayForm'>
																							<div class="form-group">
																								<textarea class="form-control textarea-limited quickReplay"  style="resize:none;" name="tweetContent" rows="4" maxlength="280" ><?=$replies->replay_screen_name . "  ". $tweet->replay_screen_name;?></textarea><br>
																								<input type="hidden" name="tweet_id" value = "<?=$replies->tweet_id;?>"/>
																								<input type="hidden" name="publish" value = "true"/>
																								<button type="submit" class="btn btn-outline-info btn-sm quickReplayButton pull-left"><?=QUICK_REPLAY;?></button>
																							</div>
																						</form>

																					</td>
																					<td class="td-actions text-right">
																						<button   id="retweet_unretweet_<?=$replies->tweet_id;?>" data-twid = "<?=$replies->tweet_id;?>" data-action = '<?=$replies->retweet_type;?>' data-replay-context = "true"  <?=$replies->retweet_button_style;?>>
																							<i class="fas fa-retweet"></i>
																						</button>
																						<button   id="like_unlike_<?=$replies->tweet_id;?>" data-twid = "<?=$replies->tweet_id;?>" data-action = '<?=$replies->like_type;?>' data-replay-context = "true" class="<?=$replies->like_status;?>">
																							<i class="far fa-heart"></i>
																						</button>
																					</td>
																				</tr>	
																<?php																						
																	endforeach;
																else : 
																?>	
																<p style='color:red;'><?=NO_REPLIES_FOR_THIS_TWEET;?></p>
																<?php
																endif;
																?>
																		</tbody>
																		</table>
																	</div>
																</div>
																<!-- end row -->
																<!--- End Replies Table -->
															</div>
														</div>
													</div>
												</div>
											</div>
											<!--  end acordeon -->
										<?php  endif;?>											
