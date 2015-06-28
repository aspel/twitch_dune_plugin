<?php

require_once 'lib/default_dune_plugin.php';
require_once 'lib/utils.php';
require "main_menu.php";
require "games_menu.php";
require "favorite_menu.php";
require "games_search_menu.php";
require "games_quality_menu.php";
require "channels_menu.php";
require "game_play.php";


class TwitchPlugin extends DefaultDunePlugin implements UserInputHandler{

    public function __construct()
    {
    }
        public $stream_name, $img;
        
        public function get_folder_view($media_url, &$plugin_cookies) {

            if (strpos($media_url, "search_games:") === 0) {
                $menu = new GameSearchMenu(substr($media_url, 13));
            }
            else if (strpos($media_url, "streams:") === 0) {
                $this->stream_name = substr($media_url, 8);
                $menu = new GameQuality(substr($media_url, 8));
                $jdimg = new Tw_Search_quality($this->stream_name);
                $this->img = $jdimg->getIMG();
            }
            else if ($media_url == "games") {
                $menu = new GamesMenu();
            }
            else if ($media_url == "channels") {
                $menu = new ChannelsMenu();
            }
            else if ($media_url == "favorite") {
                $menu = new FavoriteMenu($plugin_cookies->auth_token);
            }
            else if ($media_url == "setup") {
                return $this->get_setup_folder_view($media_url, $plugin_cookies);
            }
            else {
                $menu = new MainMenu();
            }
            return $menu->generate_menu();
        }
        public function get_handler_id()
        {
            return 'twitch_handler';
        }

        public function get_next_folder_view($media_url, &$plugin_cookies) {
        }

        public function get_tv_info($media_url, &$plugin_cookies) {

        }

        public function get_tv_stream_url($media_url, &$plugin_cookies) {
        }

        //Play selected stream with selected quality
        public function get_vod_info($media_url, &$plugin_cookies) {
            if (strpos($media_url, "stream_name:") === 0) {
                $stream = new GamePlay(substr($media_url, 12),$this->stream_name,$this->img);
                return $stream->generatePlayInfo();
            }   
        }

        public function get_vod_stream_url($media_url, &$plugin_cookies) {
        }

        public function get_regular_folder_items($media_url, $from_ndx, &$plugin_cookies) {
        }

        public function get_day_epg($channel_id, $day_start_tm_sec, &$plugin_cookies) {
        }

        public function get_tv_playback_url($channel_id, $archive_tm_sec, $protect_code, &$plugin_cookies) {
        }

        public function change_tv_favorites($op_type, $channel_id, &$plugin_cookies) {
        }

        public function handle_user_input(&$user_input, &$plugin_cookies) {
        hd_print('Setup: handle_user_input:');
        foreach ($user_input as $key => $value)
            hd_print("  $key => $value");

        if ($user_input->action_type === 'apply'){
            $control_id = $user_input->control_id;
            $new_value = $user_input->auth_token;

            hd_print("Setup: changing '$control_id' value to '$new_value'");

            if ($control_id === 'btnSave'){
                $plugin_cookies->auth_token = $new_value;
            }
        }

        return null;
        }

        private function get_setup_folder_view($media_url, &$plugin_cookies)
        {
            $defs = array();

            if (isset($plugin_cookies->auth_token))
                $auth_token = $plugin_cookies->auth_token;
            else
                $auth_token = '';
            
            ControlFactory::add_text_field($defs,
                $this, null,
                'auth_token', 'Token from: http://tw.fex.cc/ ', $auth_token,
                0, 0, 0, true, 500);
            ControlFactory::add_button($defs,
                $handler = $this, $add_params = array(),
                 $name='btnSave', $title=null, $caption='Save', $width=400);
            
            return array
            (
                PluginFolderView::multiple_views_supported  => false,
                PluginFolderView::archive                   => null,
                PluginFolderView::view_kind                 => PLUGIN_FOLDER_VIEW_CONTROLS,
                PluginFolderView::data                      => array
                (
                    PluginControlsFolderView::defs => $defs,
                    PluginControlsFolderView::initial_sel_ndx => -1,
                )
            );
        }
}
?>
