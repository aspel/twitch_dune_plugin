<?php

require_once "lib/utils.php";
require_once "main_plugin.php";

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

class Tw_IRC
{
    public $database, $fp;

    function __construct($name)
    {
        $this->streamName = $name;
        $this->loadIRC();
    }
    function loadIRC()
    {
        $server = "irc.twitch.tv";
        $port = 6667;
        $nick = "aspellip_"; // Enter your nick here.
        $password = "oauth:3ei4lj7pqxn6ayndyecio82z0v61pb"; // We might want to get this through twitch API asking the user for username and password.
        $channels = array("#" . $this->streamName);
        /* End of configuration */
        $fp = fsockopen($server, $port, $errno, $errstr);
        if (!$fp) {
            hd_print("Error: $errno - $errstr\n");
            exit;
        }
        fwrite($fp, "PASS " . $password . "\r\n");
        fwrite($fp, "NICK " . $nick . "\r\n");
        while (!preg_match("/:\S+ 376 \S+ :.*/i", $read)) {
            $read = fgets($fp);
        }
        foreach ($channels as $num => $chan) {
            fwrite($fp, "JOIN $chan\r\n");
        }
        hd_print("Connected!\n");
        while (TRUE) {
            $read = fgets($fp);
            if (preg_match("/:(\S+)!\S+@\S+ JOIN (#\S+)/i", $read, $match)) {
                $this->user_joined($match[1], $match[2]);
            }
            if (preg_match("/:(\S+)!\S+@\S+ PART (#\S+)/i", $read, $match)) {
                $this->user_parted($match[1], $match[2]);
            }
            if (preg_match("/:(\S+)!\S+@\S+ PRIVMSG (#\S+) :(.*)/i", $read, $match)) {
                $this->inc_message($match[1], $match[2], $match[3]);
            }
            if (preg_match("/:jtv!jtv@\S+ PRIVMSG $nick :(\S+)/i", $read, $match)) {
                $this->jtv_error($match[1]);
            }
            if (preg_match("/PING :.*/i", $read, $match)) {
                fwrite($fp, "PONG :$match[1]\r\n");
            }
            hd_print($read);
        }
    }
    function user_joined($nick, $chan)
    {
        global $users;
        $users[$chan][] = $nick;
        hd_print("$nick joined {$chan}.\n");
    }
    function user_parted($nick, $chan)
    {
        global $users;
        $num = array_search($nick, $users[$chan]);
        if ($num !== FALSE) {
            unset($users[$chan][$num]);
        }
        hd_print("$nick parted {$chan}.\n");
    }
    function inc_message($nick, $chan, $msg)
    {
        global $fp, $users;
        $Tclass = new TwitchPlugin();
        $Tclass->stream_chat = "$chan : <$nick> $msg\n";
        hd_print("$chan : <$nick> $msg\n");
        if (preg_match("/.*!usercount.*/mi", $msg)) {
            echo "!usercount triggered.\n";
            echo "$fp\n";
            echo "$chan\n";
            fwrite($fp, "PRIVMSG $chan :There are " . count($users[$chan]) . " users in this chatroom.\r\n");
        }
    }
    function jtv_error($msg)
    {
        hd_print("Message from jtv: $msg\n");
    }
}
