<?php
/**
 * @file
 * helpers.php
 *
 * Helper file for WPC.
 */

/**
 * A wrapper function for the WPC\Theme class.
 * @see  lib/WPC/Theme.php
 * @param  string $template the name of the template. looks for templates in theme directory. extenstion .php
 * @param  array  $args     an associative array of arguments to set as variables in the template
 * @return string           the rendered html from the template
 */
function wpc_theme($template, $args = array()) {

  //this whole thing is reliant on the theme class.
  $theme = new WPC\Theme($template);

  foreach($args as $key => $val){
    $theme->set($key, $val);
  }

  return $theme->fetch();

}

/**
 * A wrapper for get_post for the block type data.
 * @param string $slug the post slug
 * @return array       a wordpress post
 */
function wpc_get_block($slug, $type = 'block') {
  $args = array(
    'name' => $slug,
    'post_type' => $type,
    'post_status' => 'publish',
    'posts_per_page' => 1,
  );
  error_log(print_r($args, true));
  $posts = get_posts($args);
  //because it should always be a single post, so let's bump it out of the sub array
  $p = ! empty($posts) ? array_shift($posts) : new stdClass();
  $p->post_content = wpautop($p->post_content);//and wrap in p tags because for some reason this doesn't happen on get_posts.
  return $p;
}

/**
 * prints a block.
 * @param object $block a block of data from a post
 * @return void       print the data
 */
function wpc_render_block($block, $template = 'block', $title = '') {
  //arguments for the theme template.
  $args = array(
    'id' => $block->ID,
    'content' => $block->post_content,
    'title' => ($title == 'none') ? '' : $block->post_title,
    'name' => $block->post_name,
  );

  $edit = '';
  // We will append edit links to each block for easier access to editing inline content.
  if(is_user_logged_in() && ! empty($block->ID)){
    $id = $block->ID;
    $url = get_edit_post_link( $id );
    $edit = "<a href=\"$url\">edit</a>";
  }
  return wpc_theme($template, $args).$edit;
}

/**
 * use shortcodes to place content within your content
 * @param array $atts an array of attributes to use
 * @return string      a rendered block
 * @example [block type=post slug=hello-world] will put the title/content of the hello-world page into your post.
 */
function wpc_block_shortcode($atts) {
  $templatedir = str_replace(get_stylesheet_directory(), '', __DIR__);
  extract(
    shortcode_atts(
      array(
        'slug' => '',
        'template' => $templatedir . '/block.tpl',//by default, we look for block template in wpc block folder.
        'type' => 'wpc_block',
        'args' => array(),
        'title'   => '',
      ), $atts
    )
  );


  //The post slug and post type must be set for the get post function to work.
  if($slug != '' && $type != '') {
    $contents = '';

    $block = wpc_get_block($slug, $type);
    $args = ! empty($args) ? explode(',', $args) : array();

    //if we pass arguments in, set and pass them to the block.
    foreach($args as $arg) {
      $data = explode('=', $arg);
      $block->{$data[0]} = $data[1];
    }
    //and voila.
    $contents = wpc_render_block($block, $template, $title);

    return $contents;

  }

}
add_shortcode('block', 'wpc_block_shortcode');

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
