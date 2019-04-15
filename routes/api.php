<?php

$router->group([
    'prefix' => 'api',
    //'middleware' => ['api'],
], function ($router) {
    $router->options('/{path:.*}', function ($path) {});
    $router->get('/note_list', 'NoteController@get_note_list');
	$router->post('/note_add', 'NoteController@add');

});
