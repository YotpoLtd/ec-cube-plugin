<?php

require_once(dirname( __FILE__ ) . '/lib/yotpo-api/Yotpo.php');
require_once(dirname( __FILE__ ) . '/YotpoSettings.php');

class YotpoMapProcessor {


	public static function exportOrders() {
        $app_key = YotpoSettings::getSetting('app_key');
        $secret = YotpoSettings::getSetting('secret');
        if ($app_key == NULL || $secret == NULL) {
            throw new Exception('missing app_key or secret');
        }


        $yotpo_api = new Yotpo($app_key, $secret);
        $get_oauth_token_response = $yotpo_api->get_oauth_token();
        if(!empty($get_oauth_token_response) && !empty($get_oauth_token_response['access_token'])) {
            $offset = 0;
            $limit = 200;
            $past_orders = NULL; 

            do {
                $past_orders = YotpoMapProcessor::getPastOrders($limit, $offset);    
                if (count($past_orders) == 0) {
                    continue;
                }

                $ordersRes = array();
                $ordersRes['orders'] = YotpoMapProcessor::preparePurchases($past_orders);
                $ordersRes['platform'] = 'eccube';
                $ordersRes['utoken'] = $get_oauth_token_response['access_token'];

                $response = $yotpo_api->create_purchases($ordersRes);                       
                if ($response['code'] != 200) {
                    throw new Exception('failed to create purchases');
                }

                $offset += $limit; 
            } while ($past_orders != NULL);

        } else {
            throw new Exception('wrong app key and secret');
        }
    }

    public static function exportSingleOrder($order) {
        $yotpo_api = new Yotpo(YotpoSettings::getSetting('app_key'), YotpoSettings::getSetting('secret'));
        $get_oauth_token_response = $yotpo_api->get_oauth_token();
        if(!empty($get_oauth_token_response) && !empty($get_oauth_token_response['access_token'])) {
            $orders = YotpoMapProcessor::preparePurchases(array($order));
            $orders = $orders[0];

            $orders['platform'] = 'eccube';
            $orders['utoken'] = $get_oauth_token_response['access_token'];

            $response = $yotpo_api->create_purchase($orders);   
            if ($response['code'] != 200) {
                //failed to create purchase
            }
        } else {
           //can't get token for user 
        }

    
    }


    public static function getPastOrders($limit, $page) {
        $objQuery = & SC_Query_Ex::getSingletonInstance();

        $col = <<< __EOS__
            P.product_id,
            P.name,
            P.main_comment,
            P.main_large_image,
            OD.price,
            O.order_name01,
            O.order_name02,
            O.order_email,
            O.create_date,
            O.order_id,
            O.status
__EOS__;

        $from = <<< __EOS__
            dtb_order AS O
            INNER JOIN dtb_order_detail OD
                ON O.order_id = OD.order_id
            INNER JOIN dtb_products P
                ON P.product_id = OD.product_id
__EOS__;
        
        $today = time();
        $last = $today - (60*60*24*90); //90 days ago
        $date = date("Y-m-d", $last);
        $where = "O.create_date > '".$date."' AND O.status IN (".ORDER_DELIV.",".ORDER_NEW.")"; //TODO decide the order status we want to take

        $objQuery->setOrder('O.create_date DESC');
        $objQuery->setLimitOffset($limit, $page);
        return $objQuery->select($col, $from, $where, array());
    }

    public static function getOrder($orderId) {
        $objQuery = & SC_Query_Ex::getSingletonInstance();

        $col = <<< __EOS__
            P.product_id,
            P.name,
            P.main_comment,
            P.main_large_image,
            OD.price,
            O.order_name01,
            O.order_name02,
            O.order_email,
            O.create_date,
            O.order_id,
            O.total,
            O.status
__EOS__;

        $from = <<< __EOS__
            dtb_order AS O
            INNER JOIN dtb_order_detail OD
                ON O.order_id = OD.order_id
            INNER JOIN dtb_products P
                ON P.product_id = OD.product_id
__EOS__;

        $where = "O.order_id = ".$orderId;
        $res = $objQuery->select($col, $from, $where, array());
        if (count($res) == 1) {
            return $res[0];
        } else {
            return null;
        }              
    }

    public static function preparePurchases($rawOrders) {
        $orders = array();

        foreach ($rawOrders as $rawOrder) {
            if (!array_key_exists($rawOrder['order_id'], $orders)) {
                $orderData = array();
                $orderData['order_date'] = $rawOrder['create_date'];
                $orderData['email'] = $rawOrder['order_email'];
                $orderData['customer_name'] = $rawOrder['order_name01']." ".$rawOrder['order_name02'];
                $orderData['order_id'] = $rawOrder['order_id'];
                $orderData['currency_iso'] = 'JPY'; 
                $orderData['status'] = $rawOrder['status'];
                $orderData['products'] = array();
                $orders[$rawOrder['order_id']] = $orderData;

            }

            $productData = array();
            $productData['url'] = str_replace(ROOT_URLPATH,P_DETAIL_URLPATH,HTTP_URL.$rawOrder['product_id']);
            $productData['name'] = $rawOrder['name'];
            $productData['image'] = IMAGE_SAVE_RSS_URL.$rawOrder['main_large_image'];
            $productData['description'] = $rawOrder['main_comment'];
            $productData['price'] = $rawOrder['price'];

            $orders[$rawOrder['order_id']]['products'][$rawOrder['product_id']]  = $productData;
        }
        return array_values($orders);
    }
}