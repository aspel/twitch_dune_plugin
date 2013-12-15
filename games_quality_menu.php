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

            $i=0;
            $matches = $database->database;
			foreach ($matches as $game) {
            print_r($game);
                $items[] = array("caption" => $matches[0][$i], "url" => "stream_name:".$matches[0][$i]);
                $i++;
            }   
            
            $this->iconFile = "gui_skin://small_icons/video_file.aai";
            $this->action = PLUGIN_VOD_PLAY_ACTION_ID;
			
			return $this->create_folder_view($items);
		}
	}
?>
