<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\UserWhitelist\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
	   ['name' => 'userwhitelist#index', 'url' => '/', 'verb' => 'GET'],
	   ['name' => 'userwhitelist#show', 'url' => '/user', 'verb' => 'GET'],
	],
	'ocs' => [
	   ['name' => 'api#add', 'url' => '/api/v1/user', 'verb' => 'POST'],
	   ['name' => 'api#sync', 'url' => '/api/v1/user/sync', 'verb' => 'POST'],
	   ['name' => 'api#enable', 'url' => '/api/v1/user/enable', 'verb' => 'POST'],
	   ['name' => 'api#disable', 'url' => '/api/v1/user/disable', 'verb' => 'POST'],
	],
];
