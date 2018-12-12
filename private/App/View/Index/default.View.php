<!DOCTYPE html>
<html lang=<?=$_COOKIE['lang'] ?? 'en'?>>
<head>
<meta charset=utf-8>
<meta name=viewport content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name=description content>
<meta name=author content=Webpixels>
<title><?=NAME;?></title>
<link href=<?=ASSESTS_URI?>img/seshat.png rel=icon type=image/png>
<link href="//fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel=stylesheet>
<link rel=stylesheet href=//use.fontawesome.com/releases/v5.1.0/css/all.css integrity=sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt crossorigin=anonymous>
<link id=mainView href=<?=ASSESTS_URI?>css/app/index.css rel=stylesheet>
</head>
<body ng-app=seshatApp ng-controller=appCtrl>
<header class=header-transparent id=header-main>
<div id=navbar-top-main class="navbar-top navbar-dark bg-dark border-bottom">
<div class=container>
<div class="navbar-nav align-items-center">
<div class="d-none d-lg-inline-block">
<span class="navbar-text mr-3"><?=NAME?></span>
</div>
<div>
<ul class=nav>
<li class="nav-item dropdown ml-lg-2 dropdown-animate" data-toggle=hover>
<a class="nav-link px-0" role=button data-toggle=dropdown aria-haspopup=true aria-expanded=false>
<img alt="Image placeholder" src=<?=ASSESTS_URI?>/img/flags/us.svg>
<span class="d-none d-lg-inline-block"><?=LANG;?></span>
</a>
<div class="dropdown-menu dropdown-menu-sm dropdown-menu-arrow">
<a ng-click='changeLang("en")' class=dropdown-item>
<img alt="Image placeholder" src=<?=ASSESTS_URI?>/img/flags/us.svg>English</a>
<a ng-click='changeLang("ar")' class=dropdown-item>
<img alt="Image placeholder" src=<?=ASSESTS_URI?>/img/flags/ae.svg>العربيه</a>
</div>
</li>
</ul>
</div>
</div>
</div>
</div>
<?php 
$error = $this->session->getSession('error');
if($error!==false):
foreach ($error as $key => $value) :
// Flash error must be here.
?>
<div class="alert alert-danger alert-dismissible fade show" role=alert>
<span class=alert-inner--icon><i class="fas fa-times"></i></span>
<span class=alert-inner--text><strong>ERROR</strong><?=$value?></span>
<button type=button class=undo aria-label=Undo>Undo</button>
<button type=button class=close data-dismiss=alert aria-label=Close>
<span aria-hidden=true>&times;</span>
</button>
</div>
<?php
endforeach;
$this->session->unsetSession('error');       
endif;        
  ?>
<nav class="navbar navbar-main navbar-expand-lg navbar-sticky navbar-transparent navbar-dark bg-dark" id=navbar-main>
<div class=container>
<a class="navbar-brand mr-lg-5" href=#>
<img alt=seshat src=<?=ASSESTS_URI?>img/seshat_s.png style=height:50px>
</a>
<button class=navbar-toggler type=button data-toggle=collapse data-target=#navbar-main-collapse aria-controls=navbar-main-collapse aria-expanded=false aria-label="Toggle navigation">
<span class=navbar-toggler-icon></span>
</button>
<div class="collapse navbar-collapse" id=navbar-main-collapse>
<ul class="navbar-nav align-items-lg-center">
<li class=nav-item>
<a class=nav-link href><?=OVERVIEW?></a>
</li>
<li class="nav-item dropdown dropdown-animate" data-toggle=hover>
<a class=nav-link href=# role=button data-toggle=dropdown aria-haspopup=true aria-expanded=false><?=WIKI_MEDIA?></a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-arrow py-0">
<div class=list-group>
<a href=# class="list-group-item list-group-item-action">
<div class="media d-flex align-items-center">
<img alt="Image placeholder" src=<?=ASSESTS_URI?>img/icons/dusk/png/stairs-up.png class=img-saturate style=width:50px>
<div class="media-body ml-3">
<h6 class=mb-1><?=INCREASE_YOUR_FOLLOWERS?></h6>
<p class=mb-0><?=LEARN_WITH_VIDEOS?></p>
</div>
</div>
</a>
<a href=# class="list-group-item list-group-item-action">
<div class="media d-flex align-items-center">
<img alt="Image placeholder" src=<?=ASSESTS_URI?>img/icons/dusk/png/realtime.png class=img-saturate style=width:50px>
<div class="media-body ml-3">
<h6 class=mb-1><?=REAL_TIME_DATA;?></h6>
<p class=mb-0><?=LEARN_WITH_VIDEOS?>.</p>
</div>
</div>
</a>
<a href=# class="list-group-item list-group-item-action">
<div class="media d-flex align-items-center">
<img alt="Image placeholder" src=<?=ASSESTS_URI?>img/icons/dusk/png/four-beds.png class=img-saturate style=width:50px>
<div class="media-body ml-3">
<h6 class=mb-1><?=TASKS_OPERATION;?></h6>
<p class=mb-0><?=CREATE_TASKS_GO_SLEEP?>.</p>
</div>
</div>
</a>
</div>
</div>
</li>
<li class=nav-item>
<a class=nav-link href=<?=BASE_URL.'!index/price'?>><?=PRICE?></a>
</li>
</ul>
<ul class="navbar-nav align-items-lg-center ml-lg-auto">
<li class="nav-item mr-0">
<a href=# target=_blank class="nav-link d-lg-none">Beta</a>
<a href target=_blank class="btn btn-sm btn-white btn-circle btn-icon d-none d-lg-inline-flex">
<span class=btn-inner--text><?=BETA;?></span>
</a>
</li>
</ul>
</div>
</div>
</nav>
</header>
<main>
<section class="position-relative pb-lg-lg pt-lg-xl bg-dark">
<div class="bg-img-holder top-0 right-0 col-lg-6 zindex-100" data-bg-size=cover>
<img alt="Image placeholder" src=<?=ASSESTS_URI?>img/seshat.png>
</div>
<div class="container py-md">
<div class=row>
<div class=col-lg-5>
<div class="text-center text-lg-left">
<h2 class="heading h1 text-white mb-3"><?=AI_AS;?></h2>
<p class="lead lh-180 text-white"><?=PUT_BRAIN;?><br /><?=NAME;?>.</p>
<a class="btn btn-white btn-circle mt-4" href=<?=$login_url->generatedUrl?>><span class=btn-inner--icon><i class="fab fa-twitter"></i></span> <?=SIGN_TWITTER;?></a>
</div>
</div>
</div>
</div>
</section>
<section class="position-relative pb-lg-lg pt-lg-lg bg-secondary">
<div class="bg-img-holder top-0 left-0 col-lg-6 zindex-100" data-bg-size=cover>
<img alt="Image placeholder" src=<?=ASSESTS_URI?>img/seshat.png>
</div>
<div class="container py-md">
<div class=row>
<div class="col-lg-5 ml-lg-auto">
<div class="text-center text-lg-left">
<h2 class="heading h1 mb-3"><?=SECTION_ONE?></h2>
<p class="lead lh-180" style=color:red><?=SECTION_ONE_DETAILS?></p>
</div>
</div>
</div>
</div>
</section>
<section class="slice slice-lg">
<div class=container>
<div class="row row-grid">
<div class=col-lg-4>
<div class=card>
<div class="card-body py-5">
<div class="d-flex align-items-start">
<div class=icon>
<i class="fas fa-hashtag"></i>
</div>
<div class=icon-text>
<h5 class><?=TRACK_HASHTAGS?></h5>
<p class=mb-0><?=TRACK_ANY_HASHTAGS?></p>
</div>
</div>
</div>
</div>
</div>
<div class=col-lg-4>
<div class=card>
<div class="card-body py-5">
<div class="d-flex align-items-start">
<div class=icon>
<i class="fas fa-users"></i>
</div>
<div class=icon-text>
<h5 class><?=INCREASE_YOUR_FOLLOWERS?></h5>
<p class=mb-0><?=INCREASE_YOUR_FOLLOWERS_TREES?></p>
</div>
</div>
</div>
</div>
</div>
<div class=col-lg-4>
<div class=card>
<div class="card-body py-5">
<div class="d-flex align-items-start">
<div class=icon>
<i class="fas fa-clock"></i>
</div>
<div class=icon-text>
<h5 class><?=SCHEDULE_POSTS?></h5>
<p class=mb-0><?=SCHEDULE_POSTS?></p>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row row-grid">
<div class=col-lg-4>
<div class=card>
<div class="card-body py-5">
<div class="d-flex align-items-start">
<div class=icon>
<i class="fas fa-robot"></i>
</div>
<div class=icon-text>
<h5 class><?=FAKE_ACCOUNTS?></h5>
<p class=mb-0><?=KNOW_FAKE_ACCOUNTS?></p>
</div>
</div>
</div>
</div>
</div>
<div class=col-lg-4>
<div class=card>
<div class="card-body py-5">
<div class="d-flex align-items-start">
<div class=icon>
<i class="fas fa-tasks"></i>
</div>
<div class=icon-text>
<h5 class><?=TASKS_OPERATION?></h5>
<p class=mb-0><?=CREATE_TASKS?></p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
</main>
<footer class="footer footer-dark bg-gradient-primary">
<div class=container>
<div class="row align-items-center justify-content-md-between py-4 mt-4 delimiter-top">
<div class=col-md-6>
<div class="copyright text-sm font-weight-bold text-center text-md-left">
&copy; 2018 <a href class=font-weight-bold target=_blank>Seshat</a>. All rights reserved.
</div>
</div>
</div>
</div>
</footer>
<script src=<?=ASSESTS_URI."js"?>/lib/jquery-3.2.1.min.js></script>
<script src=<?=ASSESTS_URI?>vendor/bootstrap/dist/js/bootstrap.bundle.min.js></script>
<script src=//ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js></script>
<script src=//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.6/angular-route.min.js></script>
<script src=<?=ASSESTS_URI?>vendor/in-view/dist/in-view.min.js></script>
<script src=<?=ASSESTS_URI."js"?>/lib/exporterJson.js type=text/javascript></script>
<script src=<?=ASSESTS_URI?>js/app/index.js></script>
<script src=<?=ASSESTS_URI."js"?>/app/modules/app.js></script>
</body>
</html>