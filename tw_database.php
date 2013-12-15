<?php

require_once "lib/utils.php";
	
class TwDatabase {

    public $database;
	function __construct() {
	    $this->loadGames();
	}

	private function loadGames() {
    	$this->database = array();
    	$this->addGames("https://api.twitch.tv/kraken/games/top");
	}

	private function addGames($url) {
		$data = HD::http_get_document($url);
		$games = json_decode($data);
        foreach($games->top as $game) {
            $this->database[] = $game->game;
        }
    }
}

class Tw_Search_stream {

    public $database;

	function __construct($name) {
		$this->name = $name;
        $this->streamName = $stream_name;
        $this->loadGames();
	}

	private function loadGames() {
		$this->database = array();
        $game_name = urlencode($this->name);
    	$this->addGames("https://api.twitch.tv/kraken/search/streams?q=$game_name");
    }

	private function addGames($url) {
		$data = HD::http_get_document($url);
		$games = json_decode($data);
        foreach($games->streams as $game) {
            $this->database[] = $game->channel;
        }
    }
    
   
}
class Tw_Search_quality {

    public $database;

	function __construct($name) {
		$this->name = $name;
        $this->loadQuality();
	}

	private function loadQuality() {
        $top_url = "http://api.twitch.tv/api/channels/".$this->name."/access_token";
        $auth_data = HD::http_get_document($top_url);
        $tokens = json_decode($auth_data);

        $ts = "token=".urlencode($tokens->token)."&sig=".urlencode($tokens->sig);
        $m3u8_url = "http://usher.twitch.tv/api/channel/hls/".$this->name.".m3u8?".$ts;
        $hls_data = HD::http_get_document($m3u8_url);
        preg_match_all('|BANDWIDTH=(\d+).*VIDEO=\"(\w+)\"|', $hls_data, $match_video);
        preg_match_all('|http:(.*)|', $hls_data, $match_url);
        array_push($match_video,$match_url[0]);
        $this->database = $match_video;
    }
}
    
?>
