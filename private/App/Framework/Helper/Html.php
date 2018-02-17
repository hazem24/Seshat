<?php

    namespace Framework\Helper;
    use Framework\ConstructorClass as ConstructorClass;

    /**
    * This Class Has All @method To Prevent Bad Data To See By User
    */

    Class Html extends ConstructorClass
    {
            public static function encodeDataToHtml($data):string{
                    return htmlentities($data , ENT_QUOTES);
            }

            public static function decodeDataToHtml($data):string{
                    return html_entity_decode($data , ENT_QUOTES);
            }
    }

