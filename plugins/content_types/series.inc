<?php

/**
 * @file
 * Panels pane to display a ting collection.
 */

$plugin = array(
  'title' => t('Series Poc show case on objects version 2'),
  'description' => t("Show series on object pages"),
  'single' => TRUE,
  'render callback' => 'series_poc_series_content_type_render',
  'category' => t('Ting'),
  'content_types' => array('ting_object'),
  'required context' => new ctools_context_required(t('Ting object'), 'ting_object'),
  'render last' => FALSE,
);

/**
 * Implements hook_ID_content_type_render().
 */
function series_poc_series_content_type_render($subtype, $conf, $args, $context)
{
  $block = new stdClass();
  $title = '';
  $object = isset($context->data) ? ($context->data) : NULL;

  $series = series_poc_show_series_in_carousel($object);

  //file_put_contents("/var/www/drupalvm/drupal/web/debug/car1.txt", print_r($series, TRUE), FILE_APPEND);
  if (isset($series)) {

    $title = $series[0]->title;
    foreach ($series as $series_item) {
      $carousels[] = array(
        '#type' => 'ding_carousel',
        '#title' => $series_item->title,
        //'#path' => '',
        '#items' => $series_item->items,
        '#offset' => 3,
        // Add a single placeholder to fetch more content later if there is more
        // content.
        '#placeholders' => 1,
        '#start' => $series_item->start,
      );
    }

    $block->title = '';
    $block->content = array(
      '#type' => 'ding_tabbed_carousel',
      '#title' => $title,
      '#tabs' => $carousels,
    );
  }
  return $block;
}
