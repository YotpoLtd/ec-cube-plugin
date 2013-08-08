<?php 

require_once(dirname( __FILE__ ) . '/lib/yotpo-api/Yotpo.php');

class YotpoRegister {

	public static function register($arrData) {
        $yotpo_api = new Yotpo();
        $shop_url = HTTP_URL; 
        $url_data = parse_url(HTTP_URL);
        $shop_domain = $url_data['host'];
        $user = array(
            'email' => $arrData['email'],
            'display_name' => $arrData['name'],
            'first_name' => '',
            'password' => $arrData['password'],
            'last_name' => '',
            'website_name' => $shop_url,
            'support_url' => $shop_url,
            'callback_url' => $shop_url,
            'url' => $shop_url);
                   
        $response = $yotpo_api->create_user($user, true);   
        if ($response == NULL) {
            throw new Exception('No connection to www.yotpo.com');
        }        
        if(!empty($response['status']) && !empty($response['status']['code'])) {
            if($response['status']['code'] == 200) {
                $app_key = $response['response']['app_key'];
                $secret = $response['response']['secret'];
                $yotpo_api->set_app_key($app_key);
                $yotpo_api->set_secret($secret);
                $shop_domain = parse_url($shop_url,PHP_URL_HOST);
                $account_platform_response = $yotpo_api->create_account_platform(array( 'shop_domain' => $shop_domain,
                                                                                        'utoken' => $response['response']['token'],
                                                                                        'platform_type_id' => 18));
                if(!empty($response['status']) && !empty($response['status']['code']) && $response['status']['code'] == 200) {
                    
                    //save settings in db
                    YotpoSettings::setSetting('app_key', $app_key);
                    YotpoSettings::setSetting('secret', $secret);
                    return true;                                    
                }
                elseif($response['status']['code'] >= 400){
                    if(!empty($response['status']['message'])) {

                        throw new Exception($response['status']['message']);
                    }
                }
            }
            elseif($response['status']['code'] >= 400){
                if(!empty($response['status']['message'])) { 
                    if(!empty($response['status']['message']['email'])) {
                        if(is_array($response['status']['message']['email'])) {

                            throw new Exception('Email '.$response['status']['message']['email'][0]);
                        }
                        else {
                            throw new Exception('Email '.$response['status']['message']['email']);
                        }                           
                    }   
                    else {
                        throw new Exception($response['status']['message']);
                    }                                                 
                }
            }
        }
        else {
            throw new Exception($response['status']['message']);   
        }
    }
}