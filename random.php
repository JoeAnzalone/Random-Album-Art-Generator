<?php

require_once 'vendor/autoload.php';
require_once 'flickr_api.php';
$config = require_once 'config.php';

function random_flickr_photo($api_key)
{
    $flickr = new Flickr($api_key);

    $options = [
        'method' => 'flickr.photos.search',
        'sort' => 'interestingness-desc',
        'min_upload_date' => date('Y-m-d', strtotime('last week')),
        'per_page' => 1,
        'page' => 1,
    ];

    $count_response = $flickr->api_call($options);

    $total_pages = $count_response->photos->pages;

    // Trying to get the page after 4002 seems to not work for some reason
    // https://www.flickr.com/groups/51035612836@N01/discuss/72157666364892360/72157667034054955
    $last_page = min(4000, $total_pages);

    $options['page'] = rand(1, $last_page);

    $response = $flickr->api_call($options);

    $photo = $response->photos->photo[0];

    $photo_url = $flickr->get_photo_url($photo);
    return $photo_url;
}

function random_band_name()
{
    $url = 'https://en.wikipedia.org/wiki/Special:Random';
    $ch = curl_init($url);

    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => true,
    ];

    curl_setopt_array($ch, $options);

    $headers_string = curl_exec($ch);

    preg_match('#^Location:.+/wiki/(.*)#m', $headers_string, $matches);
    $title = trim($matches[1]);

    $title = str_replace('_', ' ', $title);
    $title = urldecode($title);

    return $title;
}

function random_album_title()
{
    $html = file_get_contents('http://www.quotationspage.com/random.php3');
    $crawler = new Symfony\Component\DomCrawler\Crawler($html);
    $quote = $crawler->filter('.quote')->last()->text();
    $quote = trim($quote);

    $words = explode(' ', $quote);
    $last_words = array_splice($words, -5);

    $last_words_string = implode(' ', $last_words);

    $last_words_string = trim($last_words_string, '.');

    return $last_words_string;
}

$random = [
    'photo_url' => random_flickr_photo($config['api_key']),
    'band_name' => random_band_name(),
    'album_title' => random_album_title(),
];

echo json_encode($random);
