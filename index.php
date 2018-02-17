<?php
        /**
        *This File @Time Of Written Provide An Example For Which This System Will Work
        *This File Work As Bootstrapping File 
        */
        use App\Router\Router;
        use App\Router\Simple;
        use App\Router\OutsideLayer;

        require("private/App/Setting.php");

        $incomingUrl = $_SERVER['REQUEST_URI'];
        $router = new Router($incomingUrl);
        $router->addRoutes(["Simple"=>new Simple(["pattern"=>LINK_SIGN])]);
        $router->dispatch();