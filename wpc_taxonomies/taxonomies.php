<?php

global $taxonomies;

$taxonomies = array(
	array(
    'name' => 'block_categories',
    'post_types' => 'block',
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
          'slug' => 'uncategorized',
        ),
      ),
      array(
        'name' => __('General', WPC_TEXTDOMAIN),
        'args' => array(
          'description' => __('General', WPC_TEXTDOMAIN),
          'slug' => 'general',
        ),
      ),
    ),
  ),
);