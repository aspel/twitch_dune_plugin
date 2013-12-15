<?php
    
    require "main_menu.php";
    require "games_menu.php";
    require "games_search_menu.php";
    require "games_quality_menu.php";
    require "channels_menu.php";
    require "game_play.php";
	
	class TwitchPlugin implements DunePlugin {
		
        public function get_folder_view($media_url, &$plugin_cookies) {

           	if (strpos($media_url, "search_games:") === 0) {
                $menu = new GameSearchMenu(substr($media_url, 13));
            }
           	else if (strpos($media_url, "streams:") === 0) {
                $menu = new GameQuality(substr($media_url, 8));
            }
			else if ($media_url == "games") {
				$menu = new GamesMenu();
			}
			else if ($media_url == "channels") {
				$menu = new ChannelsMenu();
			}
			else {
				$menu = new MainMenu();
			}
			hd_print("-->>$media_url");
			return $menu->generate_menu();
		}

		public function get_next_folder_view($media_url, &$plugin_cookies) {
		}

		public function get_tv_info($media_url, &$plugin_cookies) {
		}

		public function get_tv_stream_url($media_url, &$plugin_cookies) {
		}

		public function get_vod_info($media_url, &$plugin_cookies) {

            if (strpos($media_url, "stream_name:") === 0) {
                $stream = new GamePlay(substr($media_url, 12));
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
		}
	}
?>
