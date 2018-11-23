var BASE_URI = "/seshat/",
BASE_URL     = "http://127.0.0.1/seshat/",
template_url = BASE_URL + "assets/js/app/modules/"; 
css_url      = BASE_URL + "assets/css/";
angular.module('seshatApp',['ngJsonExportExcel']).controller("appCtrl",function ( $scope , $sce , $window , AppServices) {
    $scope.renderHtml = function(html_code) {
        return $sce.trustAsHtml(html_code);
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