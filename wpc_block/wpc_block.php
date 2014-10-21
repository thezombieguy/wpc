<?php

/**
 * use shortcodes to place content within your content
 * @param	array $atts an array of attributes to use
 * @return string			 a rendered block
 * @example	[block type=post slug=hello-world] will put the title/content of the hello-world page into your post.
 */
function wpc_block_shortcode($atts) {
	extract(
		shortcode_atts(
			array(
				'slug' => '',
				'template' => 'wpc/wpc_block/block.tpl',//by default, we look for block template in wpc block folder.
				'type' => 'post',
				'args' => array(),
				'title' 	=> '',
			), $atts
		)
	);

	//The post slug and post type must be set for the get post function to work.
	if($slug != '' && $type != '') {
		$contents = '';

		$block = wpc_get_block($slug, $type);
		$args = ! empty($args) ? explode(',', $args) : array();

		//if we pass arguments in, set and pass them to the block.
		foreach($args as $arg) {
			$data = explode('=', $arg);
			$block->{$data[0]} = $data[1];
		}
		//and voila.
		$contents = wpc_render_block($block, $template, $title);

		return $contents;

	}

}

/**
 * a wrapper for get_post for the block type data.
 * @param	string $slug the post slug
 * @return array			 a wordpress post
 */
function wpc_get_block($slug, $type) {

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
 * @param	object $block a block of data from a post
 * @return void				print the data
 */
function wpc_render_block($block, $template = '', $title = '') {

	//arguments for the theme template.
	$args = array(
		'id' => $block->ID,
		'content' => $block->post_content,
		'title' => ($title == 'none') ? '' : $block->post_title,
	);

	$template = ! empty($template) ? $template : 'block';
	$edit = '';

	// We will append edit links to each block for easier access to editing inline content.
	if(is_user_logged_in() && ! empty($block->ID)){
		$id = $block->ID;
		$edit = "<a href=\"/wp-admin/post.php?post=$id&action=edit\">edit</a>";
	}
	return wpc_theme($template, $args).$edit;
}

add_shortcode('block', 'wpc_block_shortcode');
