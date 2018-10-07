<?php

$licenses =  [
    'twitter'=>[
        "free"=>[
            "price"=>0,
            1=>['max'=>10],  
        ],
        "lvl 1"=> [
            'price'=>5,
            1=>['max'=>30],//schedule task.
            2=>['max'=>1],//post as task.
            31=>['max'=>1],//control followers task.
            32=>['max'=>1],//control followers task.
            33=>['max'=>1],//control followers task.
            4=>['max'=>"unlimited"]//create hashtag reports.
        ],
        "lvl 2"=> [
            "price"=>10,
            1=>['max'=>50],
            2=>['max'=>2],
            5=>['max'=>'unlimited']//fake accounts calculator.
        ],
        "lvl 3"=> [
           "price"=>15,
            1=>['max'=>100],//schedule task.
            2=>['max'=>3],//postAs.
            54=>['max'=>3]//follow tree created features.
        ]
    ]
];

var_dump( ($licenses['twitter']["lvl 2"] + $licenses['twitter']["lvl 1"] ) );