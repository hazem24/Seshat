<?php

        namespace App\Controller;
        use Framework\Shared\Controller;

        /**
        *This Class Just Provide The Instance If The Controller Requestd By User Not Exists
        */

        Class NotFound extends AppShared
        {

                public function notFoundAction(){
                        $this->detectLang();
                        $this->renderLayout('HeaderApp');
                        $this->render();
                        $this->renderLayout('FooterApp');
                }
        }