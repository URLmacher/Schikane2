<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'pages/view';
$route['home'] = 'pages/view';
$route['game'] = 'games/render_search';
$route['search'] = 'games/search';
$route['ready'] = 'games/ready';
$route['start'] = 'games/render';
$route['login'] = 'users/login';
$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE; 
