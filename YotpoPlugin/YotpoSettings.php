<?php

class YotpoSettings {

	public static function getSetting($key) {
		$objQuery = & SC_Query_Ex::getSingletonInstance();
        $sql = "SELECT yotpo_value FROM plg_yotpo_settings WHERE yotpo_key = '".$key."'";
        return $objQuery->getOne($sql);
	}

	public static function setSetting($key, $value) {
		$objQuery = & SC_Query_Ex::getSingletonInstance();

        $arrModule = array();
        $arrModule['yotpo_key'] = $key;
        $arrModule['yotpo_value'] = $value;
        $arrModule['create_date'] = 'CURRENT_TIMESTAMP';

        //check wether we need to insert a new row or update an existing one
        $value = YotpoSettings::getSetting($key);
        if ($value == NULL) {
            $arrModule['update_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert('plg_yotpo_settings', $arrModule);    
        } else {
            $objQuery->update('plg_yotpo_settings', $arrModule, 'yotpo_key = ?', array($key));
        } 
	}

    public static function settingExists($key) {
        $objQuery = & SC_Query_Ex::getSingletonInstance();
        $res = $objQuery->count('plg_yotpo_settings', 'yotpo_key = ?', array($key));
        return ($res > 0) ? true : false;
    }
}