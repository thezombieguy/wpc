<?php
define('WPC_TEMPLATE_DIR', get_stylesheet_directory());
define('WPC_APP_DIR', __DIR__);
define('WPC_NAMESPACE', 'wpc');

/**
 * autoloader for the theme classes
 * @param  string $controller the name of the class/controller
 * @return void             includes the controller in autoloader
 */

spl_autoload_register('wpc_autoload');

function wpc_autoload($controller) {
	if(file_exists(WPC_APP_DIR.'/classes/'.$controller.'.php')){
		require_once(WPC_APP_DIR.'/classes/'.$controller.'.php');
	}
}

WPC_Load::load();
