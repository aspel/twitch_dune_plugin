<?php

require_once "base_menu.php";
require_once "tw_database.php";

class FavoriteMenu extends BaseMenu {
    
    function __construct($auth) {

        $this->auth = $auth;

    }
    public function generate_menu() {

        $database = new Tw_Search_All_favorite($this->auth);

        $items = array();

        foreach ($database->database as $game) {
            if ($game->channel->url == "setup"){
                $items[] = array("caption" => $game->channel->display_name,  "url" => $game->channel->url);
            }else{
                $items[] = array("caption" => $game->channel->display_name."  -  ".$game->viewers." - ".$game->channel->status, "url" => "streams:".$game->channel->name);
            }
        }

        return $this->create_folder_view($items);
    }
}
?>
