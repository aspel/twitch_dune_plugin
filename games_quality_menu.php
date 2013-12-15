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
            print_r($matches);
			foreach ($matches as $game) {
                hd_print("-->>".$matches[$i]);
                $items[] = array("caption" => $game, "url" => "stream_name:".$game);
                $i++;
            }   
            
            $this->iconFile = "gui_skin://small_icons/video_file.aai";
            $this->action = PLUGIN_VOD_PLAY_ACTION_ID;
			
			return $this->create_folder_view($items);
		}
	}
?>
