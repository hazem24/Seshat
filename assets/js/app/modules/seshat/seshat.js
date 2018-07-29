angular.module("seshatApp").controller("seshatCtrl",function( $scope , seshatService){
    $timeline = String(document.location).toLowerCase().indexOf("!seshattimeline");
    if ( $timeline > 0 ){
        //get Timeline data.
        seshatService.getTimeLine(function ( response ) {
            
            if( response.data.error !== undefined ){
                $scope.results = response.data;
                globalMethod.repsonseError( response.data );
            }else if ( response.data.results !== undefined ){
                $scope.results = response.data.results;
            }
            
            spinner.removeSpinner('.spinner');
        });
    }

}).directive("timeline",function (){
    return {
        templateUrl : template_url + "feed/posts.component.html",
        restrict : "E",
        replace : false
    };
}).service("seshatService",function ( $http ){
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
    //get Timeline.
    this.getTimeLine = function ( $callback ) {
        spinner.onPageLoad(true);
        $http.get(BASE_URL + "!seshatTimeline/default/getTimeLineDataAsJson").then( $callback );
    };
});