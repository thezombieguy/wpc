<?php

/**
 * Utility class for various functions
 *
 * @author Bryan Trudel
 * @package WPC
 */
class WPC_Utils
{

  /**
   * a print utility function for debugging
   * @param  array  $data the data you want to dump.
   * @return void       print debug info to screen
   */
  public static function print_rr($data = array())
  {
    echo '<pre>';
    $debug = array_shift(debug_backtrace());
    echo "<sub>".$debug['file'] . ": line " . $debug['line']."</sub>\n";
    print_r($data);
    echo '</pre>';

  }


  /**
   * function to generate a full site url from a relative path.
   * @param  string $path relative to the wordpress install directory.
   * @return string       returns the url relative to site_url
   */
  public static function url($path, $options = array())
  {
    $url = home_url($path);
    return $url;
  }

  /**
   * a function to generate a link
   * @param  string $path  the relative path. see url()
   * @param  string $title the name for the a tag
   * @return string        html formatted a link tag.
   */
  public static function l($path, $title = '', $attributes = array())
  {
    $url = (preg_match('/http\:\/\//', $path)) ? $path : self::url($path);
    $attrs = array();

    foreach($attributes as $key => $value){
      $attrs[] = "$key=\"$value\"";
    }

    $attributes = implode(' ', $attrs);
    $link = "<a href=\"$url\" $attributes>$title</a>";
    return $link;
  }

  /**
   * extracts the filename from the end of a path
   * @param  string $path the path containing the filename
   * @return string       the filename
   *
   * /wp-content/uploads/.../filename.jpg returns filename.jpg
   */
  public static function filename_from_path($path)
  {
    $data = explode('/', $path);
    return $data[count($data) -1];
  }

}
