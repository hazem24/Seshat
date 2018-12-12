angular.module("seshatApp").controller("globalTwitterCtrl",function ( $scope ) {

    
    $scope.retweetLogic = function ($event , $cached = false) {
        twitterAction.retweetLogic($event.currentTarget,$cached);
    };

    $scope.likeLogic = function ($event , $cached = false) {
        twitterAction.likeLogic($event.currentTarget,$cached);
    };

    $scope.replayLogic = function($event){
        $tweet_id  = $event.currentTarget.dataset.twid;
        $replay_to = $event.currentTarget.dataset.replayTo;
        twitterAction.replayLogic($tweet_id , $replay_to);
    };

    $scope.translateTweet = function (element) {
        globalMethod.translateTweet($('#content' + element));
    };

    $scope.copyTweet = function ($event){
        globalMethod.copyTweet($event.currentTarget);
    };

    $scope.deleteTweet = function ( $tweet_id ,  $index){
        twitterAction.deleteTweet( $tweet_id , $("#tweet"+$index));
    };


    $scope.relationOnFly = function ( $type , $user_id , $childScope  , $index , $feature = false) { //$index for ng-repeat.
        $response = twitterAction.createRelation( $type , $user_id , true);
        switch ($type.toLowerCase()) {
            case 'follow':
                $childScope.usersResults.users[$index].following = true;
                break;
            case 'unfollow':
                $childScope.usersResults.users[$index].following = false;
                break;
            default:
                //nothing.
                break;
        }
        if ( $feature !== false) {
            //update localstorge for this feature.
            localStorage.setItem( $feature , JSON.stringify( $childScope.usersResults ) );
        }
    };
});