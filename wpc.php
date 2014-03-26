<?php
define('WPC_TEMPLATE_DIR', get_stylesheet_directory());
define('WPC_NAMESPACE', 'wpc');

/**
 * autoloader for the theme classes
 * @param  string $controller the name of the class/controller
 * @return void             includes the controller in autoloader
 */

function __autoload($controller) {
  if(file_exists(WPC_TEMPLATE_DIR.'/wpc/classes/'.$controller.'.php')){
    require_once(WPC_TEMPLATE_DIR.'/wpc/classes/'.$controller.'.php');
  }
}

WPC_Load::load();