<?php
/**
 * Class to register custom post types in WordPress
 *
 * @author Bryan Trudel
 * @package WPC
 */
class WPC_PostTypes
{

	public $post_types;

	public function __construct()
	{
		$this->load();
		$this->create();
	}

	/**
	 * create post types based on schema data
	 * @return void registers post types from global $post_types array.
	 */
	public function create()
	{

		foreach($this->post_types as $type)
		{
			extract($type);
			register_post_type($post_type, $args);
		}

	}

	/**
	 * loads the post types schema
	 * @return void load post_types array
	 */
	public function load()
	{
		global $post_types;
		$this->post_types = !empty($post_types) ? $post_types : array();
	}

}
