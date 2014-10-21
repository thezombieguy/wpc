<?php
/**
 * Class to load taxonomies
 *
 * @author Bryan Trudel
 * @package WPC
 */
class WPC_Taxonomies {

	public $taxonomies;

	public function __construct()
	{
		$this->load();
		$this->register();
	}

	public function load()
	{
		global $taxonomies;
		$this->taxonomies = !empty($taxonomies) ? $taxonomies : array();
	}

	/**
	 * registers the taxonomy data with wordpress.
	 * @return [type] [description]
	 */
	public function register()
	{

		foreach($this->taxonomies as $taxonomy)
		{
			register_taxonomy(
				$taxonomy['name'],
				$taxonomy['post_types'],
				$taxonomy['args']
			);

			//If there are terms associated with taxonomy, then by all means load them in.
			if(isset($taxonomy['terms'])){
				foreach($taxonomy['terms'] as $term)
				{
					wp_insert_term(
						$term['name'], // the term
						$taxonomy['name'], // the taxonomy
						$term['args']
					);
				}
			}

		}

	}

}
