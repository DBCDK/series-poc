<?php

/**
 * @file
 * Renders universe
 */

class Universe
{

    function show_all_universes($universes)
    {
        //file_put_contents("/var/www/drupalvm/drupal/web/debug/universe3.txt", print_r($universes, TRUE), FILE_APPEND);
        $items = [];
        if (isset($universes)) {
            foreach ($universes as $series_item) {
                if (!empty($series_item)) {
                    $items[] = $this->show_all_universes_item($series_item);
                }
            }
        }
        ////file_put_contents("/var/www/drupalvm/drupal/web/debug/universe4.txt", print_r($items, TRUE), FILE_APPEND);
        return theme('series_poc_main_all', array('items' => $items));
    }

    function handle_universe($universe)
    {
        $universe_item = new stdClass();
        $items = [];
        $universe_item->other_language_items  = [];
        $universe->series = $this->sort_series($universe);
        //file_put_contents("/var/www/drupalvm/drupal/web/debug/universe12.txt", print_r($universe, TRUE), FILE_APPEND);

        if (isset($universe->series)) {
            foreach ($universe->series as $series_item) {
                if (isset($series_item->number_in_universe) && $series_item->number_in_universe == 1) {
                    $series_item->read_first = true;
                } else {
                    $series_item->read_first = false;
                }
                $series = new Series();
                if ($this->is_another_language($series_item)) {
                    $universe_item->other_language_items[] = $series->show_all_series_item($series_item);
                } else {
                    $items[] = $series->show_all_series_item($series_item);
                }
            }
        }

        $universe_item->items = $items;

        $object = new CoverObject();
        $object->title = $universe->title;
        $object->creators = [];
        $i = 1;

        $covers = '';
        foreach (array_slice($universe->included_works, 0, 4) as $pid) {
            $object->id = $pid;
            $covers .= '<div class="ting-series-all-cover-' . $i . '">' . series_poc_render_cover_series($object, $universe->title) . '</div>';
            $i++;
        }
        $universe_item->covers = $covers;
        $universe_item->title = $universe->title;
        $universe_item->abstract = $universe->description;
        $universe_item->alt_title = null;
        if (isset($universe->alternative_title)) {
            $universe_item->alt_title = 'Alternativ titel: ' . $universe->alternative_title;
        }

        $universe_item->single_works = '';
        if (!empty($universe->single_works)) {
            $items = [];
            $series = new Series();
            foreach ($universe->single_works as $work) {
                //file_put_contents("/var/www/drupalvm/drupal/web/debug/uni1.txt", print_r($work, TRUE), FILE_APPEND);
                $items[] = $series->handle_series_item($work, '');
            }
            $universe_item->single_works = theme('universe_poc_single_works', array('items' => $items));
        }

        return theme('universe_poc_main', array('universe' => $universe_item));
    }

    function show_all_universes_item($series_item)
    {
        $item = new SeriesObject();

        $object = new CoverObject();
        $object->title = $series_item->title;
        $object->creators = [];
        $i = 1;

        $first_covers = '';
        $second_covers = '';
        foreach (array_slice($series_item->included_works, 0, 2) as $pid) {
            $object->id = $pid;
            $first_covers  .= '<div class="ting-universe-all-cover-' . $i . '">' . series_poc_render_cover_series($object, $series_item->title, 'universe') . '</div>';
            $i++;
        }

        foreach (array_slice($series_item->included_works, -2, 2) as $pid) {
            $object->id = $pid;
            $second_covers  .= '<div class="ting-universe-all-cover-' . $i . '">' . series_poc_render_cover_series($object, $series_item->title, 'universe') . '</div>';
            $i++;
        }

        $item->cover = $first_covers . $second_covers;

        $options = array(
            'html' => TRUE,
            'query' => ['universe' => $series_item->title],
        );

        $item->title = l($series_item->title, 'serier', $options);
        $item->abstract = $series_item->description;
        if (isset($series_item->universe_title)) {
            $item->unviverse = $series_item->universe_title;
        } else {
            $item->unviverse = '';
        }
        $item->type = 'Univers';
        $item->series = '';
        //file_put_contents("/var/www/drupalvm/drupal/web/debug/universe5.txt", print_r($item, TRUE), FILE_APPEND);
        return theme('series_poc_all_universe_item', array('item' => $item));
    }

    function sort_series($universe)
    {
        $first = [];
        $last = [];
        foreach ($universe->series as $series_item) {
            if (isset($series_item->number_in_universe)) {
                $first[] = $series_item;
            } else {
                $last[] = $series_item;
            }
        }
        usort($first, [$this, 'series_sort_compare']);
        return array_merge($first, $last);
    }

    function series_sort_compare($series1, $series2)
    {
        if ($series1->number_in_universe >= $series2->number_in_universe) {
            return 1;
        } else {
            return -1;
        }
    }


    function is_another_language($series)
    {
        $type = $series->objects[0]->work_metadata->type[0];
        $language = $series->objects[0]->work_metadata->language[0];
        if (in_array($type, ['Bog', 'Tegneserie']) && $language == 'eng') {
            return true;
        }
        //file_put_contents("/var/www/drupalvm/drupal/web/debug/uni4.txt", print_r($type . ' ' . $language, TRUE), FILE_APPEND);
        return false;
    }
}
