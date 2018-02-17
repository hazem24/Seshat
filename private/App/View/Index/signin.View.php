<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta charset="utf-8" />
    <meta content="ie=edge" http-equiv="x-ua-compatible" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="FullStory" name="author" />
    <meta content="website" property="og:type" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta content="@fullstory" name="twitter:site" />
    <meta content="@fullstory" name="twitter:creator" />
    <link href="https://www.fullstory.com/favicon.ico" rel="icon" type="image/x-icon" />
    <title>Seshat</title><link href="<?=ASSESTS_URI?>css/home/home.css" rel="stylesheet" />

  </head>
  <?php 
          $error = $this->session->getSession('error');
          if($error!==false):
                  foreach ($error as $key => $value) :
                            echo $value; //Design Must Be Here !.
                  endforeach;
           $this->session->unsetSession('error');       
          endif;        
  ?>
  <body class="cont love love_index" ontouchstart="">
    <div class="cont--gradient"></div>
    <div class="cont__inn">
      <div class="cont__content">
        <div class="head__cont">
          <a class="head__logo" href="/"><img alt="FullStory" src="/images/love/logo.svg" /></a>
        </div>
        <div class="content__cont">
          <div class="content__love">
            <?=AI_AS;?>
          </div>
          <h1 class="content__header">
           <?=PUT_BRAIN;?><br /><?=NAME;?>.
          </h1>
          
          <p class="content__text">
            <?=SESHAT_POWER;?>
          </p>
          
          <div class="content__btns">
            <a class="btn btn--regular btn--gradient btn--rounded" href="<?=$login_url->generatedUrl?>"><span class="btn--gradient__text"><?=SIGN_TWITTER;?></span></a><a class="btn btn--regular btn--underline btn--text" href="/features"> Learn More</a>
          </div>
          
        </div>
        
      </div>
      <div class="quote__cont">
        <div class="quote__cont__inn" id="quotesScroll">
          <div class="quote__cont__inn__row">
            <div class="quote__cont__col">
            
              
            
              
              <a class="quote__card" href="https://twitter.com/mcintosh33/status/898208034895400960" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/655066816054603776/H4voIQfL_normal.png');"></span><span class="quote__user">@mcintosh33</span><span class="quote__text">After 12 years of using <span class="quote__text__anchor">@googleanalytics</span> I completely switched to <span class="quote__text__anchor">@fullstory.</span> 12 yrs with any software is an incredible run! <span class="quote__text__anchor">#distruption</span></span><span class="quote__date">17 Aug 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/justin_schueler/status/898084326495178752" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/925387550860115968/eAfoi_Az_normal.jpg');"></span><span class="quote__user">@justin_schueler</span><span class="quote__text">we just integrated <span class="quote__text__anchor">@fullstory</span> at our new <span class="quote__text__anchor">@teamshirts_de</span> <span class="quote__text__anchor">#wizard</span> and it's 😵 great piece of software to get insights from our users 🔥</span><span class="quote__date">17 Aug 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/midito/status/874377252703797248" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/722410502261972992/W0xz8uaq_normal.jpg');"></span><span class="quote__user">@midito</span><span class="quote__text">I love <span class="quote__text__anchor">@fullstory</span> 😍</span><span class="quote__date">12 Jun 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/4.jpg');"></span><span class="quote__user">Jeremy Lavitt</span><span class="quote__text">FullStory pays for itself in spades. Every answer we get on what's happening with the checkout funnel is real money.</span><span class="quote__date">B&amp;H Photo Video</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/vbhartia/status/862711312110952450" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/717011546/Varun_-_Photo_normal.jpg');"></span><span class="quote__user">@vbhartia</span><span class="quote__text">I love <span class="quote__text__anchor">@fullstory!</span> Just set it up last week and already found a ton of bugs and things to improve. Telling my friends!</span><span class="quote__date">11 May 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/GAchenbach/status/852137921989816326" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/661656301684985856/TUoD4cWj_normal.jpg');"></span><span class="quote__user">@GAchenbach</span><span class="quote__text"><span class="quote__text__anchor">@fullstory</span> is a must have for product teams. 2 years into using it and I'm still amazed at the instant insights it can deliver.</span><span class="quote__date">12 Apr 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/Shpigford/status/837700684485099520" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/781560517596966912/UQQScxJa_normal.jpg');"></span><span class="quote__user">@Shpigford</span><span class="quote__text">One of the most transformative tools we’ve been using lately at <span class="quote__text__anchor">@baremetrics</span> is <span class="quote__text__anchor">@fullstory.</span> Amazing to have that data so readily available.</span><span class="quote__date">03 Mar 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/bpdunbar/status/831502110256676864" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/790625098164305920/G956GnPR_normal.jpg');"></span><span class="quote__user">@bpdunbar</span><span class="quote__text">Found the amazing tool called <span class="quote__text__anchor">@fullstory</span> Absolutely fantastic! I find it more valuable than Google Analytics.</span><span class="quote__date">14 Feb 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/designajvirgil/status/828996577104654336" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/845486433926303744/4s6Deh99_normal.jpg');"></span><span class="quote__user">@designajvirgil</span><span class="quote__text">Some people have the TV on while they work. I have <span class="quote__text__anchor">@fullstory</span> auto-playing on a second monitor.</span><span class="quote__date">07 Feb 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/LukePeerFly/status/826121423416651779" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/646400080778559489/Zl-a5I0s_normal.png');"></span><span class="quote__user">@LukePeerFly</span><span class="quote__text">I've been testing out <span class="quote__text__anchor">@FullStory</span> with their free trial and it is absolutely amazing application. One of the best user experiences I've had.</span><span class="quote__date">30 Jan 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/weswinham/status/783731527377006592" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/3631830113/9ae20a77e39ec5fca8bfe5e0015d01b0_normal.jpeg');"></span><span class="quote__user">@weswinham</span><span class="quote__text">What if you never again had to ask yours users to explain a problem they encountered? <span class="quote__text__anchor">@fullstory</span> feels like magic.</span><span class="quote__date">05 Oct 2016</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/johnwright79/status/760497706108350464" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/1979124147/johnwright79_normal.jpg');"></span><span class="quote__user">@johnwright79</span><span class="quote__text">If you run a website then I highly recommend <span class="quote__text__anchor">@fullstory,</span> I signed up an hour ago and already my mind is blown. This is amazing!</span><span class="quote__date">02 Aug 2016</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/graysky/status/693148943153242113" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/618427316146868225/Dbv09hjH_normal.jpg');"></span><span class="quote__user">@graysky</span><span class="quote__text">Was convinced today that if I was an investor I would give <span class="quote__text__anchor">@fullstory</span> all my money. Just saved me tons of time debugging a customer issue</span><span class="quote__date">29 Jan 2016</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/mhmazur/status/649228894520877056" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/940050855906000896/G3ClDbY-_normal.jpg');"></span><span class="quote__user">@mhmazur</span><span class="quote__text">"Any sufficiently advanced technology is indistinguishable from magic" - how I feel after trying <span class="quote__text__anchor">@FullStory,</span> a new UX insights tool</span><span class="quote__date">30 Sep 2015</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/katzboaz/status/609315889272389632" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/538799522005008384/hhuiBVYF_normal.jpeg');"></span><span class="quote__user">@katzboaz</span><span class="quote__text"><span class="quote__text__anchor">@fullstory</span> it is not us that need to thank you. It is our customers. We are learning things that no other analytical service could provide</span><span class="quote__date">12 Jun 2015</span></a>
              
            
            </div>
            <div class="quote__cont__col">
            
              
              <a class="quote__card" href="https://twitter.com/CallMeWuz/status/905508869945909249" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/902989465471385600/xCZqYeNm_normal.jpg');"></span><span class="quote__user">@CallMeWuz</span><span class="quote__text">We use <span class="quote__text__anchor">@fullstory</span> at work and, I gotta say, it's pretty awesome. Just discovered a small error by watching a user session.</span><span class="quote__date">06 Sep 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/13.jpg');"></span><span class="quote__user">Sebastian Tonkin</span><span class="quote__text">FullStory helps us uncover the truth behind the assumptions we're forced to make with analytics.</span><span class="quote__date">Zenefits</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/zacknotes/status/889536591013851137" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/378800000563220516/8386c13ee64311a7cdf554dd08f6afd5_normal.jpeg');"></span><span class="quote__user">@zacknotes</span><span class="quote__text">Finding <span class="quote__text__anchor">@fullstory</span> to be a really effective QA tool recently, even for code that's not live yet.</span><span class="quote__date">24 Jul 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/2.jpg');"></span><span class="quote__user">Chris Savage, CEO</span><span class="quote__text">FullStory is magic. It gives us a level of insight that we can't get anywhere else. It's absolutely invaluable.</span><span class="quote__date">Wistia</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/malgon007/status/870310342894682113" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/780381472632999936/CoTnwlFK_normal.jpg');"></span><span class="quote__user">@malgon007</span><span class="quote__text">Today I've started to use <span class="quote__text__anchor">@fullstory</span> app - and I've discovered a whole new universe!</span><span class="quote__date">01 Jun 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/8.jpg');"></span><span class="quote__user">Seamus James</span><span class="quote__text">Operating an e-commerce website without using a tool like FullStory is like running a brick-and-mortar store with the lights off.</span><span class="quote__date">Betabrand</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/maggiecrowley/status/843105442163310592" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/610499482808152064/gcKFz4Y0_normal.png');"></span><span class="quote__user">@maggiecrowley</span><span class="quote__text">New Saturday morning: ☕️ + watching the latest magic sent my way by the brilliant <span class="quote__text__anchor">@fullstory</span></span><span class="quote__date">18 Mar 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/19.jpg');"></span><span class="quote__user">Emeric Ernoult</span><span class="quote__text">If you're serious about providing top notch support and user experience, FullStory is a must have. It's the kind of tool you don't know you need until you experience it for the first time and think "Oh shit! I had no idea we had that problem."</span><span class="quote__date">Agorapulse</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/andrewchilds/status/829046741597032448" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/728426422138638336/X1idNF9q_normal.jpg');"></span><span class="quote__user">@andrewchilds</span><span class="quote__text">Actual wizards may have been responsible for creating <span class="quote__text__anchor">@fullstory,</span> because it is magic.</span><span class="quote__date">07 Feb 2017</span></a>
              
            
              
            
              
              <a class="quote__card" ><span class="quote__img" style="background-image: url('/images/love/user/16.jpg');"></span><span class="quote__user">James Doman-Pipe</span><span class="quote__text">It's been a massive timesaver. With FullStory, we can move at 4x the speed we used to.</span><span class="quote__date">Kayako</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/ChairmanHu/status/825531556974428165" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/912687969399660547/AN_2badt_normal.jpg');"></span><span class="quote__user">@ChairmanHu</span><span class="quote__text">I watch <span class="quote__text__anchor">@fullstory</span> more than I watch TV.</span><span class="quote__date">29 Jan 2017</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/jlaurenswanson/status/761258852855656448" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/721023908732735490/vAJ-PEys_normal.jpg');"></span><span class="quote__user">@jlaurenswanson</span><span class="quote__text"><span class="quote__text__anchor">@fullstory</span> I can't remember the last time I enjoyed using a software tool this much!</span><span class="quote__date">04 Aug 2016</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/jlvanhulst/status/705536885410635777" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/1778527521/jl_normal.jpg');"></span><span class="quote__user">@jlvanhulst</span><span class="quote__text">Humbled by what <span class="quote__text__anchor">@fullstory</span> reveals about website visitors. For e-Commerce sites I can see now it's a must have. <span class="quote__text__anchor">#surprise</span></span><span class="quote__date">03 Mar 2016</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/KevinMandeville/status/656553027525091328" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/813838616468291585/T8LcUcaJ_normal.jpg');"></span><span class="quote__user">@KevinMandeville</span><span class="quote__text">I can't reiterate how valuable <span class="quote__text__anchor">@fullstory</span> is for *every* area of co - support, product, eng - amazing to see everyone use in diff ways</span><span class="quote__date">20 Oct 2015</span></a>
              
            
              
            
              
              <a class="quote__card" href="https://twitter.com/nainig/status/627235417406832640" target="_blank"><span class="quote__img" style="background-image: url('https://pbs.twimg.com/profile_images/796852917470756865/actzi-41_normal.jpg');"></span><span class="quote__user">@nainig</span><span class="quote__text"><span class="quote__text__anchor">@fullstory</span> Your instant feedback just shortened my iterations by weeks! Where have you been all my product building life!! :)</span><span class="quote__date">31 Jul 2015</span></a>
              
            
              
            
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
    
    <div class="content__note">
      <div class="content__note__inn">
        <p class="content__note__text">
        <a class="btn btn--regular btn--underline btn--text" href="/customers">What Seshat Can Do?</a><br>
         <a class="btn btn--regular btn--underline btn--text" href="/customers">Price</a><br>
        <a class="btn btn--regular btn--underline btn--text" href="/customers">Privacy & Terms Of Use</a>
        </p>
      </div>
    </div><script src="<?=ASSESTS_URI?>js/home/home.js"></script>
  </body>
</html>
