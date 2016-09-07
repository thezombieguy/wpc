<?php
namespace WPC;
/**
 * @file
 * Api.php
 */

/**
 * Api class to register endpoints for API use.
 * @see http://coderrr.com/create-an-api-endpoint-in-wordpress/
 */
class Api {

  /**
   * An array of enpoints based on WordPress reqrite api syntax.
   * @var array
   */   
  private $endpoints = array();

  /**
   * Query args that will be extracted from the WordPress redirect string for each endpoint.
   * @var array
   */
  private $query_args = array();

  /**
   * Mighty construcTOR!
   */
  public function __construct(array $endpoints) {
    $this->endpoints = $endpoints;
    $this->extract_query_args();
    $this->init();
  }

  /**
   * Extract query args from redirect and store for later.
   */
  public function extract_query_args() {
    foreach ($this->endpoints as $endpoint) {
      $redirect = ltrim($endpoint['redirect'], 'index.php?');
      parse_str($redirect, $args);
      foreach ($args as $arg => $val) {
        $this->query_args[$arg] = ''; 
      }
    }    
  }

  /**
   * Initialize the WordPress API callbacks.
   * @return void 
   */
  public function init() {
    add_filter('query_vars', array($this, 'add_query_vars'), 0);
    add_action('parse_request', array($this, 'sniff_requests'), 0);
    add_action('init', array($this, 'add_endpoint'), 0);
  }

  /**
   * Register the query vars you are using in your query.
   * @see  function extract_query_args for the arg extraction.
   * @param array $vars array
   */
  public function add_query_vars(array $vars) {
    foreach ($this->query_args as $var => $val) {
      $vars[] = $var;
    }
    return $vars;
  }

  /**
   * Adds the rewrite rule with WordPress API.
   */
  public function add_endpoint() {
    foreach ($this->endpoints as $endpoint) {
      // This is the API.
      add_rewrite_rule($endpoint['regex'], $endpoint['redirect'], $endpoint['after']);
    }
  }

  /**
   * Interrupts WordPress query to add your own handler to the output.
   * @return void
   */
  public function sniff_requests() {
    global $wp;
    // We will fire this only if the __api url param is set.
    if(isset($wp->query_vars['__api'])){
      // Handles callbacks within this api. Should invoke a callback function.
      $this->handle_request();
      exit;
    }
  }

  /**
   * Handles the request. 
   *
   * This could probably be integrated into invoke().
   */
  protected function handle_request() {
    global $wp;
    $this->invoke($wp->query_vars);
  }

  /**
   * Invokes a method/class callback from the WordPress args
   * @param  array  $args query args from WordPress
   * @return void   
   */
  protected function invoke(array $args) {

    $class = "\\" . stripslashes($args['handler']);
    // Does this method exist?
    if (method_exists(new $class, $args['callback'])) {
      $handler = new $class();
      call_user_func_array(array($handler, $args['callback']), array($args));
    }
    else {
      // No, break here.
      throw new \Exception("Handler callback not defined");
      
    }
  }

}
