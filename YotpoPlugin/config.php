<?php

require_once PLUGIN_UPLOAD_REALDIR .  'YotpoPlugin/LC_Page_Plugin_YotpoPluginConfig.php';

$objPage = new LC_Page_Plugin_YotpoPluginConfig();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
