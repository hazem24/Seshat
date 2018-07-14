angular.module('seshatApp').controller("twitterprofileCtrl",function ($scope , $sce , profileReader) {
    $scope.renderHtml = function(html_code) {
        return $sce.trustAsHtml(html_code);
    }

    profileReader.getProfile( "getTwitterProfile" , $scope.user_name , function ( response ) {
        $scope.data = response.data;
        if($scope.data.error !== undefined || $scope.data.AppError !== undefined){
            globalMethod.repsonseError($scope.data);
        }
        spinner.removeSpinner('.spinner');
    });
    $scope.retweetLogic = function ($event) {
        twitterAction.retweetLogic($event.currentTarget,false);
    };

    $scope.calculateFakeFollowers = function ( $screen_name ) {
        $button_text = $("#fakeAccountsCalc").html();
        spinner.button($("#fakeAccountsCalc"), '<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');
        profileReader.calculateFakeAccounts( $screen_name  , function( response ){
            $scope.fakeReport = response.data;
            if ( $scope.fakeReport.fakeFollowersReport !== undefined ){
                $("main").append($scope.fakeReport.fakeFollowersReport);//Inject to body.
                $("#fakeAccountsReport").modal();
            }else{
                //ERROR HERE.
                globalMethod.repsonseError($scope.fakeReport);
            }
            spinner.remove( $("#fakeAccountsCalc") , $button_text );
        });
    }

    $scope.tweetThis = function ($selector) {
        $img = globalMethod.screenShot($selector);
        console.log( $img );
    }
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

    $scope.createRelation = function ( $type , $user_name ) {
        $response = twitterAction.createRelation( $type , $user_name , true);
        switch ($type.toLowerCase()) {
            case 'follow':
                if($scope.data.profile.protected === false){
                    $scope.data.profile.following = true;
                    $scope.data.profile.follow_request_sent = false;     
                }else {
                    $scope.data.profile.follow_request_sent = true;
                }
                break;
            case 'unfollow':
                $scope.data.profile.following = false;
                break;
            case 'follow_request_sent':
                $scope.data.profile.follow_request_sent = true;
                break;    
            default:
                break;
        }
    };
}).directive("twitterProfilePage",function(){
    return {
        templateUrl : template_url + "profile/twitter/twitter-profile-page.component.html",
        restrict : "E",
        replace : false
    };
});