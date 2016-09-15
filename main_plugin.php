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

class TwitchPlugin extends DefaultDunePlugin implements UserInputHandler
{
    public function __construct() {}
    public $game_name, $stream_name, $img, $status_stream, $stream_chat = "", $install_dir;

    public function get_folder_view($media_url, &$plugin_cookies)
    {
        if (strpos($media_url, "search_games:") === 0) {
            $menu = new GameSearchMenu(substr($media_url, 13));
        } else if (strpos($media_url, "streams:") === 0) {
            $this->stream_name = substr($media_url, 8);
            $menu = new GameQuality(substr($media_url, 8));
            $jdimg = new Tw_Search_quality($this->stream_name);
            $this->img = $jdimg->getIMG();
            $this->status_stream = $jdimg->getStatus();
            $this->game_name = $jdimg->getGameName();
            $this->install_dir = DuneSystem::$properties["install_dir_path"];
            shell_exec($this->install_dir . "/bin/irc.sh start " . $this->stream_name);
        } else if ($media_url == "games") {
            $menu = new GamesMenu();
        } else if ($media_url == "channels") {
            $menu = new ChannelsMenu();
        } else if ($media_url == "favorite") {
            $menu = new FavoriteMenu($plugin_cookies->auth_token);
        } else if ($media_url == "setup") {
            return $this->get_setup_folder_view($media_url, $plugin_cookies);
        } else {
            $menu = new MainMenu();
        }
        return $menu->generate_menu();
    }
    public function get_handler_id()
    {
        return 'twitch_handler';
    }

    public function get_next_folder_view($media_url, &$plugin_cookies) {}

    public function get_tv_info($media_url, &$plugin_cookies) {}

    public function get_tv_stream_url($media_url, &$plugin_cookies) {}

    //Play selected stream with selected quality
    public function get_vod_info($media_url, &$plugin_cookies)
    {
        $q = array(
            GUI_EVENT_KEY_CLEAR => UserInputHandlerRegistry::create_action($this, 'chat'),
            GUI_EVENT_PLAYBACK_GOING_TO_SWITCH => UserInputHandlerRegistry::create_action($this, 'stop_irc'),
            GUI_EVENT_PLAYBACK_STOP => UserInputHandlerRegistry::create_action($this, 'stop_irc')
        );
        if (strpos($media_url, "stream_name:") === 0) {
            $stream = new GamePlay(substr($media_url, 12), $this->stream_name, $this->img, $this->status_stream, $this->game_name);
            return $stream->generatePlayInfo($q);
        }
    }

    public function get_vod_stream_url($media_url, &$plugin_cookies) {}

    public function get_regular_folder_items($media_url, $from_ndx, &$plugin_cookies) {}

    public function get_day_epg($channel_id, $day_start_tm_sec, &$plugin_cookies) {}

    public function get_tv_playback_url($channel_id, $archive_tm_sec, $protect_code, &$plugin_cookies) {}

    public function change_tv_favorites($op_type, $channel_id, &$plugin_cookies) {}

    public function handle_user_input(&$user_input, &$plugin_cookies)
    {
        hd_print('Setup: handle_user_input:');
        foreach ($user_input as $key => $value) hd_print("  $key => $value");

        if ($user_input->action_type === 'apply') {
            $control_id = $user_input->control_id;
            $auth_value = $user_input->auth_token;
            $name_value = $user_input->username;

            if ($control_id === 'btnSave') {
                $plugin_cookies->auth_token = $auth_value;
                $plugin_cookies->username = $name_value;
            }
        }
        if ($user_input->control_id == "chat") {
            $this->stream_chat = file_get_contents("/tmp/tw_irc.log", true);
            ControlFactory::add_multiline_label($defs, '', $this->stream_chat, 15);
            $attrs['dialog_params'] = array('frame_style' => DIALOG_FRAME_STYLE_GLASS);
            $attrs['actions'] = array(GUI_EVENT_TIMER => ActionFactory::close_dialog_and_run(UserInputHandlerRegistry::create_action($this, 'chat')));
            $attrs['timer'] = array(GuiTimerDef::delay_ms => 2000);
            return ActionFactory::show_dialog("#" . $this->stream_name, $defs, true, 1500, $attrs);
        }
        if ($user_input->control_id == "stop_irc") {
            shell_exec($this->install_dir . "/bin/irc.sh stop");
        }

        return null;
    }

    private function get_setup_folder_view($media_url, &$plugin_cookies)
    {
        $defs = array();

        if (isset($plugin_cookies->auth_token) and isset($plugin_cookies->username)) {
            $auth_token = $plugin_cookies->auth_token;
            $username = $plugin_cookies->username;
        } else {
            $auth_token = '';
            $username = '';
        }
        ControlFactory::add_text_field(
            $defs,
            $this,
            null,
            'auth_token',
            'Token from: http://tw.fex.cc/ ',
            $auth_token,
            0,
            0,
            0,
            true,
            500
        );
        ControlFactory::add_text_field(
            $defs,
            $this,
            null,
            'username',
            'Login from: twitch.tv ',
            $username,
            0,
            0,
            0,
            true,
            500
        );

        ControlFactory::add_button(
            $defs,
            $handler = $this,
            $add_params = array(),
            $name = 'btnSave',
            $title = null,
            $caption = 'Save',
            $width = 400
        );

        return array(
            PluginFolderView::multiple_views_supported  => false,
            PluginFolderView::archive                   => null,
            PluginFolderView::view_kind                 => PLUGIN_FOLDER_VIEW_CONTROLS,
            PluginFolderView::data                      => array(
                PluginControlsFolderView::defs => $defs,
                PluginControlsFolderView::initial_sel_ndx => -1,
            )
        );
    }
}
