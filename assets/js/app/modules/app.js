var BASE_URI = "/seshat/",
BASE_URL     = "http://127.0.0.1/seshat/",
template_url = BASE_URL + "assets/js/app/modules/"; 
css_url      = BASE_URL + "assets/css/";
var app = angular.module('seshatApp',['ngJsonExportExcel',"ngRoute"]).controller("appCtrl",function ( $scope , $sce , $window , AppServices) {

    $scope.$on('$routeChangeStart', function($event, next, current) { 
        $("#tree_data").remove();
        $("#showTree").remove();            
      });
     
    $scope.navPostion = localStorage.getItem('dashkitNavPosition');
    $scope.renderHtml = function(html_code) {
        return $sce.trustAsHtml(html_code);
    };

    $scope.jsonParser = function(string){
        return JSON.parse(string);
    };
    $scope.changeLang = function (lang){
        AppServices.changeLang(lang, function(response){
            $scope.lang = response.data.lang; //Will be used latter in the new version.
            $window.location.reload();
        });
    };  
    
}).service('AppServices',function($http){
    this.changeLang = function (lang , callback){
        $http.get(BASE_URL + '!index/changeLang?lang='+lang).then(callback);
    }
});

//Routing.
app.config(function($routeProvider) {
    $routeProvider
    .when("/controlfollowers/recentFollowers", {
      templateUrl : template_url + "feed/users.component.html",
      controller : "seshatCtrl",
      redirectTo  : "controlfollowers/recentFollowers"
    })
    .when("/controlfollowers/nonFollowers", {
        templateUrl : template_url + "feed/users.component.html",
        controller  : "seshatCtrl",
        redirectTo  : "controlfollowers/nonFollowers"
    })
    .when("/controlfollowers/fans", {
        templateUrl : template_url + "feed/users.component.html",
        controller : "seshatCtrl",
        redirectTo  : "controlfollowers/fans"
    })
    .when("/controlfollowers/recentUnfollow", {
        templateUrl : template_url + "feed/users.component.html",
        controller : "seshatCtrl",
        redirectTo  : "controlfollowers/recentUnfollow"
    })
    .when("/timeline", {
        templateUrl : template_url + "feed/posts.component.html",
        controller : "seshatCtrl"
    })
    .when("/statistics", {
        templateUrl : template_url + "statistics/accounts/accounts-statistics.component.html",
        controller : "seshatCtrl"
    })  
    .when("/settings", {
        templateUrl : template_url + "account/settings.component.html",
        controller : "seshatCtrl"
    })   
    .when("/checkFriends/twitter", {
        templateUrl : template_url + "feed/checkRelation.component.html",
        controller : "seshatCtrl"
    })
    .when("/profile/:media/:profile_screen_name", {
        templateUrl : template_url + "profile/twitter/twitter-profile-page.component.html",
        controller : "twitterprofileCtrl"
    })
    .when("/tasks", {
        templateUrl : template_url + "tasks/tasks.component.html",
        controller : "seshatCtrl",
        redirectTo  : "tasks"
    })
    .when("/activity", {
        templateUrl : template_url + "activity/account-activity.component.html",
        controller : "seshatCtrl",
        redirectTo  : "activity"
    })
    .when("/followTree", {
        templateUrl : template_url + "followTree/follow-tree.component.html",
        controller : "followTreeCtrl",
        redirectTo  : "followTree"
    })
    .when("/trees/showAll", {
        templateUrl : template_url + "followTree/show-all.component.html",
        controller : "followTreeCtrl",
        redirectTo  : "trees/showAll"
    })
    .when("/tree/:tree_name", {
        templateUrl : template_url + "followTree/data.component.html",
        controller : "followTreeCtrl",
        redirectTo  : "tree/:tree_name"
    })
  });