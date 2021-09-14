<?php

/**
 * @file
 * Renders series 
 */

class Series
{

    function show_all_series($series)
    {
        $items = [];
        if (isset($series)) {
            foreach ($series as $series_item) {
                if (!empty($series_item)) {
                    $items[] = $this->show_all_series_item($series_item);
                }
            }
        }
        return theme('series_poc_main_all', array('items' => $items));
    }

    function show_all_series_item($series_item)
    {
        $item = new SeriesObject();

        $object = new CoverObject();
        $object->title = $series_item->title;
        $object->creators = [];
        $i = 1;

        foreach (array_slice($series_item->pids, 0, 3) as $pid) {
            $object->id = $pid;
            $item->cover .= '<div class="ting-series-all-cover-' . $i . '">' . series_poc_render_cover_series($object, $series_item->title) . '</div>';
            $i++;
        }

        $options = array(
            'html' => TRUE,
            'query' => ['series' => $series_item->title],
        );

        $item->title = l($series_item->title, 'serier', $options);
        $item->abstract = $series_item->description;
        if (isset($series_item->universe_title)) {
            $item->universe = self::get_universe_title($series_item);
        } else {
            $item->universe = '';
        }
        $item->type = 'Serie';
        if (isset($series_item->read_first) && $series_item->read_first) {
            $item->read_first = "Læs denne serie først";
        }

        return theme('series_poc_all_item', array('item' => $item));
    }

    function handle_series($series)
    {
        $items = [];
        //file_put_contents("/var/www/drupalvm/drupal/web/debug/series7.txt", print_r($series, TRUE), FILE_APPEND);
        $read_at_will = true;
        if (isset($series->objects)) {
            foreach ($series->objects as $series_item) {
                $number = series_poc_get_number_in_series($series_item, $series->title);
                if (isset($number) && $number == 1) {
                    $series_item->read_first = true;
                } else {
                    $series_item->read_first = false;
                }
                if (isset($number)) {
                    $read_at_will = false;
                }
                $items[] = $this->handle_series_item($series_item, $series->title);
            }
        }

        $object = new CoverObject();
        $object->title = $series->title;
        $object->creators = [];
        $i = 1;

        $covers = '';
        foreach (array_slice($series->pids, 0, 3) as $pid) {
            $object->id = $pid;
            $covers .= '<div class="ting-series-all-cover-' . $i . '">' . series_poc_render_cover_series($object, $series->title) . '</div>';
            $i++;
        }

        $title = $series->title;
        $abstract = $series->description;
        $universe = null;
        if (isset($series->universe_title)) {
            $universe = $this->get_universe_title($series);
        }
        $read_at_will_text = null;
        if ($read_at_will) {
            $read_at_will_text = 'Kan læses i vilkårlig rækkefølge';
        }
        $alt_title = null;
        if (isset($series->alternative_title)) {
            $alt_title = 'Alternativ titel: ' . $series->alternative_title[0];
        }
        return theme('series_poc_main', array(
            'items' => $items, 'title' => $title, 'abstract' => $abstract, 'universe' => $universe,
            'covers' => $covers, 'read_at_will' => $read_at_will_text, 'alt_title' => $alt_title
        ));
    }

    function handle_series_item($series_item, $series_title)
    {
        $item = new SeriesObject();

        $object = new CoverObject();
        $object->id = $series_item->work_id;
        $object->title = $series_item->work_metadata->title_full[0];
        if (isset($series_item->work_metadata->creator)) {
            $object->creators = $series_item->work_metadata->creator;
            $item->creators = $series_item->work_metadata->creator[0];
        } else {
            $object->creators = array();
            $item->creators = '';
        }

        $item->cover = series_poc_render_cover($object);
        $url = 'ting/object/' . $object->id;
        $item->title = l($series_item->work_metadata->title_full[0], $url);
        if (isset($series_item->work_metadata->abstract[0])) {
            $item->abstract = $series_item->work_metadata->abstract[0];
        } else {
            $item->abstract = '';
        }
        $item->number = series_poc_get_number_in_series($series_item, $series_title);
        $item->type = $series_item->work_metadata->type[0];
        if (isset($series_item->read_first) && $series_item->read_first) {
            $item->read_first = "Begynd med denne";
        }

        return theme('series_poc_item', array('item' => $item));
    }

    public static function get_universe_title($series)
    {
        $options = array(
            'html' => TRUE,
            'query' => array('universe' => $series->universe_title),
        );
        $universe_string = ' universet: ';
        if (strpos(strtolower($series->universe_title), 'universe') !== false) {
            $universe_string = '';
        }
        if (isset($series->number_in_universe)) {
            $prefix = 'Del ' . $series->number_in_universe . ' af ' . $universe_string;
        } else {
            $prefix = 'Del af' . $universe_string;
        }
        return l($prefix . $series->universe_title, '/serier', $options);
    }
}
