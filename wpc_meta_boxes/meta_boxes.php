<?php
global $meta_boxes;
$meta_boxes = array(
	 array(
    'key' => 'sample_image_meta',
    'post_types' => array('post'),
    'title' => __('Sample Image', WPC_TEXTDOMAIN),
    'label' => __('Sample Image', WPC_TEXTDOMAIN),
    'context' => 'advanced',
    'priority' => 'high',
    'field_type' => 'image',
  ),

  array(
    'key' => 'sample_file_meta',
    'post_types' => array('post'),
    'title' => __('Sample File', WPC_TEXTDOMAIN),
    'label' => __('Sample File', WPC_TEXTDOMAIN),
    'context' => 'advanced',
    'priority' => 'high',
    'field_type' => 'file',
  ),

  array(
    'key' => 'sample_link_meta',
    'post_types' => array('post', 'project'),
    'title' => __('Sample Link', WPC_TEXTDOMAIN),
    'label' => __('Sample Link', WPC_TEXTDOMAIN),
    'context' => 'advanced',
    'priority' => 'high',
    'field_type' => 'link',
  ),
);