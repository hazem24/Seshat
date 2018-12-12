angular.module("seshatApp").controller("seshatCtrl",function( $scope , seshatService , $location, $routeParams){

    $scope.feature_type = false;// flag for uses feature type must be change to search about element.
    $scope.loading      = true;// flag detemined that page is loading or send request for data.
    
    // Define Functions.
    $scope.getTimeLine = function($url){
        if (  $url.indexOf("#!/timeline") > 0 ){
            //get Timeline data.
            seshatService.getTimeLine(function ( response ) {
                if( response.data.error !== undefined ){
                    $scope.timeline = response.data;
                    globalMethod.repsonseError( response.data );
                }else if ( response.data.results !== undefined ){
                    $scope.timeline = response.data.results;
                }
                spinner.removeSpinner('.spinner');
            });
        }    
    };  

    $scope.controlFollowers = function($url){
        if ( $url.indexOf("#!/controlfollowers") > 0 ) {
            //get type of followers.
            $scope.feature_type = $url.split("/");
            $scope.feature_type =  $scope.feature_type[ $scope.feature_type.length - 1 ];    
            //get from storage.
            if (!Cookies.get($scope.feature_type)) {
                // get data from server.            
                seshatService.controlFollowers( BASE_URL + "!seshat/controlFollowers/twitter/" + $scope.feature_type + "/json", function ( $response ) {
                    //save as task.
                    $scope.saveTask = true;//uses this to appear save task button.
                    if ( $response.data.results !== undefined ){
                        $scope.usersResults = $response.data.results;
                        localStorage.setItem($scope.feature_type,JSON.stringify($response.data.results));
                        localStorage.setItem("saveTask",true);
                        Cookies.set($scope.feature_type , 1 , { expires: 0.5 * 0.125 * 1 } );
                    }else { //error happen.
                        $scope.usersResults = $response.data;
                        globalMethod.repsonseError( $response.data );
                    }
                    spinner.removeSpinner('.spinner');
                });
            } else {
                $scope.usersResults  = JSON.parse(localStorage.getItem( $scope.feature_type ));// get data from storage.
                $scope.saveTask = localStorage.getItem( "saveTask" ); 
            }
            //saveTask Button.
            $scope.saveTaskAction = function( $feature_type ){
                $order = $scope.usersResults.users.length;
                $saveTaskButton = $("#saveAsTask");
                $text   = $("#saveAsTask").html();
                spinner.button($saveTaskButton,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
                seshatService.controlFollowersTask( $feature_type , $order , function ( $response ) {
                    if ( $response.data.success !== undefined ){
                        globalMethod.showNotification('success','top','Right',$response.data.success,'body',20000);
                    }else if ( $response.data.error !== undefined ){
                        globalMethod.repsonseError( $response.data );
                    }else {
                        globalMethod.showNotification('danger','top','Right','App Error try again later','body',20000);
                    }
                    spinner.remove( $saveTaskButton , $text , false , true );
                });
            };
            //End saveTask Button.
        }
    };
    

    //getRelation.
    $scope.getRelation = function ( $source , $target ) {
        $text = $("#checkRelation").html();
        $check_relation_button = $("#checkRelation");
        spinner.button($check_relation_button,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        seshatService.getRelation( BASE_URL + "!seshat/checkFriends/twitter/json" , $source , $target , function ( $response ) {
            if ( $response.data.error !== undefined ){
                globalMethod.repsonseError( $response.data );
            }else {
                $scope.relation = $response.data;
            }
            spinner.remove( $check_relation_button , $text , false , true );
        } );
    }
    //End getRelation.

    //seshat statistics.
    $scope.getStatistics = function($url){
        if ($url.indexOf('#!/statistics') > 0){
            spinner.onPageLoad(true);
            seshatService.accountsStatistics( function ( $response ){
                $scope.loading = false;
                if( $response.data.now !== undefined ){
                    $scope.statistics      = $response.data;
                    $scope.followers_delta = $scope.statistics.now.statistics.twitter.followers    -  $scope.statistics.past.statistics.twitter.followers;
                    $scope.tweets_delta    = $scope.statistics.now.statistics.twitter.tweet_count  -  $scope.statistics.past.statistics.twitter.tweet_count;
                    $scope.following_delta = $scope.statistics.now.statistics.twitter.following    -  $scope.statistics.past.statistics.twitter.following;
                }else{
                    globalMethod.repsonseError( $response.data );
                }
                spinner.removeSpinner('.spinner');
            } );
        }
    };
    //End statistics.

    //seshat account activity ( Notifications ).
    $scope.activity = function($url){
        if ($url.indexOf("activity") > 0){
            spinner.onPageLoad(true);
            seshatService.accountActivity( function( $response ){
                $scope.accountActivity = $response.data;
                spinner.removeSpinner('.spinner');
            } );
        }
        //End account activity.
    };

    //seshat account tasks.
    $scope.accountTasks = function($url){
        if ($url.indexOf('#!/tasks') > 0){
            spinner.onPageLoad(true);
            seshatService.accountTasks( function( $response ){
                $scope.loading = false;
                if ( $response.data.error !== undefined ){
                    globalMethod.repsonseError( $response.data );
                }else {
                    $scope.tasks_data = $response.data;
                }
                spinner.removeSpinner('.spinner');
            } );
        }
    };
    //delete Tasks.

    $scope.accountInformation = function($url){
        if ($url.indexOf('#!/settings') > 0){
            spinner.onPageLoad(true);
            seshatService.accountInformation( function( $response ){
                $scope.loading = false;
                console.log( $response.data );
                if ( $response.data.error !== undefined ){
                    globalMethod.repsonseError( $response.data );
                }else {
                    $scope.userAccountData = $response.data;
                }
                spinner.removeSpinner('.spinner');
            } );
        }
    };

    $scope.deleteTask = function ( $task_id ) {
        $text = $("#deleteTask" + $task_id).html();
        $delete_task_button = $("#deleteTask" + $task_id);
        spinner.button($delete_task_button,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        seshatService.deleteTask( $task_id , function ( $response ) {
            if ( $response.data.task_deleted !== undefined ){
                angular.element("#task"+$task_id).remove();    
                $scope.tasks_data.length =  $scope.tasks_data.length - 1;
            }else if ( $response.data.task_not_deleted !== undefined ){
                globalMethod.showNotification('danger','top','Right',$response.data.task_not_deleted,"body",20000);   
            }else{
                globalMethod.showNotification('danger','top','Right',"Error Happen Please Try again later.","body",20000);
            }
            spinner.remove( $delete_task_button , $text , false , true );
        } );
    };
    //End delete tasks.
    //End    account tasks. 
    //Run main Functions.
    $scope.getTimeLine(BASE_URL + '!seshat/' + '#!' + $location.path());
    $scope.getStatistics(BASE_URL + '!seshat/' + '#!' + $location.path());
    $scope.controlFollowers(BASE_URL + '!seshat/' + '#!' + $location.path());    
    $scope.accountTasks(BASE_URL + '!seshat/' + '#!' + $location.path());
    $scope.activity(BASE_URL + '!seshat/' + '#!' + $location.path());
    $scope.accountInformation(BASE_URL + '!seshat/' + '#!' + $location.path());
    //End Run Main Functions.
}).directive("timeline",function (){
    return {
        templateUrl : template_url + "feed/posts.component.html",
        restrict : "E",
        replace : false
    };
}).directive("controlFollowers",function () {
    return {
        templateUrl : template_url + "feed/users.component.html",
        restrict    : "E",
        replace     : false
    };
}).directive("relation",function () {
    return {
        templateUrl : template_url + "feed/checkRelation.component.html",
        restrict    : "E",
        replace     : false
    };
}).directive("accountsStatistics",function (){
    return {
        templateUrl : template_url + "statistics/accounts/accounts-statistics.component.html",
        restrict    : "E",
        replace     : false
    };
}).directive("accountActivity",function (){
    return {
        templateUrl : template_url + "activity/account-activity.component.html",
        restrict    : "E",
        replace     : false
    };
}).directive("tasks",function (){
    return {
        templateUrl : template_url + "tasks/tasks.component.html",
        restrict    : "E",
        replace     : false
    };
}).service("seshatService",function ( $http ){
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    //get Timeline.
    this.getTimeLine = function ( $callback ) {
        spinner.onPageLoad( true );
        $http.get(BASE_URL + "!seshatTimeline/default/getTimeLineDataAsJson").then( $callback );
    //End TimeLine.
    };
    //controlFollowers Services.
    this.controlFollowers = function ( $url , $callback ) {
        spinner.onPageLoad( true );
        $http.get($url).then( $callback );
    };
    //End controlFollowers Services. 

    //get Relation.
    this.getRelation = function ( $url , $source , $target , $callback ) {
        $http.get($url + "/" + encodeURIComponent($source) + "/" + encodeURIComponent($target)).then($callback);
    };
    //End get Relation.
    
    //saveControlFollowersTask.
    this.controlFollowersTask = function (  $type , $order , $callback ){
        $http.post(BASE_URL + "!seshat/controlFollowersTask","taskType="+$type +"&order="+$order).then( $callback );
    };
    //End ControlFollowersTask.

    //get accounts statistics.
    this.accountsStatistics = function ($callback){
        $http.get(BASE_URL + "!seshat/statistics?getStatistics=true").then( $callback );
    };
    //End accounts statistics.

    //get account information.
    this.accountInformation = function ($callback){
        $http.get(BASE_URL + "!seshat/account?get=getAccountInformation").then( $callback );
    };
    //End account information.

    //get account activity .. user notifications the same as account activity .. what seshat do in user accounts.
    this.accountActivity = function ( $callback ){
        $http.get( BASE_URL + "!seshat/account?get=getUserNotifications" ).then( $callback );
    };
    //End account activity.

    //get tasks of specific account.
    this.accountTasks = function ( $callback ){
        $http.get( BASE_URL + "!seshat/account?get=tasks" ).then( $callback );
    };
    //End tasks.

    //delete task is ready just implement it with the design.
    this.deleteTask = function ( $task_id ,  $callback ){
        $http.post(BASE_URL + "!seshat/deleteTask","task_id="+$task_id).then( $callback );
    };
    //end delete task.
});