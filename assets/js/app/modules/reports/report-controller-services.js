angular.module('seshatApp').controller('reportCtrl',function($scope,$location,$window){
    $scope.location = $location;
    $scope.window   = $window;
    $scope.url = $location.absUrl();
    $scope.report_name = $scope.url.substr($scope.url.lastIndexOf('/') + 1); //Get Report name.  
    
}).service("reportServices",function ($http){
    //Depen. injection style.
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";


    //Hashtag report section.

    //Read hashtag.
    this.getHashtagReport = function($reportName,$callback){
        $http.get(BASE_URL + "!seshat/reportData/hashtag/" + $reportName).then($callback);
    }

    //create hashtag.
    this.createHashTagReport = function ( hashtag , $callback){
        $http.post(BASE_URL + "!seshat/createReport/hashtag/","hashtag=" + hashtag).then($callback);
    }
    
    //End Hashtag report section.
});