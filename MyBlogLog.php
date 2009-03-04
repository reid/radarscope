<?php

require 'MBLApplication.php';
require 'MBLWebServiceClient.php';

class MyBlogLog {

    protected $user;

    protected $entries;

    public function __construct ($user) {
        $this->user = $user;
    }

    public function fetch ($api, $count = 60) {

        $q = array(
            'appid' => MBLApplication::getAppId(),
            'format' => 'json',
            'count' => $count
        );

        $url = 'http://mybloglog.yahooapis.com/v1/user/' . $this->user . '/' . $api . '?' . http_build_query($q);

        $json = MBLWebServiceClient::request($url);

        if (!$json) return false;

        return json_decode($json, true);

    }

    public function newWithMe () {

        $data = $this->fetch('newwithme');

        $output = array();

        if (!is_array($data)) return $output;

        foreach ($data['event'] as $entry) {
            $row = array();

            $row['site'] = $entry['name'];
            $row['time'] = MBLApplication::ago(strtotime($entry['created_at']));

            switch ($entry['name']) {
                case 'twitter':
                    $row['content'] = $entry['message'];
                    $row['source'] = 'http://twitter.com/' . $row['username'];
                    break;
                case 'flickr_latest':
                    $row['site'] = 'flickr';
                    $row['content'] = '<a href="' . $entry['image_link'] . '" class="flickrd"><img src="' . $entry['thumbnail_src'] . '" alt="' . $entry['title'] . '"></a>';
                    $row['source'] = 'http://flickr.com/photos/' . $row['username'] . '/';
                    break;
                case 'delicious':
                    $row['content'] = '<a href="' . $entry['url'] . '">' . $entry['title'] . '</a>';
                    $row['content'] .= '<p>' . $entry['description'] . '</p>';
                    $row['source'] = 'http://delicious.com/' . $row['username'];
                    break;
                case 'youtube':
                    $row['content'] = '<a href="' . $entry['url'] . '" class="vid"><img src="' . $entry['thumbnail_url'] . '" alt="' . $entry['title'] . '"></a>';
                    $row['source'] = 'http://youtube.com/' . $row['username'];
                    break;
                case 'lastfm':
                    $track = urldecode(substr($entry['url'], strrpos($entry['url'], '/') + 1));
                    $row['content'] = 'Listened to <a href="' . $entry['url'] . '">' . $track . '</a> by ' . $entry['artist'];
                    $row['source'] = 'http://last.fm/user/' . $row['username'];
                    break;
                case 'netflix':
                    $row['content'] = '<a href="' . $entry['url'] . '">' . $entry['title'] . '</a>';
                    break;
                default:
                    continue 2;
                    // $row['content'] .= '<br><pre>' . print_r($entry, 1) . '</pre>';
                    break;
            }

            $output[] = $row;

        }

        return $output;


    }

}
