<?php

define('TEMPLATE_DIR', get_stylesheet_directory());
require('inc/WP_Block_Theme.php');

/**
 * use shortcodes to place content within your content
 * @param  array $atts an array of attributes to use
 * @return string       a rendered block
 * @example  [block type=post slug=hello-world] will put the title/content of the hello-world page into your post.
 */
function wp_block_shortcode($atts) {
  extract(
    shortcode_atts(
      array(
        "slug" => '',
        "template"    => 'wp_block/block.tpl',//by default, we look for block template in wpc block folder.
        "type"     => 'post',
        "args"   => array(),
        "title"  => ''
      ), $atts
    )
  );

  //The post slug and post type must be set for the get post function to work.
  if($slug != '' && $type != '') {
    $contents = '';

    $block = get_block($slug, $type);
    $args = !empty($args) ? explode(',', $args) : array();

    //if we pass arguments in, set and pass them to the block.
    foreach($args as $arg) {
      $data = explode('=', $arg);
      $block->{$data[0]} = $data[1];
    }
    //and voila.
    $contents = render_block($block, $template, $title);

    return $contents;

  }

}

/**
 * a wrapper for get_post for the block type data.
 * @param  string $slug the post slug
 * @return array       a wordpress post
 */
function get_block($slug, $type) {

  $args = array(
    'name' => $slug,
    'post_type' => $type,
    'post_status' => 'publish',
    'posts_per_page' => 1,
  );

  $posts = get_posts($args);

  $p = array_shift($posts);//because it should always be a single post, so let's bump it out of the sub array
  $p->post_content = wpautop($p->post_content);//and wrap in p tags because for some reason this doesn't happen on get_posts.

  return $p;
}

/**
 * prints a block.
 * @param  object $block a block of data from a post
 * @return void        print the data
 */
function render_block($block, $template = '', $title = '') {

  //arguments for the theme template.
  $args = array(
    'id' => $block->ID,
    'content' => $block->post_content,
    'title' => ($title == "none") ? '' : $block->post_title,
  );

  $template = !empty($template) ? $template : 'block';
  $edit = '';

  //this should check permissions big time. We will append edit links to each block for easier access to editing inline content.
  if(is_user_logged_in()){
    $id = $block->ID;
    $edit = "<a href=\"/wp-admin/post.php?post=$id&action=edit\">edit</a>";
  }
  return theme($template, $args).$edit;
}


/**
 * a wrapper function for the block theme class.
 * @param  string $template the name of the template. loosk for templates in theme directory. extenstion .php
 * @param  array  $args     an associative array of arguments to set as variables in the template
 * @return string           the rendered html from the template
 */
function theme($template, $args = array()) {

  //this whole thing is reliant on the theme class.
  $theme = new WP_Block_Theme($template);

  foreach($args as $key => $val){
    $theme->set($key, $val);
  }

  return $theme->fetch();

}

add_shortcode('block', 'wp_block_shortcode');
