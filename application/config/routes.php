<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "inicio";
$route['404_override'] = '';


/* RUTAS PYME */

#logout
$route['logout']										= "inicio/logout";

#paginas editables
$route['paginas-editables'] 							= "paginas_editables";
$route['paginas-editables/editar/(:num)'] 				= "paginas_editables/editar/$1";
$route['paginas-editables/editar'] 						= "paginas_editables/editar";

#productos
$route['productos/(:num)']								= "productos/index/$1";

#categorias
$route['categorias/(:num)']                             = "categorias/index/$1";

#datos empresa
$route['datos-empresa']									= "datos_empresa";

$route['contacto/']							            = "contacto";
$route['contacto/(:num)']								= "contacto";
$route['contacto/exportar']								= "contacto/exportar_excel";
$route['contacto/ver/(:num)']							= "contacto/ver_contacto/$1";


$route['usuarios/(:num)']								= "usuarios";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
