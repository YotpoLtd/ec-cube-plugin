<?php

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
require_once(dirname( __FILE__ ) . '/YotpoSettings.php');
require_once(dirname( __FILE__ ) . '/YotpoRegister.php');
require_once(dirname( __FILE__ ) . '/YotpoMapProcessor.php');

class LC_Page_Plugin_YotpoPluginConfig extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * Initialize.
     *
     * @return void
     */
    function init() {
        parent::init();
        //decide wether to send him to registration form or setting form
        $app_key = YotpoSettings::getSetting('app_key');

        $arrForm = array();
        $arrForm['yotpo_css'] = "<link rel=\"stylesheet\" href=\"". PLUGIN_HTML_URLPATH .  "YotpoPlugin/media/yotpo.css\" type=\"text/css\" media=\"screen\" />";
        $arrForm['yotpo_logo'] = "<img src=\"". PLUGIN_HTML_URLPATH .  "YotpoPlugin/logo.png\"/></img>";

        if ($app_key == NULL && ($this->getMode() != 'export_orders')) {
            $this->setRegisterTemplate();  
        } else {
            $this->setConfigTemplate();  

            //prepare data
            $arrForm['app_key'] = $app_key;
            $arrForm['secret'] = YotpoSettings::getSetting('secret');
            $arrForm['language_code'] = YotpoSettings::getSetting('language_code');
            $arrForm['product_page_bottomline_enabled'] = YotpoSettings::getSetting('product_page_bottomline_enabled');
            $arrForm['disable_default_reviews_system'] = YotpoSettings::getSetting('disable_default_reviews_system');
        }
        $this->arrForm = $arrForm;
    }

    /**
     * Process.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page action
     *
     * @return void
     */
    function action() {
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $arrForm = array();
        switch ($this->getMode()) {
            case 'yotpo_register':
                $this->arrErr = $this->checkRegisterErrors($objFormParam);
                $formData = $objFormParam->getHashArray();
                //If there are no errors, update the table.
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    try {
                        YotpoRegister::register($formData); 
                        $this->arrForm['new_registration'] = true; 
                        $this->setConfigTemplate();  
                    } catch (Exception $e) {
                        $this->arrForm['register_error'] = true;
                        $this->arrForm['register_error_msg'] = $e->getMessage();    
                    }
                }
                $this->arrForm['app_key'] = YotpoSettings::getSetting('app_key');
                $this->arrForm['secret'] = YotpoSettings::getSetting('secret');
                $this->arrForm = array_merge($this->arrForm,$formData);
                break;
            case 'yotpo_login':
                $this->setConfigTemplate();
                $this->arrForm['already_logged_in'] = true;
                $this->arrForm['language_code'] = YotpoSettings::getSetting('language_code');
                $this->arrForm['product_page_bottomline_enabled'] = YotpoSettings::getSetting('product_page_bottomline_enabled');
                $this->arrForm['disable_default_reviews_system'] = YotpoSettings::getSetting('disable_default_reviews_system');
                break;
            case 'yotpo_settings':
                $formData = $objFormParam->getHashArray();
                YotpoSettings::setSetting('app_key', $formData['app_key']);
                YotpoSettings::setSetting('secret', $formData['secret']);
                YotpoSettings::setSetting('product_page_bottomline_enabled', $formData['product_page_bottomline_enabled'] == '1' ? 1 : 0);
                YotpoSettings::setSetting('language_code', $formData['language_code']);
                YotpoSettings::setSetting('disable_default_reviews_system', $formData['disable_default_reviews_system'] == '1' ? 1 : 0);
                $this->arrForm['settings_update_success'] = true;
                $this->arrForm['new_registration'] = false;
                if ($formData['app_key'] != NULL) {
                    $this->arrForm['already_logged_in'] = false;
                }
                $this->arrForm = array_merge($this->arrForm, $formData);
                $this->setConfigTemplate();
                break;
            case 'export_orders':
                try {
                    YotpoMapProcessor::exportOrders();
                    $this->arrForm['export_success'] = true;
                } catch(Exception $e) {
                    $this->arrForm['export_error'] = true;
                    $this->arrForm['export_error_msg'] = $e->getMessage();
                }
                
            default:
                break;
        }
  
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * Destructor.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    /**
     * Paramater info initialization 
     *
     * @param object $objFormParam SC_FormParam instance
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('Name', 'name', STEXT_LEN, ``, array('MAX_LENGTH_CHECK', 'EXIST_CHECK'));
        $objFormParam->addParam('Email', 'email', STEXT_LEN, ``, array('EMAIL_CHECK', 'EXIST_CHECK'));
        $objFormParam->addParam('Password', 'password', 6, ``, array('EXIST_CHECK', 'MIN_LENGTH_CHECK'));
        $objFormParam->addParam('Password Confirmation', 'password_confirmation', 6, ``, array('EXIST_CHECK', 'MIN_LENGTH_CHECK'));
        $objFormParam->addParam('App Key', 'app_key', STEXT_LEN, ``, array('EXIST_CHECK'));
        $objFormParam->addParam('Secret', 'secret', STEXT_LEN, ``, array('EXIST_CHECK'));
        $objFormParam->addParam('Product Page Bottomline Enabled', 'product_page_bottomline_enabled', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('Language Code', 'language_code', STEXT_LEN, ``, array());
        $objFormParam->addParam('Disable Default Reviews System', 'disable_default_reviews_system', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
    }

    /**
     * Check for errors of inputed data
     * @param Object $objFormParam
     * @return Array error 
     */
    function checkRegisterErrors(&$objFormParam) {
        $objFormParam-> removeParam('app_key');
        $objFormParam-> removeParam('secret');
        $objFormParam-> removeParam('product_page_bottomline_enabled');
        $objErr = new SC_CheckError_Ex();
        $objErr->arrErr = $objFormParam->checkError();
        
        $arrForm = $objFormParam->getHashArray();
        if ($arrForm['password'] !=  $arrForm['password_confirmation']) {
            $objErr->arrErr['password'] = '* Password must be identical. <br />';
        }

        return $objErr->arrErr;
    }

    function setRegisterTemplate() {
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . "YotpoPlugin/templates/register.tpl";
        $this->tpl_subtitle = "Yotpo Plug-in Registration";  
    }

    function setConfigTemplate() {
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR . "YotpoPlugin/templates/config.tpl";
        $this->tpl_subtitle = "Yotpo Plug-in Config";       
    }
}
