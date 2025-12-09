<?php


    $router->group([
    ], function() use ($router) {
        $router->post('/files', [
            'uses' => 'Controller@Files',
        ]);
        $router->post('/hapus-files', [
            'uses' => 'Controller@deleteGambar',
        ]);
    });
