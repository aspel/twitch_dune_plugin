<?php

class GamePlay {
        
    private $fileIndex;
                
    function __construct($stream_url,$stream_name,$stream_img,$stream_status) {

        $this->streamURL = $stream_url;
        $this->streamName = $stream_name; 
        $this->streamIMG = $stream_img; 
        $this->streamStatus = $stream_status; 

    }
                
        
    public function generatePlayInfo() {
                 
        return array(
                                        
            PluginVodInfo::name           => $this->streamName,
            PluginVodInfo::description    => "$this->streamStatus",
            PluginVodInfo::poster_url     => $this->streamIMG,
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
