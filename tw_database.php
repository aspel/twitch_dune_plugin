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
?>
