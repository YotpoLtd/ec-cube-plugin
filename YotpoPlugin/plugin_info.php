<?php

class plugin_info{
    static $PLUGIN_CODE       = "YotpoPlugin";
    static $PLUGIN_NAME       = "YotpoPlugin";
    static $PLUGIN_VERSION    = "1.1";
    static $COMPLIANT_VERSION = "2.12.0,2.12.1,2.12.2,2.12.3,2.12.4,2.12.5";
    static $AUTHOR            = "Yotpo";
    static $DESCRIPTION       = "Yotpo Social Reviews plug-in";
    static $PLUGIN_SITE_URL   = "http://www.yotpo.com";
    static $AUTHOR_SITE_URL   = "http://www.yotpo.com";
    static $CLASS_NAME        = "plg_YotpoPlugin";  
    static $LICENSE           = "LGPL";

   //hook points***
    static $HOOK_POINTS       = array(
                    array("prefilterTransform", 'prefilterTransform'),
                    array("LC_Page_Products_Detail_action_after", 'showWidget'),
                    array("LC_Page_Shopping_Complete_action_before", 'mailAfterPurchase')
                    );
}

