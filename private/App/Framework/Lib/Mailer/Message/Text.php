<?php 

            namespace Framework\Lib\Mailer\Message;


            class Text implements Message
            {

                /**
                *This method Not Complete For Test Only
                */
                public function format($msg){
                        if(is_array($msg)){
                                return implode(" " , $msg);
                        }

                        return $msg;
                }
            } 