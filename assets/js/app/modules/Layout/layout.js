angular.module('seshatApp').controller("layoutCtrl",function($scope,layoutService,$interval){

    $scope.alertNotification = function() {
        var audio = new Audio(BASE_URL + "assets/alertNotification.mp3");
        audio.play();
    };
    
    $scope.getNotifications = [];
    $scope.getUnReadNotifications = function (){
        layoutService.getUnReadNotifications(function ($response){
            if ($response.data.length > 0){
                $scope.alertNotification();
                $("#notify_counter").text("+" + $response.data.length);
                $scope.getNotifications = $response.data;
            }
        });
    };
    //load user unRead notification every 60 sec.
    $interval($scope.getUnReadNotifications,60000);    
}).directive('notifyArea',function(){
    return {
        templateUrl : template_url + "layout/notify-area.component.html",
        restrict : "E",
        replace : false
    };
}).directive('notifyAreaModal',function(){
    return {
        templateUrl : template_url + "layout/notify-area-modal.component.html",
        restrict : "E",
        replace : false
    };
}).service("layoutService",function ($http){
    this.getUnReadNotifications = function ($callback){
        $http.get(BASE_URL + "!seshat/account?get=unReadNotifications").then($callback);
    };
});