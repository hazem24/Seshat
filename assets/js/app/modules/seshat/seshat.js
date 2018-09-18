angular.module("seshatApp").controller("seshatCtrl",function( $scope , seshatService){
    $timeline = String(document.location).toLowerCase().indexOf("!seshattimeline");
    $controlFollowers = String(document.location).toLowerCase().indexOf("controlfollowers");
    $scope.feature_type = false;// flag for uses feature type must be change to search about element.

    if ( $timeline > 0 ){
        //get Timeline data.
        seshatService.getTimeLine(function ( response ) {
            if( response.data.error !== undefined ){
                $scope.results = response.data;
                globalMethod.repsonseError( response.data );
            }else if ( response.data.results !== undefined ){
                $scope.results = response.data.results;
            }
            spinner.removeSpinner('.spinner');
        });
    }

    if ( $controlFollowers > 0 ) {
        //get type of followers.
        $scope.feature_type = String(document.location).split("/");
        $scope.feature_type =  $scope.feature_type[ $scope.feature_type.length - 1 ];

        //get from storage.
        if (!Cookies.get($scope.feature_type)) {
            // get data from server.
            seshatService.controlFollowers( String(document.location) , function ( $response ) {
                //save as task.
                $scope.saveTask = true;//uses this to appear save task button.
                if ( $response.data.results !== undefined ){
                    $scope.results = $response.data.results;
                    localStorage.setItem($scope.feature_type,JSON.stringify($response.data.results));
                    localStorage.setItem("saveTask",true);
                    Cookies.set($scope.feature_type , 1 , { expires: 0.5 * 0.125 * 1 } );
                }else { //error happen.
                    $scope.results = $response.data;
                    globalMethod.repsonseError( $response.data );
                }
                spinner.removeSpinner('.spinner');
            });
        } else {
            $scope.results  = JSON.parse(localStorage.getItem( $scope.feature_type ));// get data from storage.
            $scope.saveTask = localStorage.getItem( "saveTask" ); 
        }
        //saveTask Button.
        $scope.saveTaskAction = function( $feature_type ){
            $order = $scope.results.users.length;
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

    //getRelation.
    $scope.getRelation = function ( $source , $target ) {
        $text = $("#checkRelation").html();
        $check_relation_button = $("#checkRelation");
        spinner.button($check_relation_button,'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        seshatService.getRelation( String(document.location) , $source , $target , function ( $response ) {
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
    if (angular.element("#accounts-statistics").length > 0){
        seshatService.accountsStatistics( function ( $response ){
            console.log( $response.data );
        } );
    }
    //End statistics.
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
        $http.get( $url + "/json" ).then( $callback );
    };
    //End controlFollowers Services. 

    //get Relation.
    this.getRelation = function ( $url , $source , $target , $callback ) {
        $http.get($url  + "/json/" + encodeURIComponent($source) + "/" + encodeURIComponent($target)).then($callback);
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
    
});