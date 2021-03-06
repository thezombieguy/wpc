<?php
namespace WPC;
/**
 * View class
 *
 * Loads a view.
 *
 * @author Bryan Trudel
 * @package WPC
 */
   class Theme {

    private $pageVars = array();
    private $template;

    /**
     *  Loads the template passted into the class
     *
     * @param   string  $template   an array containing routing information
     * @return  void
     */
    public function __construct($template)
    {
      $this->loadTemplate($template);
    }

    /**
     * Load a template
     * @param  string $template the template path/name you want to use
     * @return void           sets the template
     */
    public function loadTemplate($template)
    {
      $this->template = get_stylesheet_directory().'/'.$template .'.php';
      if (!file_exists($this->template)) {
        throw new \Exception("File does not exist: " . $this->template, 1);
      }
    }

    /**
     *  Set vars in a view template
     *
     * @param   string  $var   the php variable you want to set
     * @param   mixed  $val   the value you want to assign to the var. This may also be an array, object, integer etc.
     * @return  void
     */
    public function set($var, $val)
    {
      $this->pageVars[$var] = $val;
    }

    /**
     * Render the view template
     *
     * @return  void
     */
    public function render()
    {
      extract($this->pageVars);

      ob_start();
      require($this->template);
      echo ob_get_clean();
    }

    /**
     * Fetch the view template for use in php variables
     *
     * @return  string  $contents The php template
     */
    public function fetch()
    {
      extract($this->pageVars);

      ob_start();
      require($this->template);
      $contents = ob_get_contents();
      ob_end_clean();
      return $contents;
    }


  }