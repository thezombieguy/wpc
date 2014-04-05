<?php

require_once('post_types.php');

/**
 * a wrapper function to register all custom post types
 * @return void instantiates the post types
 */
function wpc_register_post_types() {
  new WPC_PostTypes();
}

add_action( 'init', 'wpc_register_post_types' );