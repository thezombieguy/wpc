<?php

/**
 * This class allows adding Meta Boxes to post types.
 *
 * @author Bryan Trudel
 * @package WPC
 *
 * Currently this only allows for meta boxes that are input text fields. No other html form input specified.
 */
class WPC_MetaBoxes
{

	public $meta = array();
	public $templates;

	public function __construct($templates = '')
	{
		//maybe just move init here?
		$this->templates = $templates;
		$this->load();
		$this->init();
	}

	/**
	 * initalize the wordpress hooks
	 * @return void initializes adding and saving of the meta boxes
	 */
	public function init()
	{
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );

	}

	/**
	 * add a meta box to wordpress post type
	 * @param string $post_type the post type. page, post, custom etc.
	 * @see	http://codex.wordpress.org/Function_Reference/add_meta_box#Parameters
	 */
	public function add($post_type)
	{

		foreach ( $this->meta as $box ){

			//if we want this field in a specific post type...
			if ( in_array( $post_type, $box->post_types ) ){

				add_meta_box(
					$box->id,//html ID for div.
					$box->title,//title
					array( $this, 'render' ),//callback to render.
					$post_type,//post type. page, post, special content type etc.
					$box->context,//where on the page should this appear.
					$box->priority,
					array( 'box' => $box )//callback arguments for the render function
				);

			}
		}

	}

	/**
	 * saves meta data to the wordpress database
	 * @param	string $post_id the ID of the post
	 * @param	array $post		The post data... this might be uesless
	 * @return void					updates the post in the database
	 */
	public function save($post_id)
	{
		/*
		* We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/
		foreach ( $this->meta as $box ){

			// Sanitize the user input.
			$mydata = sanitize_text_field( $_POST[ $box->field_name ] );

			// If no errors have occurred, update the meta field.
			if ( $this->permission( $box ) ) {
				update_post_meta( $post_id, $box->meta_key, $mydata );
			}
		}

	}

	//needs work. using a theme...when would i hook remove? idunno. easier to do if plugin turns off or unregisters or something
	//
	public function remove()
	{

		global $meta_boxes;//needs get meta.

		foreach ( $meta as $box ) {
			foreach ( $box['post_types'] as $type ) {
				remove_meta_box( 'postcustom', $type, $box['context'] );
			}
		}

	}

	/**
	 * check permissions for creating/updating meta box information.
	 * @param	array	$box the meta box object
	 * @return boolean			bollean value if permissions pass validating
	 */
	public function permission($box = array())
	{

		if ( ! isset( $_POST[ $box->wp_nonce_field['name'] ] ) ) {
			return false;
		}
		$nonce = $_POST[ $box->wp_nonce_field['name'] ];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, $box->wp_nonce_field['action'] ) ) {
			return false;
		}

		// If this is an autosave, our form has not been submitted,
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return false;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return false;
			}
		}

		return true;
	}


	/**
	 * renders the meta box inside the post type
	 * @param	array $post					the current post
	 * @param	array $callback_args arguments passed from the calling function
	 * @return void								prints the data on screen
	 */
	public function render($post, $callback_args)
	{

		$box = $callback_args['args']['box'];//if this is empty we shoudl error out or something.
		if ( empty($box) ){
			return;
		}
		wp_nonce_field( $box->wp_nonce_field['action'], $box->wp_nonce_field['name'] );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, $box->meta_key, true );
		$type = $box->field_type;
		$description = ! empty($box->description) ? $box->description : '';

		$args = array(
			'label' => $box->label,
			'id' => $box->id.'_id',//do this so the input ID differes from the wrapper id
			'type' => $type,
			'field' => $box->field_name,
			'value' => $value,
			'description' => $description,
		);

		print $this->theme( $this->templates.$type.'_box', $args );

	}

	/**
	 * load the meta box meta. It should be set as a global environment in config file
	 * @return array A complete array of all meta boxes we want to implement.
	 */
	public function load()
	{
		global $meta_boxes;

		$meta = ! empty($meta_boxes) ? $meta_boxes : array();

		foreach ( $meta as $box ) {

			if ( empty($box['key']) ) {
				continue;//don't bother doing anything here if there's no key provided.
			}

			//this could be an array of defaults, and array merged to complete.
			//I haven't decided to go this route yet as not a lot of this can be defaulted.

			$this->meta[] = (object) array(
				'id' => $box['key'],
				'title' => $box['title'],
				'label' => $box['label'],
				'description' => ! empty($box['description']) ? $box['description'] : '',
				'post_types' => ! empty($box['post_types']) ? $box['post_types'] : array(),
				'context' => isset($box['context']) ? $box['context'] : 'normal',
				'priority' => isset($box['priority']) ? $box['priority'] : 'low',
				'callback_args' => null,//not used yet
				'wp_nonce_field' => array(
					'action' => $box['key'].'_nonce_action',
					'name' => $box['key'].'_nonce_name',
					'referrer' => null,
					'echo' => null,
				),
				'field_name' => 'field_'.$box['key'],
				'field_type' => isset($box['field_type']) ? $box['field_type'] : 'textfield',
				'meta_key' => $box['key'],
			);
		}

		return $this->meta;
	}

	public function theme($template, $args = array()) {

		//this whole thing is reliant on the theme class.
		$theme = new WPC_Theme( $template );

		foreach ( $args as $key => $val ){
			$theme->set( $key, $val );
		}

		return $theme->fetch();

	}

}

