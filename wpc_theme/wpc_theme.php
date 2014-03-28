<?php
require_once('classes/WPC_Theme.php');

/**
 * a wrapper function for the block theme class.
 * @param  string $template the name of the template. loosk for templates in theme directory. extenstion .php
 * @param  array  $args     an associative array of arguments to set as variables in the template
 * @return string           the rendered html from the template
 */
function wpc_theme($template, $args = array()) {

  //this whole thing is reliant on the theme class.
  $theme = new WPC_Theme($template);

  foreach($args as $key => $val){
    $theme->set($key, $val);
  }

  return $theme->fetch();

}

/**
 * register rss short code feature
 * @param  array $atts an array of attributes found in the short code tag
 * @return string       themes content for the shortcode rss generator.
 * @example [theme template='templates/blocks/block' args='title=bob,content=fred']
 */
function theme_shortcode( $atts ) {
  extract(shortcode_atts(array(
    "template" => '',
    "args" => '',
  ), $atts));

  if ( $template != "") {

    $args = explode(',', $args);
    $tmp =array();

    foreach($args as $arg) {
      $data = explode('=', $arg);
      $tmp[$data[0]] = $data[1];
    }

    $args = $tmp;

    $contents = '';
    $contents = wpc_theme($template, $args);
    return $contents;
  }
}
add_shortcode( 'theme', 'theme_shortcode');