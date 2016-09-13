<?php

/**
 * a print utility function for debugging
 * @param array $data the data you want to dump.
 * @return void      print debug info to screen
 */
function print_rr($data = array())
{
  echo '<pre>';
  $debug = array_shift( debug_backtrace() );
  echo "<sub>{$debug['file']} : line {$debug['line']}</sub>'\n";
  print_r( $data );
  echo '</pre>';
}

/**
 * function to generate a full site url from a relative path.
 * @param string $path relative to the wordpress install directory.
 * @return string      returns the url relative to site_url
 */
function url($path)
{
  $url = home_url( $path );
  return $url;
}

/**
 * a function to generate a link
 * @param string $path  the relative path. see url()
 * @param string $title the name for the a tag
 * @return string       html formatted a link tag.
 */
function l($path, $title = '', $attributes = array())
{
  $url = ( preg_match( '/http\:\/\//', $path ) ) ? $path : self::url( $path );
  $attrs = array();

  foreach ( $attributes as $key => $value ){
    $attrs[] = "$key=\"$value\"";
  }

  $attributes = implode( ' ', $attrs );
  $link = "<a href=\"$url\" $attributes>$title</a>";
  return $link;
}

/**
 * extracts the filename from the end of a path
 * @param string $path the path containing the filename
 * @return string      the filename
 *
 * /wp-content/uploads/.../filename.jpg returns filename.jpg
 */
function filename_from_path($path)
{
  $data = explode( '/', $path );
  return $data[ count( $data ) -1 ];
}

/**
 * Let's register the block custom post type.
 */
if ( ! function_exists('wpc_block') ) {

  // Register Custom Post Type
  function wpc_block() {

    $labels = array(
      'name'                  => _x( 'Blocks', 'Post Type General Name', 'wpc' ),
      'singular_name'         => _x( 'Block', 'Post Type Singular Name', 'wpc' ),
      'menu_name'             => __( 'Blocks', 'wpc' ),
      'name_admin_bar'        => __( 'Block', 'wpc' ),
      'archives'              => __( 'Item Archives', 'wpc' ),
      'parent_item_colon'     => __( 'Parent Block:', 'wpc' ),
      'all_items'             => __( 'All Blocks', 'wpc' ),
      'add_new_item'          => __( 'Add New Block', 'wpc' ),
      'add_new'               => __( 'Add New', 'wpc' ),
      'new_item'              => __( 'New Block', 'wpc' ),
      'edit_item'             => __( 'Edit Block', 'wpc' ),
      'update_item'           => __( 'Update Block', 'wpc' ),
      'view_item'             => __( 'View Block', 'wpc' ),
      'search_items'          => __( 'Search Block', 'wpc' ),
      'not_found'             => __( 'Not found', 'wpc' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'wpc' ),
      'featured_image'        => __( 'Featured Image', 'wpc' ),
      'set_featured_image'    => __( 'Set featured image', 'wpc' ),
      'remove_featured_image' => __( 'Remove featured image', 'wpc' ),
      'use_featured_image'    => __( 'Use as featured image', 'wpc' ),
      'insert_into_item'      => __( 'Insert into Block', 'wpc' ),
      'uploaded_to_this_item' => __( 'Uploaded to this Block', 'wpc' ),
      'items_list'            => __( 'Blocks list', 'wpc' ),
      'items_list_navigation' => __( 'Blocks list navigation', 'wpc' ),
      'filter_items_list'     => __( 'Filter Block list', 'wpc' ),
    );
    $args = array(
      'label'                 => __( 'Block', 'wpc' ),
      'description'           => __( 'Block', 'wpc' ),
      'labels'                => $labels,
      'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
      'taxonomies'            => array( 'category', 'post_tag' ),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
    );
    register_post_type( 'wpc_block', $args );

  }
  add_action( 'init', 'wpc_block', 0 );

}
