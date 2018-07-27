angular.module("seshatApp").controller("globalTwitterCtrl",function ( $scope ) {

    $scope.retweetLogic = function ($event) {
        twitterAction.retweetLogic($event.currentTarget,false);
    };

    $scope.likeLogic = function ($event) {
        twitterAction.likeLogic($event.currentTarget,false);
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

    $scope.relationOnFly = function ( $type , $user_id , $childScope  , $index) { //$index for ng-repeat.
        $response = twitterAction.createRelation( $type , $user_id , true);
        switch ($type.toLowerCase()) {
            case 'follow':
                $childScope.results.users[$index].following = true;
                break;
            case 'unfollow':
                $childScope.results.users[$index].following = false;
                break;
            default:
                //nothing.
                break;
        }
    };
});