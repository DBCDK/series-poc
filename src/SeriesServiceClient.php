<?php

/**
 * @file
 * Renders series on objectpages
 */

class SeriesServiceClient
{
    public function get_universe_with_series($title)
    {
        $pids = [];
        $universe = $this->get_universe($title);
        $universe->series = [];
        foreach ($universe->included_series as $series) {
            $universe->series[] = $this->get_works_in_series($series);
        }

        foreach ($universe->series as $series) {
            $pids  = array_merge($pids, $series->pids);
        }
        $single_pids = array_diff($universe->included_works, $pids);
        if (isset($single_pids)) {
            $universe->single_works = [];
            foreach ($single_pids as $pid) {
                $universe->single_works[] = $this->get_work($pid);
            }
        }
        return $universe;
    }

    public function get_universe($title)
    {
        $parameters = 'title=' . rawurlencode($title);
        $results = $this->request('universe', $parameters);
        if (isset($results->statusCode)) {
            return null;
        } else {
            return $results;
        }
    }

    public function get_series($title)
    {
        $parameters = 'title=' . rawurlencode($title);
        $results = $this->request('series', $parameters);
        if (isset($results->statusCode)) {
            return null;
        } else {
            return $results;
        }
    }

    public function get_works_in_series($title)
    {
        $parameters = [];
        $objects = [];
        $series = $this->get_series($title);
        if (isset($series) && isset($series->pids)) {
            $startTime = explode(' ', microtime());
            foreach ($series->pids as $pid) {
                $parameters[] = 'workid=' . $pid;
            }
            $results = $this->multi_request('pid', $parameters);
            $stopTime = explode(' ', microtime());
            $time = floatval(($stopTime[1] + $stopTime[0]) - ($startTime[1] + $startTime[0]));
            if (isset($results)) {
                $series->objects = $results;
            }
        }
        return $series;
    }

    public function get_work($pid)
    {
        $parameters = 'workid=' . $pid;
        $results = $this->request('pid', $parameters);
        if (isset($results->statusCode)) {
            return null;
        } else {
            return $results;
        }
    }

    public function get_all_series($type)
    {
        try {
            $param = drupal_get_query_parameters();
            $results = $this->request($type . '-all', '');
            if ($type == 'series') {
                $all_series = $results->series;
            } else {
                $all_series = $results->universes;
            }

            if (isset($param['category'])) {
                $category = $param['category'];
                $cat_series = [];
                foreach (Menu::get_category_data() as $key => $title) {
                    if ($title['category'] == $category) {
                        $cat_series[] = $key;
                    }
                }
                $temp = [];
                foreach ($all_series as $a) {
                    if (in_array($a, $cat_series)) {
                        $temp[] = $a;
                    }
                }
                $all_series = $temp;
            }

            if (isset($results->statusCode)) {
                return null;
            } else {
                foreach ($all_series as $series) {
                    $parameters[] = 'title=' . rawurlencode($series);
                }
                $series_data = $this->multi_request($type, $parameters);

                return $series_data;
            }
        } catch (Exception $e) {
        }
    }

    public function get_work_data()
    {
        $data = variable_get('series_poc_work_data', null);
        if (isset($data)) {
            return $data;
        } else {
            $results = $this->request('pid-all', '');
            variable_set('series_poc_work_data', $results);
            return $results;
        }
    }

    private function request($action, $parameters)
    {
        $service_url = 'https://series-poc.demo.dbc.dk/';
        $service_url .= $action . '?' . $parameters;
        $curl = curl_init($service_url);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Accept: application/json'
            )
        );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
        }
        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            //TODO Handle error
        }
        return  $decoded;
    }

    private function multi_request($action, $parameters)
    {
        $requests = [];
        foreach ($parameters as $parameter) {
            $service_url = 'https://series-poc.demo.dbc.dk/';
            $service_url .= $action . '?' . $parameter;
            $requests[] = $this->buildCurlRequest($service_url);
        }

        try {
            $results = curl_multi($requests);
            $decoded_results = [];

            if (is_array($results)) {
                foreach ($results as $result) {
                    $decoded_results[] = json_decode($result);
                }
            } else {
                $decoded_results[] = json_decode($results);
            }
            return $decoded_results;
        } catch (Exception $e) {
        }
    }

    private function buildCurlRequest($url)
    {
        $curl_session = array();
        $curl_session['endpoint'] = $url;

        $curl_options = array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        );
        $curl_session['options'] = $curl_options;
        return $curl_session;
    }
}
