<?php
    namespace App\Model;
    use Framework\Shared\Model;
    

    /**
     * This class is an abstract class for all Domain Model.
     */

    Abstract Class BaseModel extends Model
    {
        /**
         * save model into DB.
         * @method save.
         * @return bool.
         */
        Abstract public function save();
        /*protected function update();

        protected function delete();

        protected function insert();*/
        
        Abstract protected static function getFinder();
    }