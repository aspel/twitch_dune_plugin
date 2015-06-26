<?php
	abstract class BaseMenu
	{
		protected $iconFile = "gui_skin://small_icons/folder.aai";
		protected $action = PLUGIN_OPEN_FOLDER_ACTION_ID;
		
		protected static function CompareCaption($a, $b)
		{
			$al = strtolower($a["caption"]);
			$bl = strtolower($b["caption"]);
			if ($al == $bl)
			{
				return 0;
			}
			else
			{
				return ($al > $bl) ? +1 : -1;
			}
		}
		
		protected function create_folder_view($items)
		{
			$folderItems = array();
			foreach ($items as $item)
			{
				$folderItems[] = array(
									PluginRegularFolderItem::media_url			=> $item["url"],
									PluginRegularFolderItem::caption			=> $item["caption"],
									PluginRegularFolderItem::view_item_params	=> array()
								);
			}

			return array(
					PluginFolderView::view_kind								=>	PLUGIN_FOLDER_VIEW_REGULAR,
					PluginFolderView::data									=>
						array(
							PluginRegularFolderView::initial_range					=>
								array(
									PluginRegularFolderRange::items							=>	$folderItems,
									PluginRegularFolderRange::total							=>	count($folderItems),
									PluginRegularFolderRange::count							=>	count($folderItems),
									PluginRegularFolderRange::more_items_available			=>	false,
									PluginRegularFolderRange::from_ndx						=>	0
								),
							PluginRegularFolderView::view_params					=>
								array(
									ViewParams::num_cols									=>	1,
									ViewParams::num_rows									=>	11
								),
							PluginRegularFolderView::base_view_item_params			=>
								array(
									ViewItemParams::icon_path								=>	$this->iconFile,
									ViewItemParams::item_layout								=>	HALIGN_LEFT,
									ViewItemParams::icon_valign								=>	VALIGN_TOP,
									ViewItemParams::item_caption_dx							=>	60,
									ViewItemParams::icon_dx									=>	10
									),
							PluginRegularFolderView::not_loaded_view_item_params	=>	array(),
							PluginRegularFolderView::actions						=>
								array(
									GUI_EVENT_KEY_ENTER										=>
										array(
											GuiAction::handler_string_id							=>	$this->action
										)
								)
						)
					);
		}
		
		abstract public function generate_menu();
	}
?>