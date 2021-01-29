<?php

function routes(){
    $routes = [
        '/example' => ['method' => 'POST', 'controller' => 'ExampleController@resource'],
    ];

    return $routes;
}
