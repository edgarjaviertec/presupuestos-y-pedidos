<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

$route['admin/usuarios'] = 'admin/users';
$route['admin/usuarios/nuevo'] = 'admin/users/new_user';
$route['admin/usuarios/(:num)'] = 'admin/users/edit_user/$1';
$route['admin/usuarios/cambiar_contrasena/(:num)'] = 'admin/users/change_password/$1';

$route['admin/clientes'] = 'admin/customers';
$route['admin/clientes/nuevo'] = 'admin/customers/new_customer';
$route['admin/clientes/(:num)'] = 'admin/customers/edit_customer/$1';

$route['admin/productos'] = 'admin/products';
$route['admin/productos/nuevo'] = 'admin/products/new_product';
$route['admin/productos/(:num)'] = 'admin/products/edit_product/$1';

$route['admin/presupuestos'] = 'admin/estimates';
$route['admin/presupuestos/nuevo'] = 'admin/estimates/new_estimate';
$route['admin/presupuestos/(:num)'] = 'admin/estimates/edit_estimate/$1';
$route['admin/presupuestos/pdf/(:num)'] = 'admin/estimates/get_pdf/$1';

$route['admin/pedidos'] = 'admin/orders';
$route['admin/pedidos/nuevo'] = 'admin/orders/new_order';
$route['admin/pedidos/(:num)'] = 'admin/orders/edit_order/$1';
$route['admin/pedidos/pdf/(:num)'] = 'admin/orders/get_pdf/$1';

$route['admin/configuracion'] = 'admin/settings/edit_settings';
