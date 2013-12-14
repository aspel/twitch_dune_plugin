<?php
	require_once "base_menu.php";
	require_once "tw_database.php";
	
	class GamesMenu extends BaseMenu
	{
		public function generate_menu()
		{
			$database = new TwDatabase();
			
			$items = array();
			foreach ($database->database as $game)
			{
				$items[] = array("caption" => $game->name, "url" => "games_search:".$game->name);

			}
			usort($items, array("BaseMenu", "CompareCaption"));
			
			return $this->create_folder_view($items);
		}
	}
?>
