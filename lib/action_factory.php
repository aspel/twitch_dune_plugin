<?php
///////////////////////////////////////////////////////////////////////////

class ActionFactory
{
    public static function open_folder($media_url = null, $caption = null)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_OPEN_FOLDER_ACTION_ID,
            GuiAction::data => array(
                PluginOpenFolderActionData::media_url => $media_url,
                PluginOpenFolderActionData::caption => $caption,
            ),
        );
    }

    public static function tv_play()
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_TV_PLAY_ACTION_ID,
        );
    }

    public static function vod_play($vod_info = null)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_VOD_PLAY_ACTION_ID,
            GuiAction::data => array(
                PluginVodPlayActionData::vod_info => $vod_info,
            ),
        );
    }

    public static function show_error($fatal, $title, $msg_lines = null)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_SHOW_ERROR_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => array(
                PluginShowErrorActionData::fatal => $fatal,
                PluginShowErrorActionData::title => $title,
                PluginShowErrorActionData::msg_lines => $msg_lines,
            ),
            GuiAction::params => null,
        );
    }

    public static function show_dialog(
        $title,
        $defs,
        $close_by_return = true,
        $preferred_width = 0,
        $attrs = array()
    )
    {
        $initial_sel_ndx = isset($attrs['initial_sel_ndx']) ? $attrs['initial_sel_ndx'] : -1;
        $actions = isset($attrs['actions']) ? $attrs['actions'] : null;
        $timer = isset($attrs['timer']) ? $attrs['timer'] : null;
        $min_item_title_width = isset($attrs['min_item_title_width']) ? $attrs['min_item_title_width'] : 0;

        $dialog_params = isset($attrs['dialog_params']) ? $attrs['dialog_params'] : array();

        return array(
            GuiAction::handler_string_id => SHOW_DIALOG_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => array(
                ShowDialogActionData::title => $title,
                ShowDialogActionData::defs => $defs,
                ShowDialogActionData::close_by_return => $close_by_return,
                ShowDialogActionData::preferred_width => $preferred_width,
                ShowDialogActionData::min_item_title_width => $min_item_title_width,
                ShowDialogActionData::initial_sel_ndx => $initial_sel_ndx,
                ShowDialogActionData::actions => $actions,
                ShowDialogActionData::timer => $timer,
                ShowDialogActionData::params => $dialog_params,
            ),
            GuiAction::params => null,
        );
    }

    public static function close_dialog_and_run($post_action)
    {
        return array(
            GuiAction::handler_string_id => CLOSE_DIALOG_AND_RUN_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => array(
                CloseDialogAndRunActionData::post_action => $post_action,
            ),
            GuiAction::params => null,
        );
    }

    public static function close_dialog()
    {
        return self::close_dialog_and_run(null);
    }

    public static function close_and_run($post_action = null)
    {
        return array(
            GuiAction::handler_string_id => CLOSE_AND_RUN_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => array(
                CloseAndRunActionData::post_action => $post_action,
            ),
            GuiAction::params => null,
        );
    }

    public static function show_title_dialog($title, $post_action = null, $attrs)
    {
        $defs = array();

//        ControlFactory::add_vgap($defs, 50);

        ControlFactory::add_custom_close_dialog_and_apply_buffon(
            $defs,
            'apply_subscription',
            'OK',
            300,
            $post_action
        );

        return self::show_dialog($title, $defs, false, 0, $attrs);
    }

    public static function status($status)
    {
        return array(
            GuiAction::handler_string_id => STATUS_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => array(
                StatusActionData::status => $status,
            ),
            GuiAction::params => null,
        );
    }

    public static function invalidate_folders(
        $media_urls,
        $post_action = null
    )
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_INVALIDATE_FOLDERS_ACTION_ID,
            GuiAction::data => array(
                PluginInvalidateFoldersActionData::media_urls => $media_urls,
                PluginInvalidateFoldersActionData::post_action => $post_action,
            ),
        );
    }

    public static function show_popup_menu($menu_items, $sel_ndx = 0)
    {
        return array(
            GuiAction::handler_string_id => SHOW_POPUP_MENU_ACTION_ID,
            GuiAction::data => array(
                ShowPopupMenuActionData::menu_items => $menu_items,
                ShowPopupMenuActionData::selected_menu_item_index => $sel_ndx,
            ),
        );
    }

    public static function update_regular_folder(
        $range,
        $need_refresh = false,
        $sel_ndx = -1
    )
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_UPDATE_FOLDER_ACTION_ID,
            GuiAction::data => array(
                PluginUpdateFolderActionData::range => $range,
                PluginUpdateFolderActionData::need_refresh => $need_refresh,
                PluginUpdateFolderActionData::sel_ndx => intval($sel_ndx),
            ),
        );
    }

    public static function reset_controls($defs, $post_action = null, $initial_sel_ndx = -1)
    {
        return array(
            GuiAction::handler_string_id => RESET_CONTROLS_ACTION_ID,
            GuiAction::data => array(
                ResetControlsActionData::defs => $defs,
                ResetControlsActionData::initial_sel_ndx => $initial_sel_ndx,
                ResetControlsActionData::post_action => $post_action,
            ),
        );
    }

    public static function clear_archive_cache($archive_id = null, $post_action = null)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_CLEAR_ARCHIVE_CACHE_ACTION_ID,
            GuiAction::data => array(
                PluginClearArchiveCacheActionData::archive_id => $archive_id,
                PluginClearArchiveCacheActionData::post_action => $post_action,
            ),
        );
    }

    public static function launch_media_url($url, $post_action = null)
    {
        return array(
            GuiAction::handler_string_id => LAUNCH_MEDIA_URL_ACTION_ID,
            GuiAction::data => array(
                LaunchMediaUrlActionData::url => $url,
                LaunchMediaUrlActionData::post_action => $post_action,
            ),
        );
    }

    public static function plugin_system($exec, $post_action = null)
    {
        return array(
            GuiAction::handler_string_id => LAUNCH_MEDIA_URL_ACTION_ID,
            GuiAction::data => array(
                PluginSystemActionData::run_string => $exec,
                PluginSystemActionData::post_action => $post_action,
            ),
        );
    }

    public static function show_main_screen($post_action = null)
    {
        return array(
            GuiAction::handler_string_id => SHOW_MAIN_SCREEN_ACTION_ID,
            GuiAction::data => array(
                ShowMainScreenActionData::post_action => $post_action,
            ),
        );
    }

    public static function handle_user_input($params)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_HANDLE_USER_INPUT_ACTION_ID,
            GuiAction::caption => null,
            GuiAction::data => null,
            GuiAction::params => $params,
        );
    }

    public static function change_behaviour($actions, $timer = null, $post_action = null)
    {
        return array(
            GuiAction::handler_string_id => CHANGE_BEHAVIOUR_ACTION_ID,
            GuiAction::data => array(
                ChangeBehaviourActionData::actions => $actions,
                ChangeBehaviourActionData::timer => $timer,
                ChangeBehaviourActionData::post_action => $post_action,
            ),
        );
    }

    public static function update_info_block(
        $text_above,
        $text_color = null,
        $text_halo = false,
        $text_y_offset = 0,
        $post_action = null
    )
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_UPDATE_INFO_BLOCK_ACTION_ID,
            GuiAction::data => array(
                PluginUpdateInfoBlockActionData::text_above => $text_above,
                PluginUpdateInfoBlockActionData::text_color => $text_color,
                PluginUpdateInfoBlockActionData::text_halo => $text_halo,
                PluginUpdateInfoBlockActionData::text_y_offset => $text_y_offset,
                PluginUpdateInfoBlockActionData::post_action => $post_action,
            ),
        );
    }

    public static function update_epg(
        $channel_id,
        $clear,
        $day_start_tm_sec = 0,
        $programs = null,
        $post_action = null
    )
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_UPDATE_EPG_ACTION_ID,
            GuiAction::data => array(
                PluginUpdateEpgActionData::channel_id => $channel_id,
                PluginUpdateEpgActionData::clear => $clear,
                PluginUpdateEpgActionData::day_start_tm_sec => $day_start_tm_sec,
                PluginUpdateEpgActionData::programs => $programs,
                PluginUpdateEpgActionData::post_action => $post_action,
            ),
        );
    }

    public static function add_osd_image(
        &$comps,
        $image_url,
        $x,
        $y,
        $image_width = 0,
        $image_height = 0
    )
    {
        $comps[] = array(
            PluginOsdComponent::image_url => $image_url,
            PluginOsdComponent::x => $x,
            PluginOsdComponent::y => $y,
            PluginOsdComponent::image_width => $image_width,
            PluginOsdComponent::image_height => $image_height
        );
    }

    public static function add_osd_text(
        &$comps,
        $text,
        $x,
        $y,
        $text_font_size = PLUGIN_FONT_NORMAL,
        $text_color = "15",
        $text_halo = false
    )
    {
        $comps[] = array(
            PluginOsdComponent::text => $text,
            PluginOsdComponent::x => $x,
            PluginOsdComponent::y => $y,
            PluginOsdComponent::text_font_size => $text_font_size,
            PluginOsdComponent::text_color => $text_color,
            PluginOsdComponent::text_halo => $text_halo
        );
    }

    public static function update_osd($comps, $post_action = null)
    {
        return array(
            GuiAction::handler_string_id => PLUGIN_UPDATE_OSD_ACTION_ID,
            GuiAction::data => array(
                PluginUpdateOsdActionData::components => $comps,
                PluginUpdateOsdActionData::post_action => $post_action,
            ),
        );
    }

    public static function change_settings(
        $settings,
        $reboot,
        $restart_gui,
        $post_action = null
    )
    {
        return array(
            GuiAction::handler_string_id => CHANGE_SETTINGS_ACTION_ID,
            GuiAction::data => array(
                ChangeSettingsActionData::settings => $settings,
                ChangeSettingsActionData::reboot => $reboot,
                ChangeSettingsActionData::restart_gui => $restart_gui,
                ChangeSettingsActionData::post_action => $post_action,
            ),
        );
    }

    public static function timer($delay_ms)
    {
        return array(GuiTimerDef::delay_ms => $delay_ms);
    }
}

///////////////////////////////////////////////////////////////////////////
?>
