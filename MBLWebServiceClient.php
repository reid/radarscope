<?php

class MBLWebServiceClient {

    static protected $cache_dir = false;
    static protected $cache_duration = 3600;

    static public function request ($url) {

        if (!self::$cache_dir) self::$cache_dir = dirname(__FILE__) . '/cache';

        $file = self::$cache_dir . '/' . md5($url);

        if (!file_exists($file) || filemtime($file) < (time() - self::$cache_duration)) {

            $data = @file_get_contents($url);

            if (empty($data) || !is_array(json_decode($data, true))) { // fetch failed, use cached data
                touch($file);
                return file_get_contents($file);
            }

            $tmp = tempnam($cache_dir, 'RBF');
            file_put_contents($tmp, $data);
            rename($tmp, $file);

            return $data;

        } else {

            return file_get_contents($file);

        }

    }

}
