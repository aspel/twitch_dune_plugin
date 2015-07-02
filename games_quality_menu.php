<?php
require_once "base_menu.php";
require_once "tw_database.php";

class GameQuality extends BaseMenu {
    private $date;

    function __construct($name) {
        $this->name = $name;
    }
public function generate_menu() {
    $database = new Tw_Search_quality($this->name);

    $items = array();

    $matches = $database->database;
    $i = 0;

    foreach ($matches as $game) {
        if ($matches[2][$i]) {
            $kbps = (int)($matches[1][$i])/8.0/1000;
            $items[] = array("caption" => $matches[3][$i]." - ".$matches[2][$i]." - ".round($kbps,1)."kBps", "url" => "stream_name:".$matches[4][$i]);
            $i++;
        }       
    }
            
    $this->iconFile = "gui_skin://small_icons/video_file.aai";
    $this->action = PLUGIN_VOD_PLAY_ACTION_ID;
    return $this->create_folder_view($items);
    }
}
?>
