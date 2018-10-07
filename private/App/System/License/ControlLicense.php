<?php
    namespace App\System\License;

    /**
     * this class control license in seshat App.
     */

    class ControlLicense extends AbstractLicense
    {

        public function feature_license_data(){
            switch (strtolower( $this->license_name )) {
                case 'free':
                $features = ($this->licenses[$this->media][$this->license_name][$this->feature_id]) ?? false;
                    break;
                case 'lvl 1':
                $features = ($this->licenses[$this->media][$this->license_name][$this->feature_id]) ?? false;
                    break;    
                case 'lvl 2':
                    $licenses = $this->licenses[$this->media]['lvl 2'] + $this->licenses[$this->media]['lvl 1'];
                    $features = ($licenses[$this->feature_id]) ?? false;
                    break;
                case 'lvl 3':
                $licenses = $this->licenses[$this->media]['lvl 3'] + $this->licenses[$this->media]['lvl 2'] + $this->licenses[$this->media]['lvl 1'];
                $features = ($licenses[$this->feature_id]) ?? false;
                    break;
                default:
                    throw new LicenseException("Undefiend License name " . $this->license_name . " not permitted please @class " . get_class( $this ));
                    break;
            }
            return $features ?? false;
        }
    }