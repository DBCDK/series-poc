<?php

/**
 * @file
 * Panels render to display search results.
 */


$plugin = array(
  'title' => t('Series Poc show case on objects'),
  'description' => t("Show seris on object pages"),
  'single' => TRUE,
  'render callback' => 'series_poc_ui_object_content_type_render',
  'category' => t('Ting'),
  'content_types' => array('ting_object'),
  'required context' => new ctools_context_required(t('Ting object'), 'ting_object'),
  'render last' => FALSE,
);

/**
 * Render the custom content type.
 */
function series_poc_ui_object_content_type_render($subtype, $conf, $panel_args, $context)
{
  $object = isset($context->data) ? ($context->data) : NULL;
  // Build the content type block.
  $block = new stdClass();
  $block->content = series_poc_show_series_on_object($object);

  return $block;
}
