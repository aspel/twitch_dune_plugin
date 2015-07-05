#!/bin/bash


daemon_pid_file=/tmp/twitch_irc.pid
IFS=" = "
while read -r name value
do
if [ $name == 'username' ]
    then 
        SHIC_NICK=${value}
elif [ $name == 'auth_token' ]
    then 
        SHIC_PASS=`echo $value`
        SHIC_PASS="oauth:"$SHIC_PASS
else
    echo "s"
fi
done < /config/twitch_tv_plugin_cookies.properties

if [[ -z $SHIC_NICK ]]; then exit 1; fi
if [[ -z ${SHIC_PASS} ]]; then exit 1; fi

SHIC_CHANNEL="#$2"
SHIC_HOST="irc.twitch.tv"
SHIC_PORT=6667
SHIC_LOG="/tmp/tw_irc.log"

Daemon()
{


trap "kill 0" EXIT

function _send() {
    printf "%s\r\n" "$*" >&3
}

function _output() {
    printf "%s\n" "$(tail -n 7 $SHIC_LOG)" > $SHIC_LOG
    printf "%s\n" "$*" >> $SHIC_LOG
}

exec 3<>/dev/tcp/$SHIC_HOST/$SHIC_PORT || exit 1

{
    while read _line; do
        [[ ${_line:0:1} == ":" ]] && _source="${_line%% *}" && _line="${_line#* }"
        _source="${_source:1}"
        _user=${_source%%\!*}
        _txt="${_line#*:}"

        case "${_line%% *}" in
            "PING")
                _send "PONG" ;;
            "PRIVMSG")
                _ch="${_line%% :*}"
                _ch="${_ch#* }"
                _output "<$_user> $_txt" ;;
            *)
                _output "$_source >< $_line" ;;
        esac
    done
} <&3 &

pid=$!
echo "$pid" >"$daemon_pid_file"
echo "daemon process $pid started"


[[ $SHIC_PASS ]] && _send "PASS $SHIC_PASS"
_send "NICK $SHIC_NICK"
_send "USER $SHIC_NICK localhost $SHIC_HOST :$SHIC_NICK"
_send "JOIN $SHIC_CHANNEL"

while read -e line; do
    _input "$line"
done

}

StartDaemon()
{
    echo "starting daemon process..."
    if [ -f "$daemon_pid_file" ]; then
        pid=`cat "$daemon_pid_file"`
        echo "daemon process is already running with pid $pid"
    else
        Daemon >/dev/null 2>&1 &
    fi
}

StopDaemon()
{
    echo "stopping daemon process..."
    if [ -f "$daemon_pid_file" ]; then
        pid=`cat "$daemon_pid_file"`
        kill "$pid"
        rm -f "$daemon_pid_file"
        echo "daemon process $pid stopped"
    else
        echo "daemon process is not running"
    fi
}

CheckDaemonStatus()
{
    echo "getting daemon process status..."
    if [ -f "$daemon_pid_file" ]; then
        pid=`cat "$daemon_pid_file"`
        echo "daemon process is running with pid $pid"
    else
        echo "daemon process is not running"
    fi
}

###########################################################################

if [ "$#" -lt 1 ]; then
    echo "usage: $0 (start|stop|restart|status)"
    exit 1
fi

case "$1" in
    start)
        StopDaemon
        StartDaemon
        ;;
    stop)
        StopDaemon
        ;;
    restart)
        StopDaemon
        StartDaemon
        ;;
    status)
        CheckDaemonStatus
        ;;
    *)
        echo "unknown command '$1'"
        exit 1
        ;;
esac
