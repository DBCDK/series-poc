<?php

/**
 * @file
 * Renders series on objectpages
 */

class SeriesOnObjects
{
    public function render_series_on_object($object)
    {
        $result = '';
        if (!isset($object)) {
            return $result;
        }
        $work = $this->get_object_series_from_api($object);
        foreach ($work->series as $series_item) {
            $item = $this->createRenderItem($series_item, $work);
            $result .= theme('series_poc_object', array('item' => $item));
        }
        return $result;
    }

    public function render_series_in_carousel($object)
    {
        $results = [];
        if (!isset($object)) {
            return null;
        }
        $work = $this->get_object_series_from_api($object);
        foreach ($work->series as $series_item) {
            $results[] =  $this->createCarouselItem($series_item, $work);
        }
        return $results;
    }

    public function check_work_is_in_example($pid)
    {
        if ($this->isInExampleData($pid)) {
            return $this->renderMarkup($pid);
        } else {
            return null;
        }
    }

    private function renderMarkup($pid)
    {
        $markup = '';
        $client = new SeriesServiceClient();
        $work = $client->get_work($pid);
        if (isset($work->series_memberships)) {
            foreach ($work->series_memberships as $member_ship) {
                $vars = get_object_vars($member_ship);
                $title = array_keys($vars)[0];
                $number = $vars[$title][0];
                $link = series_poc_create_link($title, ['series' => $title]);
                $markup .= '<div class="series-poc-search-series">' . $number . '. del af ' . $link  . '</div>';
            }
        }
        if (isset($work->universe_title)) {
            $universe_string = ' universet: ';
            if (strpos(strtolower($work->universe_title), 'universe') !== false) {
                $universe_string = '';
            }
            $link = series_poc_create_link($work->universe_title, ['universe' => $work->universe_title]);
            $markup .= '<div class="series-poc-search-universe">Del af ' . $universe_string  . $link . '</div>';
        }
        return $markup;
    }

    private function isInExampleData($pid): bool
    {
        $client = new SeriesServiceClient();
        $data = $client->get_work_data();
        if (in_array($pid, $data->works)) {
            return true;
        }
        return false;
    }

    private function createCarouselItem($series_item, $work)
    {
        $result = $this->get_covers_on_object($series_item, $work, 100);
        $result->title =  $series_item->title;
        return $result;
    }

    private function createRenderItem($series_item, $work): SeriesObject
    {
        $item = new SeriesObject();
        $covers = $this->get_covers_on_object($series_item, $work, 100);
        $item->covers = array_slice($covers->items, $covers->start, 3);

        $covers = [];
        foreach ($item->covers as $cover) {
            $covers[] = '<li class="ting-series-object-item">' . $cover . '</li>';
        }
        $item->covers = $covers;
        $item->title = series_poc_create_link($series_item->title, ['series' => $series_item->title]);
        $item->abstract = $series_item->description;

        if (isset($series_item->universe_title)) {
            $item->universe = Series::get_universe_title($series_item);
        } else {
            $item->universe = '';
        }
        $item->type = 'Serie';
        $item->actionButton = series_poc_create_link('Se hele serien', ['series' => $series_item->title]);

        if (isset($series_item->read_first) && $series_item->read_first) {
            $item->read_first = "Læs denne serie først";
        }
        return $item;
    }

    private function get_covers_on_object($series_item, $work, $number_off_items)
    {
        $result = new stdClass();
        $result->items = [];
        $cover_objects = $this->get_next_series_objects($series_item, $work, $number_off_items);
        $i = 0;

        foreach ($cover_objects as $cover_object) {
            $result->items[] = $this->create_cover_object($cover_object, $series_item);
            if (isset($cover_object->current) && $cover_object->current) {
                $result->start = $i;
            }
            $i++;
        }
        return $result;
    }

    private function create_cover_object($cover_object, $series_item)
    {
        $cover_item = new stdClass();
        $cover_item->currentClass = '';
        $cover_item->cover = $this->render_cover_object($cover_object);
        $cover_item->title = $cover_object->work_metadata->title_full[0];
        $number = series_poc_get_number_in_series($cover_object, $series_item->title);
        if (isset($number)) {
            $cover_item->title  = $number . '. ' . $cover_item->title;
        }
        if (isset($cover_object->read_next)) {
            $cover_item->read_next = "Læs denne som den næste";
        }
        if (isset($cover_object->current)) {
            $cover_item->read_next = "Aktuel bog";
            $cover_item->currentClass = "ting-series-object-current";
        }
        return theme('series_poc_object_item', array('item' => $cover_item));
    }

    private function render_cover_object($cover_object)
    {
        $object = new CoverObject();
        $object->id = $cover_object->work_id;
        $object->title = $cover_object->work_metadata->title_full[0];
        $object->creators = [];
        return series_poc_render_cover($object);
    }

    private function get_next_series_objects($series_item, $work, $number_off_items)
    {
        $number_in_series = series_poc_get_number_in_series($work, $series_item->title);
        if (isset($number_in_series)) {
            return $this->get_next_series_objects_in_order($series_item, $number_in_series, $number_off_items);
        } else {
            return $this->get_next_series_objects_random_order($series_item, $work,  $number_off_items);
        }
    }

    private function get_next_series_objects_random_order($series_item, $work,  $number_off_items)
    {
        $cover_objects = [];

        foreach ($series_item->objects as $object) {
            if ($work->work_id == $object->work_id) {
                $object->current = true;
                array_unshift($cover_objects, $object);
            } else {
                $cover_objects[] = $object;
            }
        }
        return array_slice($cover_objects, 0, $number_off_items);
    }

    private function get_next_series_objects_in_order($series_item, $number_in_series, $number_off_items)
    {
        $cover_objects = [];
        foreach ($series_item->objects as $object) {
            $object_number_in_series = series_poc_get_number_in_series($object, $series_item->title);
            if ($object_number_in_series  == $number_in_series) {
                $object->current = true;
            }
            if ($object_number_in_series == $number_in_series + 1) {
                $object->read_next = true;
            }

            $cover_objects[] = $object;
        }
        return array_slice($cover_objects, 0, $number_off_items);
    }


    private function get_object_series_from_api($object)
    {
        $client = new SeriesServiceClient();
        $work = $client->get_work($object->id);
        $work->series = [];
        if (isset($work->series_memberships)) {
            foreach ($work->series_memberships as $member_ship) {
                $vars = get_object_vars($member_ship);
                $title = array_keys($vars)[0];
                $work->series[] = $client->get_works_in_series($title);
            }
        }
        return $work;
    }
}
