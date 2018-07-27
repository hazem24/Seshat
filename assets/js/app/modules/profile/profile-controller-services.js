angular.module("seshatApp").controller("socialMediaprofileCtrl",function ($scope , $sce ,  $location , $window) {
   

    $scope.location = $location;
    $scope.window   = $window;
    $scope.url = $location.absUrl();
    $scope.user_name = $scope.url.substr($scope.url.lastIndexOf('/') + 1); //Get userName.  
}).service("profileReader",function($http){
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    //Twitter Section.
    this.getProfile = function ( $profile_type , $user_name , $callback) {
        spinner.onPageLoad(true);
        $http.get(BASE_URL + "!profile/" + $profile_type + "/" + $user_name).then($callback);
    };

    this.calculateFakeAccounts = function ( $screen_name , $callback ) {
        $http.get(BASE_URL + "!profile/fakeAccounts/twitter/"+$screen_name).then($callback);
    };

    this.tweetAs = function ( $screen_name , $lang , $callback ) {
        $http.post(BASE_URL + "!profile/tweetAs/",'tweetAs='+$screen_name+"&translate_tweets_to="+$lang).then( $callback );
    }
    //End Twitter Section.
});