<?php
     namespace App\Model\WebServices\Twitter\Scarper;
     use \DOMDocument as Dom;
     use \DOMXPath as XDom;
     use \DOMElement as DomElement;


    /**
     * this trait help to in some helper methods uses in Twitter/Scarper class.
     */

     trait Helper
     {
         /**
          * @method repliesFilter.
          */
          protected static function repliesFilter(string $statusContent){
            libxml_use_internal_errors(true);
            $replies_ids = []; $replies_contents = []; $user_replies = []; $user_profiles_images = [];
            $dom = new Dom();
            @$dom->loadHtml($statusContent);
            $xDom = new XDom($dom);
            $replies_div = $xDom->query("//*[contains(@class, 'tweet-text')]");
            $users_replies = $xDom->query("//*[contains(@class, 'username')]");
            $search = $dom->getElementsByTagName("div");
            //Get replies ids.
            foreach ($search as $nodes) {
                if(!empty($nodes->getAttribute('data-id'))){
                    $replies_ids[] = trim($nodes->getAttribute('data-id'));
                }
            }
            //End replies ids.
            //get replies contents.
            foreach ($replies_div as $nodes) {
                if(!empty($nodes->textContent)){
                    $replies_contents[] = trim($nodes->textContent);    
                }
            }
            //End replies contents.
            //get users replies to the content.
            foreach ($users_replies as $nodes) {
                if($nodes->tagName == 'div' && stripos($nodes->textContent,'Replying') === false){
                        $user_replies[] = $nodes->textContent;
                }
            }
            //End users replies to the content.
          }
     }