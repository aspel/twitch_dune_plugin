<?php
///////////////////////////////////////////////////////////////////////////

class Properties
{
    private $data;

    public function __construct($props_path)
    {
        $this->data = array();

        $this->read_properties_file($props_path);
    }

    private function read_properties_file($props_path)
    {
        hd_silence_warnings();
        $lines = file($props_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        hd_restore_warnings();

        if ($lines === false)
        {
            hd_print("Properties file '$props_path' does not exist.");
            return false;
        }

        hd_print("Reading properties from '$props_path'...");

        for ($i = 0; $i < count($lines); ++$i)
        {
            if (preg_match('/^#/', $lines[$i]))
                continue;

            if (preg_match('/^([a-zA-Z_][A-Za-z0-9_\-:\.]*) *= *(.*)$/', $lines[$i], $matches) != 1)
            {
                hd_print(
                    "Warning: line " . ($i + 1) . ": unknown format. " .
                    "Data: '" . $lines[$i] . "'.");
                continue;
            }

            $this->data[$matches[1]] = $matches[2];
        }

        return true;
    }

    public function __get($key)
    { return isset($this->data[$key]) ? $this->data[$key] : null; }

    public function __isset($key)
    { return isset($this->data[$key]); }

    public function set_default($key, $value)
    {
        if (!isset($this->data[$key]))
        {
            hd_print("Warning: no value for key '$key'. Using default: '$value'");
            $this->data[$key] = $value;
        }
    }

    public function log_option($key)
    {
        if (isset($this->data[$key]))
            hd_print("$key: '" . $this->data[$key] . "'");
        else
            hd_print("$key is not set.");
    }
}

///////////////////////////////////////////////////////////////////////////
?>
