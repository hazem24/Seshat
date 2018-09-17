angular.module('seshatApp').controller("layoutCtrl",function($scope,layoutService,$interval){

    $scope.getUnReadNotifications = function (){
        layoutService.getUnReadNotifications(function ($response){
            $scope.getNotifications = $response.data;
            console.log( $scope.getNotifications );
        });
    };

    //load user unRead notification every 60 sec.
    $interval($scope.getUnReadNotifications,60000);

}).directive('navArea',function(){
return {
    templateUrl : template_url + "layout/nav-area.component.html",
    restrict : "E",
    replace : false
};
}).service("layoutService",function ($http){
    this.getUnReadNotifications = function ($callback){
        $http.get(BASE_URL + "!seshat/account?get=unReadNotifications").then($callback);
    };
});