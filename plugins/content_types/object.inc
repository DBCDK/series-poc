<?php

/**
 * @file
 * Panels render to display search results.
 */


$plugin = array(
  'title' => t('Ting simple navigation object'),
  'description' => t("Show categories on object pages"),
  'single' => TRUE,
  'render callback' => 'simple_navigation_ui_object_content_type_render',
  'category' => t('Ting'),
  'content_types' => array('ting_object'),
  'required context' => new ctools_context_required(t('Ting object'), 'ting_object'),
  'render last' => FALSE,
);

/**
 * Render the custom content type.
 */
function simple_navigation_ui_object_content_type_render($subtype, $conf, $panel_args, $context)
{
  $object = isset($context->data) ? ($context->data) : NULL;
  // Build the content type block.
  $block = new stdClass();
  $block->content = simple_navigation_ui_get_object_breadcrumb($object);

  return $block;
}
