<?php

class GamePlay {
        
    private $fileIndex;
                
    function __construct($stream_name) {

        $this->streamName = $stream_name;
        $this->database = array();
        $top_url = "http://api.twitch.tv/api/channels/".$this->streamName."/access_token";
        $auth_data = HD::http_get_document($top_url);
        $tokens = json_decode($auth_data);
        
        $ts = "token=".urlencode($tokens->token)."&sig=".urlencode($tokens->sig);
        $m3u8_url = "http://usher.twitch.tv/api/channel/hls/".$this->streamName.".m3u8?".$ts;
        $hls_data = HD::http_get_document($m3u8_url);
        preg_match('/^http:/', $hls_data, $matches);
        $this->streamURL = $matches[0];
        hd_print ($this->streamURL);

    }
                
        
    public function generatePlayInfo() {
                 
        return array(
                                        
            PluginVodInfo::name           => $this->streamName,
            PluginVodInfo::description    => "",
            PluginVodInfo::series         =>
                array(
                    array(
                        PluginVodSeriesInfo::name                        => $this->streamName,
                        PluginVodSeriesInfo::playback_url                => $this->streamURL,
                        PluginVodSeriesInfo::playback_url_is_stream_url  => true
                        ),
                     ),
                     PluginVodInfo::initial_series_ndx    => 0,
                     PluginVodInfo::initial_position_ms   => 0,
                     PluginVodInfo::advert_mode           => false,
                     PluginVodInfo::ip_address_required   => true,
                     PluginVodInfo::valid_time_required   => false
                     );
    }
}
?>
