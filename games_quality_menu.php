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
            hd_print ($matches[3][$i][$i]);
                    $items[] = array("caption" => $matches[2][$i], "url" => "stream_name:".$matches[3][$i][$i]);
                    $i++;
            }   
            
            $this->iconFile = "gui_skin://small_icons/video_file.aai";
            $this->action = PLUGIN_VOD_PLAY_ACTION_ID;
			
			return $this->create_folder_view($items);
		}
	}
?>
