angular.module('seshatApp').controller("twitterprofileCtrl",function ($scope , profileReader) {
   
    profileReader.getProfile( "getTwitterProfile" , $scope.user_name , function ( response ) {
        $scope.data = response.data;
        if($scope.data.error !== undefined || $scope.data.AppError !== undefined){
            globalMethod.repsonseError($scope.data);
        }
        spinner.removeSpinner('.spinner');
    });

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
    };

    $scope.createRelation = function ( $type , $user_id ) {
        $response = twitterAction.createRelation( $type , $user_id , true);
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
    $scope.tweetAs = function ( $screen_name  , $lang) {
        $button_content = $("#tweetAs").html();
        spinner.button($("#tweetAs"), '<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        profileReader.tweetAs( $screen_name , $lang  , function ( response ) {
            if(response.data.error !== undefined){
                globalMethod.repsonseError( response.data );
            }else if ( response.data.tweet_as_success !== undefined){
                globalMethod.showNotification('success','top','Right',response.data.tweet_as_success,'body',5000);
            }
            spinner.remove($("#tweetAs"), $button_content , false , true);
        });
    };
}).directive("twitterProfilePage",function(){
    return {
        templateUrl : template_url + "profile/twitter/twitter-profile-page.component.html",
        restrict    : "E",
        replace     : false
        };
});