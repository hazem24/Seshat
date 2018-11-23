angular.module('seshatApp').controller("reportHashTagCtrl",function($scope,reportServices){
    $scope.hashtag_name;
    if (angular.element("hashtag-create-report").length <= 0){
        //inject custom style for hashtag.
        angular.element('head').append('<link href="' + css_url + "app/hashtag-style.css" + '" rel="stylesheet">');
        //Hashtag report reader section.
        reportServices.getHashtagReport($scope.report_name,function(response){
            //Response Here !.
            $scope.hashtag_report_data = response.data;
        });
    }
    //End Hashtag report reader section.
    //Hashtag create.
    //create hashTag Name. 
    $scope.hashtagName;

    $scope.createHashTagReport = function (hash_name){
        spinner.button($("#createReport"),'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>');
        reportServices.createHashTagReport(hash_name,function(response){
            //Data Return Logic.
            if(response.data.hash_not_active != undefined){
                console.log('This Hash tag not active !.');
            }else if (response.data.report_name != undefined){
                $scope.window.location.href = BASE_URL + "!seshat/getReport/hashtag/" + response.data.report_name;
            }else if (response.data.error !== undefined || repsonseError.data.AppError !== undefined){
                globalMethod.repsonseError(response.data);
            }
            spinner.remove($("#createReport") , $("#createReport").text());
        });
    }
    //End Hashtag create.

}).directive('hashtagReport',function(){
return {
    templateUrl : template_url + "reports/hashtag/hashtag-report.component.html",
    restrict : "E",
    replace : false
};
}).directive('hashtagCreateReport',function(){
return {
    templateUrl : template_url + 'reports/hashtag/hashtag-create-report.component.html',
    restrict     : "E",
    replace      : false
};
});