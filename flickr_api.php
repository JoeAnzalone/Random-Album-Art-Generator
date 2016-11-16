<?php

class Flickr
{
    public $api_key;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    public function api_call($options)
    {
        $url = 'https://api.flickr.com/services/rest/';

        $default_options = [
            'api_key' => $this->api_key,
            'format' => 'json',
            'nojsoncallback' => '1',
        ];

        $options = array_merge($default_options, $options);

        $url = $url . '?' . http_build_query($options);

        $response_text = file_get_contents($url);
        $response = json_decode($response_text);

        return $response;
    }

    public function get_photo_url($photo)
    {
        $id = $photo->id;
        $owner = $photo->owner;
        $secret = $photo->secret;
        $server = $photo->server;
        $farm = $photo->farm;

        $photo_url = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}_c.jpg";

        return $photo_url;
    }
}
