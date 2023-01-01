<?php

function wdpEnqueueStyles()
{
    wp_enqueue_style('mef',WDP_PLUGIN_URL.'assets/css/mef.css');
}

add_action('admin_enqueue_scripts', 'wdpEnqueueStyles');

