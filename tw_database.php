<?php

require_once "lib/utils.php";

class TwDatabase
{
    public $database;

    function __construct()
    {
        $this->loadGames();
    }

    private function loadGames()
    {
        $this->database = array();
        $this->addGames("https://api.twitch.tv/kraken/games/top?limit=100");
    }

    private function addGames($url)
    {
        $data = HD::http_get_document($url);
        $games = json_decode($data);
        foreach ($games->top as $game) {
            $this->database[] = $game->game;
        }
    }
}

class Tw_Search_All_favorite
{
    public $database, $data;

    function __construct($name)
    {
        $this->name = $name;
        $this->streamName = $stream_name;
        $this->loadGames();
        hd_print('Token:' . $name);
    }

    private function loadGames()
    {
        $this->database = array();
        $game_name = urlencode($this->name);
        $auth = $this->name;
        $this->addGames("https://api.twitch.tv/kraken/streams/followed?oauth_token=" . $auth);
    }

    private function addGames($url)
    {
        try {
            $data = HD::http_get_document($url);
        } catch (Exception $e) {
            hd_print($e->getMessage());
            $data = '{ "streams": [ { "channel":{ "display_name":"No Token, please enter your token in settings->twitch.tv", "url":"setup" } } ] }';
        }
        $games = json_decode($data);
        hd_print($games);
        foreach ($games->streams as $game) {
            $this->database[] = $game;
        }
    }
}

class Tw_Search_All_stream
{
    public $database;

    function __construct($name)
    {
        $this->name = $name;
        $this->streamName = $stream_name;
        $this->loadGames();
    }

    private function loadGames()
    {
        $this->database = array();
        $game_name = urlencode($this->name);
        $this->addGames("https://api.twitch.tv/kraken/streams?limit=100");
    }

    private function addGames($url)
    {
        $data = HD::http_get_document($url);
        $games = json_decode($data);
        foreach ($games->streams as $game) {
            $this->database[] = $game;
        }
    }
}

class Tw_Search_stream
{
    public $database;

    function __construct($name)
    {
        $this->name = $name;
        $this->streamName = $stream_name;
        $this->loadGames();
    }

    private function loadGames()
    {
        $this->database = array();
        $game_name = urlencode($this->name);
        $this->addGames("https://api.twitch.tv/kraken/streams?game=$game_name&limit=100");
    }

    private function addGames($url)
    {
        $data = HD::http_get_document($url);
        $games = json_decode($data);
        foreach ($games->streams as $game) {
            $this->database[] = $game;
        }
    }
}

class Tw_Search_quality
{
    public $database, $img;

    function __construct($name)
    {
        $this->name = $name;
        $this->loadQuality();
    }

    public function getIMG()
    {
        $top_url = "https://api.twitch.tv/kraken/users/" . $this->name;
        $data = HD::http_get_document($top_url);
        $jd = json_decode($data);
        return $jd->logo;
    }
    public function getStatus()
    {
        $top_url = "https://api.twitch.tv/kraken/streams/" . $this->name;
        $data = HD::http_get_document($top_url);
        $jd = json_decode($data);
        $descript = $jd->stream->channel->status . "\nViewers: " . $jd->stream->viewers;
        return $descript;
    }

    private function loadQuality()
    {
        $top_url = "http://api.twitch.tv/api/channels/" . $this->name . "/access_token";
        $auth_data = HD::http_get_document($top_url);
        $tokens = json_decode($auth_data);

        $ts = "token=" . urlencode($tokens->token) . "&sig=" . urlencode($tokens->sig);
        $m3u8_url = "http://usher.twitch.tv/api/channel/hls/" . $this->name . ".m3u8?" . $ts . "&allow_source=true";
        $hls_data = HD::http_get_document($m3u8_url);
        preg_match_all('|BANDWIDTH=(\d+),RESOLUTION=(.+),VIDEO=\"(\w+)\"|', $hls_data, $match_video);
        preg_match_all('|http:(.*)|', $hls_data, $match_url);
        array_push($match_video, $match_url[0]);
        $this->database = $match_video;
    }
}

