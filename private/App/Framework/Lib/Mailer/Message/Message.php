<?php

        namespace Framework\Lib\Mailer\Message;


        /**
        *This Interface Provide The Main Function In Which Can Be Used In All Message Type
        *Like : TextMessage And Html Message
        */
        interface  Message 
        {
              /**
              *@method format Handle The Logic In Which Format the Msg As I Want As Text Or Html 
              */  
              public function format($msg);  

        }