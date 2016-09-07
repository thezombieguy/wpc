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
function wpc_render_block($block, $template = 'partials/block', $title = '') {
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
