<?php

/**
 * @file
 * Series controller
 */

class SeriesController
{
    private $client;
    private $menu;

    public function __construct()
    {
        $this->client = new SeriesServiceClient();
        $this->menu = new Menu();
    }

    function handle_series($parameters)
    {
        $render_item = new RenderItem();
        $series = new Series();
        $series_data_from_api = $this->client->get_works_in_series($parameters['series']);

        $render_item->items = $series->handle_series($series_data_from_api);
        $render_item->menu = $this->menu->handle_menu_series($series_data_from_api);
        $render_item->breadcrumb = series_poc_get_breadcrumb($series_data_from_api);
        return $render_item;
    }
    function handle_universe($parameters)
    {
        $render_item = new RenderItem();
        $universe_data_from_api = $this->client->get_universe_with_series($parameters['universe']);
        $universe = new Universe();
        $render_item->items = $universe->handle_universe($universe_data_from_api);
        $render_item->menu  = $this->menu->handle_menu_universe($universe_data_from_api);
        $render_item->breadcrumb = series_poc_get_breadcrumb($universe_data_from_api, true);
        return $render_item;
    }

    function handle_universe_overview($parameters)
    {
        $render_item = new RenderItem();
        $universe = new Universe();

        $all_universes = $this->client->get_all_series('universe');
        
        $render_item->items = $universe->show_all_universes($all_universes);
        $render_item->menu = $this->menu->handle_menu_categories();
        $render_item->breadcrumb = series_poc_get_breadcrumb();
        $render_item->select = series_poc_get_select(true);
        return $render_item;
    }

    function handle_series_overview($parameters)
    {
        $render_item = new RenderItem();
        $series = new Series();

        $all_series = $this->client->get_all_series('series');
        $render_item->items = $series->show_all_series($all_series);
        $render_item->menu  = $this->menu->handle_menu_categories();
        $render_item->breadcrumb = series_poc_get_breadcrumb();
        $render_item->select = series_poc_get_select(false);
        return $render_item;
    }
}
