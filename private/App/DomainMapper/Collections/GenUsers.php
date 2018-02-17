<?php

        namespace App\DomainMapper\Collections;
        use Framework\Lib\DataBase\DataMapper\Collections\AbstractGeneratorCollection as AbstractGeneratorCollection;
        use App\Model\Users as Users;

        /**
        *This Class Provide An Example How Gen Work
        **/
        class GenUsers extends AbstractGeneratorCollection 
        {
            public function targetClass():string{
                    return Users::class;
            }

        }
