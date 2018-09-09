angular.module("seshatApp").controller("followTreeCtrl" , function ( $scope , followTreeServices ) {
    //get users tree created or sub .. main view.
    if ( angular.element("#mainView").length > 0 ){
        //get user tree data.
        spinner.onPageLoad( true );
        followTreeServices.getTrees( function ( $response ){
            $scope.userTreesData  =  $response.data;
            spinner.remove(".spinner");
        });
    }
    //End main View.

    $scope.deleteTree = function ( $treeName , $treeId , $index ){
        swal({
            title: "Are You Sure ?",
            text: "Are you sure you want to delete this tree " + $treeName + " and it's contents ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete){
                followTreeServices.deleteTree( $treeId   , function ( $response ) {
                    if ( $response.data.error !== undefined ){
                        globalMethod.repsonseError( $response.data );
                    }else if ( $response.data.treeDeleted !== undefined ){
                        globalMethod.showNotification("success" ,"top","Right",$response.data.treeDeleted,'body',5000);
                        $scope.userTreesData.created_trees.splice($index,1);
                    }
                });
            }
          });            
    };

    $scope.exitTree = function ( $treeName  ,  $treeId , $index){
        swal({
            title: "Are You Sure ?",
            text: "Are you sure you want to Exit this tree " + $treeName + "?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          }).then((willDelete) => {
            if (willDelete){
                followTreeServices.exitTree( $treeId   , function ( $response ) {
                    if ( $response.data.error !== undefined ){
                        globalMethod.repsonseError( $response.data );
                    }else if ( $response.data.exit !== undefined ){
                        globalMethod.showNotification("success" ,"top","Right",$response.data.exit,'body',5000);
                        $scope.userTreesData.sub_trees.splice($index,1);
                    }
                });
            }
          });
    }; 
    
    //share tree.
    $scope.shareTree = function ( $treeName , $description ){
        globalMethod.shareContent("Interseting in ( " + $description + " ) join us now at our follow tree at this link : " + BASE_URL + "!followTree/show/" + $treeName);
    };
    //End share tree.

    //show All view.
    if ( angular.element("#showAllTree").length > 0 ){
        //show pageSpinner.
        spinner.onPageLoad( true );
        followTreeServices.showAllTree( function ( $response  ){
            if ( $response.data.error !== undefined ){
                globalMethod.repsonseError( $response.data );
            }else if ( $response.data.trees !== undefined ){
                $scope.allTrees = $response.data.trees;
            }
            spinner.remove('.spinner');
        });
    }
    //End show All.

    //show section.
    if ( angular.element("#showTree").length > 0 ) {
        
        $treeName = String(document.location).split("/");
        $treeName =  $treeName[ $treeName.length - 1 ];
        //show pageSpinner.
        spinner.onPageLoad( true );
        /**
         * 1 - call the server to get data of specific tree.
         * 2 - if there's a data show it to the screen else error not found appear.
         */
        followTreeServices.showTree( $treeName , function ( response ){
            $scope.treeData  = response.data;
            if ( $scope.treeData.error !== undefined ) {//data returned from the server.
                globalMethod.repsonseError( $scope.treeData );
            }else if ( $scope.treeData.tree_data !== undefined && $scope.treeData.tree_data.length > 0){
                $scope.tree = {description:response.data.tree_data[0].description,id:response.data.tree_data[0].id};
                //tree style here.
                $scope.isMember = response.data.member;
                if( $scope.treeData.tree_data.length == 1 && $scope.treeData.tree_data[0].sub_user_id == null  ){
                    $scope.treeData.tree_data.empty = true;
                }else{
                    globalMethod.createTree("showTree" , $scope.treeData.tree_data);
                }
            }
            spinner.remove('.spinner');
        } );
    }
    //End show section.

    //join tree.
    $scope.joinTree = function ($treeID){
        $button_content = $("#joinTree").html();
        spinner.button($("#joinTree"), '<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        followTreeServices.joinTree($treeID , function ( $response ){
            if ( $response.data.redirect !== undefined ){
                window.location = $response.data.redirect;
            }else if ( $response.data.error !== undefined ){
                globalMethod.repsonseError( $response.data );
            }else if ( $response.data.joined !== undefined ){
                globalMethod.showNotification("success" ,"top","Right",$response.data.joined,'body',5000);
                $scope.treeData.tree_data.push({id:$scope.treeData.tree_data.length + 1 , subscriber: 'You'});
                $scope.treeData.tree_data.empty = false;
                $scope.treeData.tree_data.splice(0,1);
                $scope.isMember = true;
                globalMethod.createTree("showTree" , $scope.treeData.tree_data);
            }
            spinner.remove($("#joinTree"), $button_content , false , true);
        });
    };
    //End join tree.
    //Max Account Update.
    $scope.descriptionUpdate = function ( $description , $treeID , $index ){
        $button_content = $("#descriptionUpdate" + $treeID).html();
        spinner.button($("#descriptionUpdate" + $treeID), '<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        followTreeServices.descriptionUpdate( $description , $treeID , function ( $response ) {
            if ( $response.data.error !== undefined ){
                globalMethod.repsonseError( $scope.data );
            }else if ( $response.data.treeUpdated !== undefined ){
                globalMethod.clearInput();
                $("#editTree" + $treeID).modal("hide");
                globalMethod.showNotification("success" ,"top","Right",$response.data.treeUpdated,'body',5000);
                $scope.userTreesData.created_trees[$index]['description'] = $description;
            }
            spinner.remove($("#descriptionUpdate"+$treeID), $button_content , false , true);
        } );
    };
    //End Max Account Update.

    $scope.createNewTree = function ( $treeName , $description , $maxAccounts ,  $media  , $followMyUsers ){
        $button_content = $("#createNewTree").html();
        spinner.button($("#createNewTree"), '<i style="margin-left: -12px;margin-right: 8px;" class="fa fa-spinner fa-spin"></i>' , true , true);
        followTreeServices.createNewTree( $treeName  , $description , $maxAccounts  , $media , $followMyUsers , function ( $response ) {
            $scope.result = $response.data;
            if ( $scope.result.error !== undefined ){
                globalMethod.repsonseError( $scope.result );
            }else if ( $scope.result.tree_created !== undefined ){
                globalMethod.clearInput();
                $("#createTree").modal("hide");
                globalMethod.showNotification("success" ,"top","Right",$scope.result.tree_created,'body',5000);
                $scope.userTreesData.created_trees.push({ 'id':$scope.result.tree_id , 'name':$treeName , 'description':$description , 'subscribers':0 ,'max_accounts': $maxAccounts , 'media':1 });
            }
            spinner.remove($("#createNewTree"), $button_content , false , true);
        });
    };

}).directive( "followTree" , function () {//default.
    return {
        templateUrl : template_url + "followTree/follow-tree.component.html",
        restrict : "E",
        replace : false
    };
}).directive( "data" , function (){
    return {
        templateUrl : template_url + "followTree/data.component.html",
        restrict : "E",
        replace : false
    };
}).directive("all",function () {
    return {
        templateUrl : template_url + "followTree/show-all.component.html",
        restrict    : "E",
        replace     : false
    };
}).service("followTreeServices",function ( $http ){
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    //get trees user created or sub.
    this.getTrees = function ($callback){
        $http.get( BASE_URL + "!followTree/default?data=true" ).then( $callback );
    };
    //End get Trees.

    //Create new tree.
    this.createNewTree = function ( $name , $description , $maxAccounts , $media , $followMyUsers , $callback){
        $followMyUsers = ( $followMyUsers === true ) ? 1 : 0;
        $http.post( BASE_URL + "!followTree/createNewTree/" , "name=" + $name  + "&description=" + $description + "&maxAccounts=" + $maxAccounts
        +"&media=" + $media + "&followMyUsers=" + $followMyUsers  ).then( $callback );
    };
    //End create new tree.
    //delete tree.
    this.deleteTree = function ( $treeId  , $callback ){
        $http.post( BASE_URL + "!followTree/delete/", "tree_id=" +$treeId).then($callback);
    };
    //End delete tree.

    //exit tree.
    this.exitTree = function (  $treeId  , $callback ) {
        $http.post( BASE_URL + "!followTree/exit/", "tree_id=" +$treeId).then($callback);
    };
    //End exit tree.

    //show tree.
    this.showTree = function ( $treeName  , $callback ) {
        $http.get( BASE_URL + '!followTree/show?treeName=' + $treeName ).then( $callback );
    };
    //End show tree.

    // showAll tree.
    this.showAllTree = function ( $callback ){
        $http.get( BASE_URL + '!followTree/showAll?showAll=' + true  ).then( $callback );
    };
    // End showAll tree. 
    //edit.
        //Edit description.
        this.descriptionUpdate = function ( $description , $treeID , $callback ){
            $http.post( BASE_URL + '!followTree/edit' , 'description=' + $description + "&tree_id=" + $treeID).then( $callback );
        };
        //End description.
    //End edit.

    //join tree.
    this.joinTree = function ( $tree_id , $callback ){
        $http.post( BASE_URL + '!followTree/join' , 'tree_id=' + $tree_id ).then( $callback );
    };
    //End join tree.

});