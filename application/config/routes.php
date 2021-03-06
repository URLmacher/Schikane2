<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'pages/view';
$route['home'] = 'pages/view';
$route['login'] = 'users/login';
$route['profile/edit'] = 'users/edit';
$route['profile/delete'] = 'users/delete';
$route['profile/other'] = 'users/show';
$route['profile/stats'] = 'users/stats';
$route['game'] = 'games/render_search'; 
$route['messages/create'] = 'messages/create';
$route['messages'] = 'messages/index';
$route['messages/(:any)'] = 'messages/view/$1';
$route['friends/add'] = 'friendships/add';
$route['friends/(:any)'] = 'friendships/delete/$1';
$route['friends'] = 'friendships/index';
$route['search'] = 'games/search';
$route['invite'] = 'games/invite';
$route['join/(:any)'] = 'games/join/$1';
$route['ready'] = 'games/ready';
$route['setready'] = 'games/setready';
$route['checkready'] = 'games/checkready';
$route['start'] = 'games/render';
$route['restart'] = 'games/rerender';
$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE; 
