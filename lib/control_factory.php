<?php
///////////////////////////////////////////////////////////////////////////

require_once 'lib/action_factory.php';

class ControlFactory
{
    public static function add_vgap(&$defs, $vgap)
    {
        $defs[] = array(
            GuiControlDef::kind => GUI_CONTROL_VGAP,
            GuiControlDef::specific_def => array(
                GuiVGapDef::vgap => $vgap
            ),
        );
    }

    public static function add_label(
        &$defs,
        $title,
        $text,
        $params = null
    )
    {
        $defs[] = array(
            GuiControlDef::name => '',
            GuiControlDef::title => $title,
            GuiControlDef::kind => GUI_CONTROL_LABEL,
            GuiControlDef::specific_def => array(
                GuiLabelDef::caption => $text
            ),
            GuiControlDef::params => $params,
        );
    }

    public static function add_button(
        &$defs,
        $handler,
        $add_params,
        $name,
        $title,
        $caption,
        $width,
        $centered = false
    )
    {
        $push_action = UserInputHandlerRegistry::create_action(
            $handler,
            $name,
            $add_params
        );
        $push_action['params']['action_type'] = 'apply';

        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => $title,
            GuiControlDef::kind => GUI_CONTROL_BUTTON,
            GuiControlDef::specific_def => array(
                GuiButtonDef::caption => $caption,
                GuiButtonDef::width => $width,
                GuiButtonDef::push_action => $push_action,
            ),
            GuiControlDef::params => array(
                'button_caption_centered' => $centered
            ),
        );
    }

    public static function add_custom_button(
        &$defs,
        $name,
        $title,
        $caption,
        $width,
        $action,
        $centered = true
    )
    {
        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => $title,
            GuiControlDef::kind => GUI_CONTROL_BUTTON,
            GuiControlDef::specific_def => array(
                GuiButtonDef::caption => $caption,
                GuiButtonDef::width => $width,
                GuiButtonDef::push_action => $action,
            ),
            GuiControlDef::params => array(
                'button_caption_centered' => $centered
            ),
        );
    }

    public static function add_close_dialog_button(
        &$defs,
        $caption,
        $width,
        $centered = true
    )
    {
        $defs[] = array(
            GuiControlDef::name => 'close',
            GuiControlDef::title => null,
            GuiControlDef::kind => GUI_CONTROL_BUTTON,
            GuiControlDef::specific_def => array(
                GuiButtonDef::caption => $caption,
                GuiButtonDef::width => $width,
                GuiButtonDef::push_action => ActionFactory::close_dialog(),
            ),
            GuiControlDef::params => array(
                'button_caption_centered' => $centered
            ),
        );
    }

    public static function add_close_dialog_and_apply_button(
        &$defs,
        $handler,
        $add_params,
        $name,
        $caption,
        $width,
        $centered = false
    )
    {
        $push_action = UserInputHandlerRegistry::create_action(
            $handler,
            $name,
            $add_params
        );
        $push_action['params']['action_type'] = 'apply';

        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => null,
            GuiControlDef::kind => GUI_CONTROL_BUTTON,
            GuiControlDef::specific_def => array(
                GuiButtonDef::caption => $caption,
                GuiButtonDef::width => $width,
                GuiButtonDef::push_action => ActionFactory::close_dialog_and_run($push_action),
            ),
            GuiControlDef::params => array(
                'button_caption_centered' => $centered
            ),
        );
    }

    public static function add_custom_close_dialog_and_apply_button(
        &$defs,
        $name,
        $caption,
        $width,
        $action,
        $centered = false
    )
    {
        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => null,
            GuiControlDef::kind => GUI_CONTROL_BUTTON,
            GuiControlDef::specific_def => array(
                GuiButtonDef::caption => $caption,
                GuiButtonDef::width => $width,
                GuiButtonDef::push_action => ActionFactory::close_dialog_and_run($action),
            ),
            GuiControlDef::params => array(
                'button_caption_centered' => $centered
            ),
        );
    }

    public static function add_text_field(
        &$defs,
        $handler,
        $add_params,
        $name,
        $title,
        $initial_value,
        $numeric,
        $password,
        $has_osk,
        $always_active,
        $width,
        $need_confirm = false,
        $need_apply = false,
        $control_params = null
    )
    {
        $apply_action = null;
        if ($need_apply) {
            $apply_action = UserInputHandlerRegistry::create_action(
                $handler,
                $name,
                $add_params
            );
            $apply_action['params']['action_type'] = 'apply';
        }

        $confirm_action = null;
        if ($need_confirm) {
            $confirm_action = UserInputHandlerRegistry::create_action(
                $handler,
                $name,
                $add_params
            );
            $confirm_action['params']['action_type'] = 'confirm';
        }

        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => $title,
            GuiControlDef::kind => GUI_CONTROL_TEXT_FIELD,
            GuiControlDef::specific_def => array(
                GuiTextFieldDef::initial_value => strval($initial_value),
                GuiTextFieldDef::numeric => intval($numeric),
                GuiTextFieldDef::password => intval($password),
                GuiTextFieldDef::has_osk => intval($has_osk),
                GuiTextFieldDef::always_active => intval($always_active),
                GuiTextFieldDef::width => intval($width),
                GuiTextFieldDef::apply_action => $apply_action,
                GuiTextFieldDef::confirm_action => $confirm_action,
            ),
            GuiControlDef::params => $control_params,
        );
    }

    public static function add_combobox(
        &$defs,
        $handler,
        $add_params,
        $name,
        $title,
        $initial_value,
        $value_caption_pairs,
        $width,
        $need_confirm = false,
        $need_apply = false
    )
    {
        $apply_action = null;
        if ($need_apply) {
            $apply_action = UserInputHandlerRegistry::create_action(
                $handler,
                $name,
                $add_params
            );
            $apply_action['params']['action_type'] = 'apply';
        }

        $confirm_action = null;
        if ($need_confirm) {
            $confirm_action = UserInputHandlerRegistry::create_action(
                $handler,
                $name,
                $add_params
            );
            $confirm_action['params']['action_type'] = 'confirm';
        }

        $defs[] = array(
            GuiControlDef::name => $name,
            GuiControlDef::title => $title,
            GuiControlDef::kind => GUI_CONTROL_COMBOBOX,
            GuiControlDef::specific_def => array(
                GuiComboboxDef::initial_value => $initial_value,
                GuiComboboxDef::value_caption_pairs => $value_caption_pairs,
                GuiComboboxDef::width => $width,
                GuiComboboxDef::apply_action => $apply_action,
                GuiComboboxDef::confirm_action => $confirm_action,
            ),
        );
    }
}

///////////////////////////////////////////////////////////////////////////
?>
