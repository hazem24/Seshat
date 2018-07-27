angular.module("seshatApp").controller( "searchCtrl" , function ( $scope , searchServices ) {
    $scope.filterBy = "twitter::tweets"; //default search for twitter to search for tweets.

    //show error in the search Form.
    $scope.searchFormError = function () {
        globalMethod.destoryNotify();
        if ( $scope.searchForm.q.$error.minlength ){
            globalMethod.showNotification('danger','top',"Center","Min length required 2.",200000);
        }
        if ( $scope.searchForm.q.$error.maxlength ){
            globalMethod.showNotification('danger','top',"Center","Max length required is 200.",200000);
        }
        if( $scope.searchForm.q.$error.required && $scope.searchForm.q.$dirty ){
            globalMethod.showNotification('danger','top',"Center","Search Field is required.",200000);
        }
    }

    $scope.advancedSearch = function ($searchVal , $searchScope) {
        $button_text = $("#searchBtn").html();
        spinner.button($("#searchBtn"),'<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>',true,true);
        $searchScope = $scope.filterBy.split("::");
        searchServices.search( $searchVal , $searchScope[0] , $searchScope[1] , function (response) {
            if ( response.data.error !== undefined ) {
                globalMethod.repsonseError( response.data );
            }else{
                //searchResult.
                if ( response.data.results !== undefined ) {
                    $scope.results = response.data.results;
                }else {
                    globalMethod.showNotification('danger','top',"Center","App error cannot handle your request.",200000);
                }
            }
            spinner.remove($("#searchBtn"),$button_text,false,true);
        } );
    };
}).directive("search" , function () {
    return {
        templateUrl : template_url + 'search/search.component.html',
        restrict     : "E",
        replace      : false
    };

}).directive("posts",function (){
    return {
        templateUrl : template_url + 'feed/posts.component.html',
        restrict     : "E",
        replace      : false
    };
}).directive("users",function () {
    return {
        templateUrl : template_url + 'feed/users.component.html',
        restrict     : "E",
        replace      : false
    };
}).service( "searchServices" , function ( $http ){
    this.search = function ( $searchVal , $media , $type  , $callback){
        
        $searchVal = ( $searchVal === undefined ) ? '' : $searchVal  ;
        $http.get(BASE_URL + "!seshat/search/"+ $media +"/" + $type +"/"+ encodeURIComponent($searchVal)).then($callback);
    };
    
});