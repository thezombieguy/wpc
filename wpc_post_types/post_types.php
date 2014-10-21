<?php

global $post_types;

$post_types = array(
	array(
		'post_type' => WPC_NAMESPACE . '_block',
		'args' => array(
			'description' => __('Data blocks'),
			'labels' => array(
				'name' => __('Blocks', WPC_TEXTDOMAIN),// required
				'singular_name' => __('Block', WPC_TEXTDOMAIN),// required
				'add_new' => __('Add New Block', WPC_TEXTDOMAIN),
				'add_new_item' => __('Add New Block', WPC_TEXTDOMAIN),
			),
			'public' => true,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'page-attributes',
				'thumbnail',
			),
			'taxonomies' => array( 'block_tag' ),
		),
	),
);


