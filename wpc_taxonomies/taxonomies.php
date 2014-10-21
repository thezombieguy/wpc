<?php

global $taxonomies;

$taxonomies = array(
	array(
		'name' => WPC_NAMESPACE . '_block_categories',
		'post_types' => WPC_NAMESPACE . '_block',
		'args' => array(
			'label' => __( 'Block Categories' ),
			'rewrite' => array( 'slug' => 'block-categories' ),
			'hierarchical' => true,
			'show_tagcloud' => true,
		),
		'terms' => array(
			array(
				'name' => __('Uncategorized', WPC_TEXTDOMAIN),
				'args' => array(
					'description' => __('Uncategorized', WPC_TEXTDOMAIN),
					'slug' => WPC_NAMESPACE . '_uncategorized',
				),
			),
			array(
				'name' => __('General', WPC_TEXTDOMAIN),
				'args' => array(
					'description' => __('General', WPC_TEXTDOMAIN),
					'slug' => WPC_NAMESPACE . '_general',
				),
			),
		),
	),
);
