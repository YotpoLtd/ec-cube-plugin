<?

require_once(dirname( __FILE__ ) . '/YotpoSettings.php');
require_once(dirname( __FILE__ ) . '/YotpoMapProcessor.php');

class plg_YotpoPlugin extends SC_Plugin_Base {

 /**
 * Constructor
 */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * Install
     * Executed when plug-in is installed.
     * Information is automatically written to the table dtb_plugin.
     *
     * @param array $arrPlugin plugin_info - from the dtb_plugin
     * @return void
     */
    function install($arrPlugin) {

        if (version_compare(phpversion(), '5.2.0') < 0 || !function_exists('curl_init')) {
            return;
        }

        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $query = "";
        switch (DB_TYPE) {
        case 'pgsql':
            $query = "CREATE TABLE plg_yotpo_settings (id SERIAL PRIMARY KEY, yotpo_key VARCHAR(255) NOT NULL, yotpo_value VARCHAR(255) NOT NULL, create_date TIMESTAMP, update_date TIMESTAMP)";            
            break;

        case 'mysql':
            $query = "CREATE TABLE `plg_yotpo_settings` (id INT NOT NULL AUTO_INCREMENT, yotpo_key VARCHAR(255) NOT NULL, yotpo_value VARCHAR(255) NOT NULL, create_date TIMESTAMP, update_date TIMESTAMP, PRIMARY KEY (id))";
            break;

        default:
        }

        $objQuery->query($query);
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/logo.png", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");
        mkdir(PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code']. "/media");
        SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/media/", PLUGIN_HTML_REALDIR .  $arrPlugin['plugin_code']. "/media/");

        //set default setting
        YotpoSettings::setSetting('product_page_bottomline_enabled', 1);
        YotpoSettings::setSetting('language_code', 'ja');
        YotpoSettings::setSetting('disable_default_reviews_system', 1);
        }

        /**
         * Uninstall
         * 
         * Executed when plug-in is uninstalled.
         * @param array $arrPlugin 
         * @return void
         */
     function uninstall($arrPlugin) {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $objQuery->query("DROP TABLE plg_yotpo_settings");
        unlink(PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");
    }

    /**
     * Enable plug-in
     * 
     * When enabled, the plug-in will start.
     *    
     * @param array $arrPlugin
     * @return void
     */
    function enable($arrPlugin) {

        // nop
    }

    /**
     * Disable  plug-in
     * 
     * When disabled, the plug-in will turn off.
     *    
     * @param array $arrPlugin
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    /**
     * PrefilterTransform hookpoint
     * 
     * Modifies the template
     *
     * @param string &$source Template html source
     * @param LC_Page_Ex $objPage Page object
     * @param string $filename Template filename
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transform
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
        switch ($objPage->arrPageLayout['device_type_id']) {
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC: 
                if (strpos($filename, 'site_frame.tpl')  === 0) {
                    //insert yQuery to site head tag
                    $objTransform->select('head')->appendFirst(file_get_contents($template_dir . 'yotpo_js.tpl'));
                } elseif (strpos($filename, 'products/detail.tpl') != false) {
                    //add widget and bottomline to product pages
                    $objTransform->select('#customervoice_area')->insertBefore(file_get_contents($template_dir . 'yotpo_widget.tpl'));
                    
                    if (YotpoSettings::getSetting('product_page_bottomline_enabled') == 1) {
                        $objTransform->select('.point')->insertAfter(file_get_contents($template_dir . 'yotpo_bottomline.tpl'));    
                    }
                    //remove existing reviews system if exists
                    if (YotpoSettings::getSetting('disable_default_reviews_system')) {
                        $objTransform->select('#customervoice_area')->removeElement();
                    }
                } elseif (strpos($filename, 'shopping/complete.tpl') != false) {
                    //add conversion tracking on checkout complete
                    $objTransform->select('.title')->insertBefore(file_get_contents($template_dir . 'yotpo_conversion_tracking.tpl'));
                }
                break;
            case DEVICE_TYPE_ADMIN:
                break;
            default:
                break;
        }

        $source = $objTransform->getHTML();
    }

     /**
     * LC_Page_Products_Detail_action_after hookpoint
     * 
     * Modifies the template
     * @param LC_Page_Ex $objPage Page object
     * 
     */
    function showWidget($objPage) {

        $product = $objPage->arrProduct;
        $productId = (int)$product['product_id'];
        $url_data = parse_url(HTTP_URL);

        $product_data = array();
        $product_data['app_key'] = YotpoSettings::getSetting('app_key');
        $product_data['domain'] = $url_data['host'];
        $product_data['id'] = $productId;
        $product_data['name'] = $product['name'];
        $product_data['description'] = strip_tags($product['main_comment']);
        $product_data['model'] = $product['product_code_min'];
        $product_data['breadcrumbs'] = '';
        $product_data['url'] = str_replace(ROOT_URLPATH,P_DETAIL_URLPATH,HTTP_URL.$productId);
        $product_data['image_url'] = IMAGE_SAVE_RSS_URL.($product['main_large_image']);
        $product_data['language_code'] = YotpoSettings::getSetting('language_code');

        $objPage->arrForm['yotpoProduct'] = $product_data;

    }

    function mailAfterPurchase($objPage) {

        try {
            
            $objQuery = & SC_Query_Ex::getSingletonInstance();
            $orderId = $_SESSION['order_id'];
            if ($orderId == NULL) {
                $objPage->arrForm['yotpo_display'] = false;
                return;
            }

            $order = YotpoMapProcessor::getOrder($orderId);
            if ($order == NULL) {
                return;
            }

            try {
                //create purchase in yotpo api
                YotpoMapProcessor::exportSingleOrder($order);
            } 
            catch (Exception $e) {
                //failed to push order to yotpo api
            }

            $objPage->arrForm['yotpo_display'] = true;
            $objPage->arrForm['yotpo_app_key'] = YotpoSettings::getSetting('app_key');
            $objPage->arrForm['yotpo_order_id'] = $orderId;
            $objPage->arrForm['yotpo_order_amount'] = $order['total'];
            $objPage->arrForm['yotpo_order_currency'] = 'JPY';
        
        } catch (Exception $e) {

        }
    }
}