<?php

$config = array(
    'switches' => array(
        'livingroom' => array(
            'code' => '10111',
            'channel' => '1',
        ),
        'kitchen' => array(
            'code' => '10111',
            'channel' => '2',
        ),
        'bedroom' => array(
            'code' => '10111',
            'channel' => '3',
        ),
    ),
    'command' => array(
        'pattern' => 'cd /home/pi/rcswitch-pi/; sudo ./send %s %s %s'
    ),
);
