<?php
    namespace App\System\License;
    use Framework\ConstructorClass as Base;

    /**
     * this class is the base class for control license in the app.
     */

    Abstract Class AbstractLicense extends Base
    {
        /**
         * type of license.
         * @property license_type.
         */
        protected $license_type = 0;
        /**
         * licenses whiteList.
         * @property license_whiteList.
         */
        protected $licenses_whiteList = ["free"=>0,"lvl 1"=>1,'lvl 2'=>2,"lvl 3"=>3];

        /**
         * license name.
         * @property license_name.
         */
        protected $license_name;

        /**
         * feature id.
         * @property feature_id.
         */
        protected $feature_id;

        /**
         * media.
         * @property media.
         */
        protected $media;

        /**
         * features ids.
         * @property features_ids_whiteList.
         */
        protected $features_ids_whiteList = ["schedule"=>1,"postAs"=>2,"nonFollowers"=>31,
            "fans"=>32,"recentFollowers"=>33,
            "hashtag_reports"=>4,"fake_calcultor"=>5,'follow_tree'=>54
        ];

        /**
         * paid features of the app.
         * @property licenses.
         */
        protected $licenses =  [
            'twitter'=>[
                "free"=>[
                    "price"=>0,
                    1=>['max'=>10],  
                ],
                "lvl 1"=> [
                    'price'=>5,
                    1=>['max'=>30],//schedule task.
                    2=>['max'=>1],//post as task.
                    31=>['max'=>1],//control followers task.
                    32=>['max'=>1],//control followers task.
                    33=>['max'=>1],//control followers task.
                    4=>['max'=>"unlimited"]//create hashtag reports.
                ],
                "lvl 2"=> [
                    "price"=>10,
                    1=>['max'=>50],
                    2=>['max'=>2],
                    5=>['max'=>'unlimited']//fake accounts calculator.
                ],
                "lvl 3"=> [
                   "price"=>15,
                    1=>['max'=>100],//schedule task.
                    2=>['max'=>3],//postAs.
                    54=>['max'=>3]//follow tree created features.
                ]
            ]
        ];

        public function initilzation( int $license_type = 0 , int $feature_id = 0){
            $this->license_type = $license_type;
            $this->license_name = array_search( $license_type , $this->licenses_whiteList , true );             
            if ( $this->license_name !== false ){
                if (array_search( $feature_id , $this->features_ids_whiteList ) !== false){
                    $this->feature_id = $feature_id;
                }else{
                    throw new LicenseException("feature " . $feature_id . " not found please fix this @class " . get_class( $this ));
                }
            }else{
                throw new LicenseException("License " . $license_type . " not permitted please @class " . get_class( $this ));
            }
        }

        /**
         * this method set media.
         * @method setMedia.
         * @return void.
         */
        public function setMedia( string $media ){
            $this->media = $media;
        }

        /**
         * get seshat license and features and price.
         * @method getLicenses.
         * @return array.
         */
        public function getLicenses(){
            return $this->licenses;
        }

        /**
         * get data of specific feature in specific license can do specific feature.
         * @method permitted.
         * @return array.
         */
        Abstract public function feature_license_data();
    }