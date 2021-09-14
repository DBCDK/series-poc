<?php

/**
 * @file
 * Renders menu
 */

class Menu
{

    function handle_menu_universe($universe)
    {
      $category = $this->get_category_series($universe);
      $series_item = '';
      foreach ($universe->series as $series) {
        $series_item .= $this->make_series_menu_item($series->title, 'series', false);
      }
      $series_menu_item = '<ul class="series-menu-sub-level-2">' . $series_item . '</ul>';
    
      $univers_menu_item = '<ul class="series-menu-sub-level">' . $this->make_series_menu_item($universe->title, 'universe', true, $series_menu_item) . '</ul>';
      return $this->handle_menu($category, $univers_menu_item);
    }
    
    
    function handle_menu_series($series)
    {
      $category = $this->get_category_series($series);
      if (isset($series->universe_title)) {
        $client = new SeriesServiceClient();
        $universe = $client->get_universe($series->universe_title);
    
        $series_menu_item = '<ul class="series-menu-sub-level-2">';
        foreach ($universe->included_series as $universe_series) {
          if ($series->title == $universe_series) {
            $series_menu_item .= $this->make_series_menu_item($universe_series, 'series', true);
          } else {
            $series_menu_item .= $this->make_series_menu_item($universe_series, 'series', false);
          }
        }
        $series_menu_item .= '</ul>';
        $univers_menu_item = '<ul class="series-menu-sub-level">' . $this->make_series_menu_item($series->universe_title, 'universe', false, $series_menu_item) . '</ul>';
        return $this->handle_menu($category, $univers_menu_item);
      } else {
        $series_menu_item = '<ul class="series-menu-sub-level series-menu-sub-level-active">' . $this->make_series_menu_item($series->title, 'series', true) . '</ul>';
        return $this->handle_menu($category, $series_menu_item);
      }
    }
    
    public static function get_category_series($series)
    {
      foreach (series_poc_get_category_data() as $key => $title) {
        if ($key == $series->title) {
          return $title['category'];
        }
      }
    }
    
    function handle_menu_categories()
    {
      $parameters = drupal_get_query_parameters();
    
      if (isset($parameters['category'])) {
        $category = $parameters['category'];
        return $this->handle_menu($category);
      } else {
        return $this->handle_menu();
      }
    }
    
    function handle_menu($category = '', $subitems = '')
    {
      $menu_items = [];
      $items = [
        'romaner' => 'serier?category=romaner',
        'krimi' => 'serier?category=krimi',
        'fantasy' => 'serier?category=fantasy',
        'fag' => 'serier?category=fag',
        'børn' => 'serier?category=børm',
      ];
    
      foreach ($items as $key => $item) {
        if ($key == $category) {
          $menu_items[] = $this->make_menu_item($item, $key, true, $subitems);
        } else {
          $menu_items[] = $this->make_menu_item($item, $key);
        }
      }
      return theme('series_poc_menu', array('items' => $menu_items));
    }
    
    function make_series_menu_item($title, $type, $active = false, $subitems = '')
    {
      $class = 'series-menu-item';
      if ($active) {
        $class .= ' series-menu-item-active';
      }
      $options = array(
        'html' => TRUE,
        'query' => array($type => $title),
      );
      $link = l($title, 'serier', $options);
    
      return '<li class="' . $class . '">' . $link . $subitems  . '</li>';
    }
    
    function make_menu_item($item, $key, $active = false, $subitems = '')
    {
      $class = 'series-menu-item';
      if ($active) {
        $class .= ' series-menu-item-active';
        if ($subitems == '') {
          $class .= ' series-menu-item-selected';
        }
      }
      $options = array(
        'html' => TRUE,
        'query' => array('category' => $key),
      );
      $link = l(ucfirst($key), 'serier', $options);
    
      return '<li class="' . $class . '">' . $link . $subitems . '</li>';
    }
    
}