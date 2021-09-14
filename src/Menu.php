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
    foreach (self::get_category_data() as $key => $title) {
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

  public static function get_category_data()
  {
    $data = array(
      'Aske i munden, sand i skoen' => array(
        'category' => 'romaner'
      ),

      'Renée Ballard-serien' => array(
        'category' => 'krimi'
      ),

      'Harry Bosch-serien' => array(
        'category' => 'krimi'
      ),

      'Harry Potter' => array(
        'category' => 'fantasy'
      ),

      'The Harry Potter series' => array(
        'category' => 'fantasy'
      ),

      'Fantastic beasts (film)' => array(
        'category' => 'fantasy'
      ),

      'Fantastic beasts (filmmanuskript)' => array(
        'category' => 'fantasy'
      ),

      'Harry Potter (film)' => array(
        'category' => 'fantasy'
      ),

      'WOW - vild viden' => array(
        'category' => 'fag'
      ),

      'De 5' => array(
        'category' => 'børn'
      ),

      'De 5 (samling)' => array(
        'category' => 'børn'
      ),

      'Tænkepauser' => array(
        'category' => 'fag'
      ),

      'Totemdyrenes fald' => array(
        'category' => 'fantasy'
      ),

      'Totemdyrenes saga' => array(
        'category' => 'fantasy'
      ),

      'Kongens ranger' => array(
        'category' => 'fantasy'
      ),

      'Våbenbrødre' => array(
        'category' => 'fantasy'
      ),

      'Skyggens lærling' => array(
        'category' => 'fantasy'
      ),

      'Den sidste inkal' => array(
        'category' => 'romaner'
      ),

      'Før inkalen' => array(
        'category' => 'romaner'
      ),

      'Teknofædrene' => array(
        'category' => 'romaner'
      ),

      'Megalex' => array(
        'category' => 'romaner'
      ),

      'Metabaronernes kaste - Castaka' => array(
        'category' => 'romaner'
      ),

      'Metabaronernes kaste' => array(
        'category' => 'romaner'
      ),

      'John Difools hemmlighed' => array(
        'category' => 'romaner'
      ),

      'Inkalen' => array(
        'category' => 'fantasy'
      ),

      'Skøre facts' => array(
        'category' => 'fag'
      ),

      'Vide verden' => array(
        'category' => 'fag'
      ),

      'Rose-serien' => array(
        'category' => 'børn'
      ),

      'Cirklen åbnes' => array(
        'category' => 'børn'
      ),

      'Løvindens sang' => array(
        'category' => 'børn'
      ),

      'Magiens cirkel' => array(
        'category' => 'fantasy'
      ),

      'Asta' => array(
        'category' => 'børn'
      ),

      'Hårdes saga' => array(
        'category' => 'børn'
      ),

      'Wimpy Kid-serien' => array(
        'category' => 'børn'
      ),

      'Rowleys dagbog' => array(
        'category' => 'børn'
      ),

      'Den der lever stille' => array(
        'category' => 'romaner'
      ),

      'Skyggernes kniv (tegneserie)' => array(
        'category' => 'fantasy'
      ),

      'His dark materials (tegneserie)' => array(
        'category' => 'fantasy'
      ),

      'His dark materials' => array(
        'category' => 'fantasy'
      ),

      'His dark materials (Sæson 1)' => array(
        'category' => 'fantasy'
      ),

      'Det gyldne kompas (tegneserie)' => array(
        'category' => 'fantasy'
      ),

      'Forestillinger om Støv' => array(
        'category' => 'fantasy'
      ),

      'The book of dust' => array(
        'category' => 'fantasy'
      ),

      'Det gyldne kompas' => array(
        'category' => 'fantasy'
      ),


      'Cirklen universet' => array(
        'category' => 'fantasy'
      ),
      'His dark materials' => array(
        'category' => 'fantasy'
      ),
      'Inkal-sagaen' => array(
        'category' => 'romaner'
      ),
      'Jodoverset' => array(
        'category' => 'romaner'
      ),
      'Michael Connellys krimi-univers' => array(
        'category' => 'krimi'
      ),
      'Skandia' => array(
        'category' => 'fantasy'
      ),
      'Spirit animals' => array(
        'category' => 'fantasy'
      ),
      'Tortall universet' => array(
        'category' => 'fantasy'
      ),
      'Wimpy Kid' => array(
        'category' => 'børn'
      ),
      'Wizarding World' => array(
        'category' => 'fantasy'
      ),
    );
    return $data;
  }
}
