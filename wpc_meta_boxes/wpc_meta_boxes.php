<?php

require_once('meta_boxes.php');

/**
 * a wrapper function to instantiate the metabox controls
 * @return void instantiates the mteaboxes
 */
function wpc_register_metaboxes(){
  new WPC_MetaBoxes('wpc/wpc_meta_boxes/templates/');
	wp_enqueue_script( 'wp-link' );
	wp_enqueue_script('wpc-code', get_template_directory_uri().'/wpc/wpc_meta_boxes/assets/js/wpc.js');
}

if ( is_admin() ) {
  //this instantiates the metabox controllers.
  add_action( 'load-post.php', 'wpc_register_metaboxes' );
  add_action( 'load-post-new.php', 'wpc_register_metaboxes' );

}
