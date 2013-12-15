<?php

	require_once "base_menu.php";
	require_once "tw_database.php";
	
	class GameSearchMenu extends BaseMenu {
        private $date;

        function __construct($name) {
            $this->name = $name;
        }
		public function generate_menu() {
			$database = new Tw_Search_stream($this->name);
			
			$items = array();

			foreach ($database->database as $game) {
				$items[] = array("caption" => $game->display_name, "url" => "streams:".$game->name);
			}
			return $this->create_folder_view($items);
		}
	}
?>
