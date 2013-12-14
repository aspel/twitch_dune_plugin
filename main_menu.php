<?php
	require_once "base_menu.php";
	
	class MainMenu extends BaseMenu
	{
		public function generate_menu()
		{
			$menu_items = array(
							array("caption" => "Games", "url" => "games"),
							array("caption" => "Channels", "url" => "channels"),
						);
			
			return $this->create_folder_view($menu_items);
		}
	}
?>
