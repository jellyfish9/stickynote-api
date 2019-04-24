<?php

$router->group([
    'prefix' => 'api',
    //'middleware' => ['api'],
], function ($router) {
    $router->options('/{path:.*}', function ($path) {});
    $router->get('/note_list', 'NoteController@get_note_list');
	$router->post('/note_add', 'NoteController@add');
	$router->post('/note_edit/{id}', 'NoteController@edit');
	$router->get('/note_show/{id}', 'NoteController@show');
	$router->get('/note_search', 'NoteController@search');
	$router->get('/note_tags', 'NoteController@tags');

});
