#!/bin/bash

VER=`git describe --abbrev=0 --tags`
#git archive --format zip --output /tmp/dune_plugin_twitch_tv.${VER}.zip master 
rm /tmp/dune_plugin_twitch_tv.${VER}.zip
zip -r /tmp/dune_plugin_twitch_tv.${VER}.zip . -x \.git\* compile.sh

curl --upload-file /tmp/dune_plugin_twitch_tv.${VER}.zip ftp://ftp:password@192.168.1.15/usb_storage_part2_2ba9_1371/
