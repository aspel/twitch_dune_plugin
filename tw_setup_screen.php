<?php
///////////////////////////////////////////////////////////////////////////

require_once 'lib/abstract_controls_screen.php';

///////////////////////////////////////////////////////////////////////////

class TwSetupScreen extends AbstractControlsScreen
{
    const ID = 'setup';

    ///////////////////////////////////////////////////////////////////////

    public function __construct()
    {
        parent::__construct(self::ID);
    }

    private function do_get_control_defs(&$plugin_cookies)
    {
        $defs = array();

        if (isset($plugin_cookies->show_in_main_screen))
            $show_in_main_screen = $plugin_cookies->show_in_main_screen;
        else
            $show_in_main_screen = 'yes';

        $show_ops = array();
        $show_ops['yes'] = T::key_global('yes_no_choice_yes');
        $show_ops['no'] = T::key_global('yes_no_choice_no');
        $this->add_combobox($defs,
            'show_in_main_screen', T::key_global('setup_show_in_main_screen'),
            $show_in_main_screen, $show_ops, 0, true);

        return $defs;
    }

    public function get_control_defs(MediaURL $media_url, &$plugin_cookies)
    {
        return $this->do_get_control_defs($plugin_cookies);
    }

    public function handle_user_input(&$user_input, &$plugin_cookies)
    {
        hd_print('Setup: handle_user_input:');
        foreach ($user_input as $key => $value)
            hd_print("  $key => $value");

        $need_reset_controls = false;
        if (!isset($user_input->action_type))
        {
            return ActionFactory::open_folder();
        }
        else if ($user_input->action_type === 'confirm')
        {
            $control_id = $user_input->control_id;
            $new_value = $user_input->{$control_id};
            hd_print("Setup: changing $control_id value to $new_value");

            if ($control_id === 'show_in_main_screen')
                $plugin_cookies->show_in_main_screen = $new_value;
            else
                return null;

            $need_reset_controls = true;
        }

        if ($need_reset_controls)
        {
            $defs = $this->do_get_control_defs($plugin_cookies);
            return ActionFactory::reset_controls($defs);
        }

        return null;
    }
}

///////////////////////////////////////////////////////////////////////////
?>
