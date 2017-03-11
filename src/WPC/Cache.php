<?php
namespace WPC;
/**
 * @file
 * WPC_Cache.php
 *
 * @author Bryan Trudel
 * @package WPC
 */

/**
 * A persistent caching model that uses the filesystem.
 */
class Cache
{
  
  /**
   * The path where we will store the cache data. 
   * @param string
   */
  private $basepath = '';

  /*
   * Set up the base path for the cache templates. This folder needs write permissions.
   *
   * @return  void
   */
  public function __construct()
  {
    $uploads = wp_upload_dir();
    $this->basepath = $uploads['basedir'] . '/wpc_cache/';

    if (!is_dir($this->basepath)) {
      mkdir($this->basepath);
    }

  }
  /**
   * Retrieve a cached items
   *
   * @param   string  $id The unique identifier of the cahced item
   * @return  object  $cache  The cached object, or an empty object if no cache object exists
   */
  public function get($id)
  {
    $filename = $this->basepath.'cache_' . $id;
    $cache = (object)array('data' => '', 'time' => 0);
    $data = @file_get_contents($filename);
    if (!$data)
    {
      return $cache;
    }
    else
    {
      $cache->data = json_decode($data);
      $cache->time = filemtime($filename);
    }
    return $cache;
  }

  /**
   * Cache an object
   *
   * @param   string  $id The unique identifier of this cached object
   * @param   string  $data A string of data you want to cache. Can be html, serialized array etc.
   * @return  boolean $result returns if the object was cached or not.
   */
  public function set($id, $data)
  {
    $filename = $this->basepath.'cache_' . $id;
    $result = file_put_contents($filename, json_encode($data));
    if ($result === FALSE)
    {
      throw new \Exception("Error when trying to write to $filename, please make sure the dir is writable");
    }
    return $result;
  }

  /**
   * Clears every cached object. Complete destruction of all cache files in cache dir.
   *
   * @return  void
   */
  public function clear()
  {
    $directory = $this->basepath.'';
    $handler = opendir($directory);
    while ($file = readdir($handler))
    {
      if ($file != "." && $file != "..")
      {
        unlink($directory.$file);
      }
    }
    closedir($handler);
    return;
  }
}
