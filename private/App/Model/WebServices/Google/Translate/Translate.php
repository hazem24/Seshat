<?php
        namespace App\Model\WebServices\Google\Translate;
        use \Statickidz\GoogleTranslate;


        /**
         * This Class Provide Methods to connect to Google Translate Api to translate in Scentence To Any Lang. 
         */

         Class Translate 
         {
            /**
              * @param org_text the orginal text.
            */
             private $org_text;
            /**
             * @param source the source of org_text lang.
            */
             private $source;

            /**
              * @param target specific lang to translate org_text to it.
            */

              private $target;

            /**
              * @param google_translate instances of google translate class.
            */
              private $google_translate = null ;

              public function __construct(array $data){
                    $this->org_text = $data['text'];
                    $this->source   = $data['from'];
                    $this->target   = $data['to'];
                    //To Protect init to instance from google translate class.
                    if(is_null($this->google_translate) == true){
                            $this->google_translate = new GoogleTranslate();
                    }
              }
              /**
               * @method getText. 
               * @return translated text.
               */
              public function getText():string{
                    return (string) $this->google_translate->translate($this->source,$this->target,$this->org_text);
              }
         }