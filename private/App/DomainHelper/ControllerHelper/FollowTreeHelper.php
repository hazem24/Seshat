<?php
    namespace App\DomainHelper\ControllerHelper;
    use App\DomainHelper\FilterTrait;
    use Framework\Request\RequestHandler;
    use App\Controller\FollowTree;
    use App\DomainHelper\Helper;
    use Framework\Error\WebViewError;
    use Framework\Shared\CommandFactory as Cmd;
    use Framework\Lib\Security\Data\FilterDataFactory;
    use Framework\Helper\Html;


    /**
    * This class a helper class for FollowTree controller.
    */

    Class FollowTreeHelper 
    {
        private static $treeMaxAccounts = [1=>5000,20000,50000,100000];
        use FilterTrait;


        public static function default(FollowTree $followTree){
            /**
             * This method has two logic :
                * first the views when there's no GET['data'] is provided. --Done.
                * Second if GET['data'] is provided .. json data of trees user created or sub returned. --Done.
             */
            if( RequestHandler::getRequest() && RequestHandler::get('data') ){
                $response   =  self::getUserTrees( $followTree->session->getSession('id') );
                $followTree->commonError( $response );
                $response = $followTree->returnResponseToUser(  $response ?? null );
                $followTree->encodeResponse( $response );    
            }
            $followTree->renderLayout("HeaderApp");
            $followTree->render();
            $followTree->renderLayout("FooterApp");
        }

        public static function show ( FollowTree $followTree ){
            if ( RequestHandler::getRequest() && RequestHandler::get('treeName') ){
                $cmd      = Cmd::getCommand('followTree');
                $response = $cmd->execute(['Method'=>['name'=>'show',
                'parameters'=>['user_id'=>$followTree->session->getSession('id'),'tree_name'=> Html::encodeDataToHtml( (string) RequestHandler::get('treeName'))]]]);
                $followTree->commonError( $response );
                $response = $followTree->returnResponseToUser(  $response ?? null );
                $followTree->encodeResponse( $response );    
            }
            $followTree->renderLayout("HeaderApp");
            $followTree->render();
            $followTree->renderLayout("FooterApp");
        }

        public static function showAll( FollowTree $followTree ){
            if ( RequestHandler::getRequest() && RequestHandler::get('showAll')  ){
                $cmd      = Cmd::getCommand("followTree");
                $response = $cmd->execute(['Method'=>['name'=>'showAll','parameters'=>[]]]);
                $followTree->commonError( $response );
                $response = $followTree->returnResponseToUser(  $response ?? null );
                $followTree->encodeResponse( $response ); 
            }
            $followTree->renderLayout("HeaderApp");
            $followTree->render();
            $followTree->renderLayout("FooterApp");
        }

        public static function join ( FollowTree $followTree ){
            if ( RequestHandler::postRequest() && RequestHandler::post('tree_id') ){
                $tree_id = (int) RequestHandler::post('tree_id');
                $user_id = $followTree->session->getSession('id');
                if ($tree_id > 0 && $user_id !== false){
                    $cmd = Cmd::getCommand("followTree");
                    $response = $cmd->execute(['Method'=>
                    ['name'=>'joinTree','parameters'=>['tokens'=>$followTree->getTokens(),'limit'=>self::$treeMaxAccounts,'tree_id'=>$tree_id,'user_id'=>$user_id]]]);
                    $followTree->commonError( $response );
                }else{
                    $response  = ($user_id === false) ? ['redirect'=>BASE_URL . '!index/signin'] : '';
                    ($tree_id < 0 ) ? $followTree->setError(INVAILED_TREE_ID) : '';
                }
            }else{
                $followTree->setError( CANNOT_UNDERSTAND );
            }
            $response = $followTree->returnResponseToUser(  $response ?? null );
            $followTree->encodeResponse( $response );    
        }

        public static function createNewTree( FollowTree $followTree ){
            /**
             * 1 - first step filter all in coming data in post request. --Done.
             * 2 - if data clean send it to FollowTreeCommand logic else error with uncleaned data go to user. --Done.
             */
            if ( RequestHandler::postRequest() && RequestHandler::post('name') ){
                $treeName  = RequestHandler::post('name');
                $validName = self::usernamePattern( $treeName , 2 , 200 ); //check name of tree.

                $treeMaxAccounts = (bool) in_array((int) RequestHandler::post('maxAccounts') , self::$treeMaxAccounts);

                $followUsers =  (bool)  RequestHandler::post('followMyUsers');

                $media       = (string)   RequestHandler::post('media');
                $mediaExists =  Helper::issetMedia( $media );
                $filter      = self::filterForm((string)RequestHandler::post('description'));

                if ( array_key_exists('cleanData',$filter) && $validName === true && $treeMaxAccounts === true && $mediaExists){
                    //No error.
                    /**
                     * Send data to FollowTreeCommand.
                     */
                    $response = self::createTree( $followTree , $treeName , $filter['cleanData'] , array_search( (int) RequestHandler::post('maxAccounts')  , self::$treeMaxAccounts ) , Helper::mediaToNumber($media) , $followUsers );
                    $followTree->commonError( $response );
                    
                }else{
                    ( $validName === false ) ? $followTree->setError( INVALID_TREE_NAME ) : '';
                    ( array_key_exists('error',$filter) ) ? $followTree->setError( $filter['error'] ) : '';
                    ( $treeMaxAccounts === false ) ? $followTree->setError( MAX_ACCOUNTS_IN_TREE ) : '';
                    ( $media === false ) ? $followTree->setError( MEDIA_NOT_FOUND ) : '';
                }
            }else{
                $followTree->setError( CANNOT_UNDERSTAND );
            }
            $response = $followTree->returnResponseToUser(  $response ?? null );
            $followTree->encodeResponse( $response );
        }


        public static function deleteTree( FollowTree $followTree ){
            if( RequestHandler::postRequest() && RequestHandler::post('tree_id') ){
                $tree_id = (int) RequestHandler::post("tree_id");
                if ( $tree_id > 0 ){
                    $cmd      = Cmd::getCommand('followTree');
                    $response = $cmd->execute(['Method'=>['name'=>'deleteTree','parameters'=>[
                        'user_id'=>$followTree->session->getSession('id') , 'tree_id'=>$tree_id]]]);
                    $followTree->commonError( $response );     
                }else{
                    $followTree->setError( INVAILED_TREE_ID );
                }
            }else{
                $followTree->setError( CANNOT_UNDERSTAND );
            }
            $response = $followTree->returnResponseToUser(  $response ?? null );
            $followTree->encodeResponse( $response );
        }


        public static function exitTree( FollowTree $followTree ){
            if( RequestHandler::postRequest() && RequestHandler::post('tree_id') ){
                $tree_id = (int) RequestHandler::post("tree_id");
                if ( $tree_id > 0 ){
                    $cmd      = Cmd::getCommand('followTree');
                    $response = $cmd->execute(['Method'=>['name'=>'exitTree','parameters'=>[
                        'user_id'=>$followTree->session->getSession('id') , 'tree_id'=>$tree_id]]]);
                    $followTree->commonError( $response );     
                }else{
                    $followTree->setError( INVAILED_TREE_ID );
                }
            }else{
                $followTree->setError( CANNOT_UNDERSTAND );
            }
            $response = $followTree->returnResponseToUser(  $response ?? null );
            $followTree->encodeResponse( $response );
        }

        public static function editTree( FollowTree $followTree){
            if ( RequestHandler::postRequest() && RequestHandler::post("description") ) {
                $treeID       = (int) RequestHandler::post( "tree_id" );
                $filter       = self::filterForm(RequestHandler::post("description"));
                if ( array_key_exists('cleanData',$filter)  && $treeID > 0){
                    //No error.
                    /**
                     * Send data to FollowTreeCommand.
                     */
                    $response    = self::updateTree( $followTree , $followTree->session->getSession('id') , $treeID , $filter['cleanData']);
                    $followTree->commonError( $response );
                }else{
                    ( array_key_exists('error',$filter) ) ? $followTree->setError( $filter['error']) : '';
                    ($treeID <= 0) ? $followTree->setError(INVAILED_TREE_ID) : '';
                }
            }else{
                $followTree->setError(CANNOT_UNDERSTAND);
            } 
            $response = $followTree->returnResponseToUser(  $response ?? null );
            $followTree->encodeResponse( $response );
        }


        private static function getUserTrees( int $user_id ){
            $cmd = Cmd::getCommand('FollowTree');
            $response = $cmd->execute(['Method'=>['name'=>'getUserTrees','parameters'=>['user_id'=>$user_id]]]);
            if ( array_key_exists('AppError' , $response) === false ){
                //convert max accounts to it's real value.
                if( isset( $response['created_trees'][0] ) ){
                    foreach ($response['created_trees'] as $key => $tree) {
                        $response['created_trees'][$key]['max_accounts'] = self::$treeMaxAccounts[ ( int ) $response['created_trees'][$key]['max_accounts']];
                    }
                }

                if ( isset(  $response['sub_trees'][0]  ) ){
                    foreach ($response['sub_trees'] as $key => $tree) {
                        $response['sub_trees'][$key]['max_accounts'] = self::$treeMaxAccounts[ ( int ) $response['sub_trees'][$key]['max_accounts']];
                    }
                }
            }
            return $response;
        }

        private static function createTree( FollowTree $followTree , string $treeName , string $description , int $maxAccounts , int $media , bool $followUsers ){
            $tokens = ( $followUsers ) ? $followTree->getTokens() : false;
            $cmd      = Cmd::getCommand('FollowTree');
            $response = $cmd->execute(['Method'=>['name'=>'createNewTreeLogic','parameters'=>['name'=>$treeName,'description'=>$description
            ,'tokens'=>$tokens,'max_accounts'=>$maxAccounts,'media'=>$media,'user_id'=>$followTree->session->getSession('id')]]]);
            return $response;
        }

        private static function updateTree(  FollowTree $followTree , int $user_id , int $tree_id , string $description ){
            $cmd      = Cmd::getCommand('FollowTree');
            $response = $cmd->execute(['Method'=>['name'=>'updateTree',
            'parameters'=>['user_id'=>$user_id,'tree_id'=>$tree_id,'description'=>$description]]]); 
            return $response;
        }

        private static function filterForm(string $description){
            $data        = [TREE_DESCRIPTION=>['value'=>$description,'type'=>'string','min'=>10,'max'=>400]];
            $filter      = new FilterDataFactory( $data );
            $filter      = $filter->getSecurityData();
            $anyError    = WebViewError::anyError('form',$data,$filter);
            if ( $anyError === false ){
                return ['cleanData'=>$filter[TREE_DESCRIPTION]];
            }else{
                return ['error'=>WebViewError::userErrorMsg( $anyError )];
            }
        }
    }