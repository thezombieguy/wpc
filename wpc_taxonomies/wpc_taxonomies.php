<?php

require_once('taxonomies.php');

/**
 * a wrapper to register taxonomies
 * @return void instantiates taxonomies and adds terms defined in schema.
 */
function wpc_register_taxonomies() {
  new WPC_Taxonomies();
}

add_action( 'init', 'wpc_register_taxonomies' );