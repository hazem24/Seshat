<?php

        namespace Framework\Shared;
        use Framework\ConstructorClass;

        /**
        *This Class Provide And Abstract Class Of All Commands Of The Bussiness Logic Of The App
        */
        Abstract Class AbstractCommand extends ConstructorClass
        {
            Abstract public  function execute(array $data = []);
        }