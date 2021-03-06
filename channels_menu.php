<?php

require_once "base_menu.php";
require_once "tw_database.php";

class ChannelsMenu extends BaseMenu
{
    public function generate_menu()
    {
        $database = new Tw_Search_All_stream();

        $items = array();

        foreach ($database->database as $game) {
            $items[] = array(
                "caption" => $game->channel->display_name . "  -  " . $game->viewers . " - " . $game->channel->status, "url" => "streams:" . $game->channel->name
            );
        }

        return $this->create_folder_view($items);
    }
}
