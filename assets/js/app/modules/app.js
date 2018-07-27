var BASE_URI = "/seshat/",
BASE_URL     = "http://127.0.0.1/seshat/",
template_url = BASE_URL + "assets/js/app/modules/"; 
angular.module('seshatApp',['ngJsonExportExcel']).controller("appCtrl",function ( $scope , $sce ) {
    $scope.renderHtml = function(html_code) {
        return $sce.trustAsHtml(html_code);
    };
});