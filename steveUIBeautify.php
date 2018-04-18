<?php

/*
  Plugin Name: Steve UI Beautify
  Plugin URI: http://www.steveUI.net
  Description: Plugin for displaying Subscriptions products without side bar
  Author: Stephen Mbaalu
  Version: 1.0
  Author URI: http://www.stephenmbaalu.net
 */

defined('ABSPATH') or die('No script kiddies please!');

// Define ST_PLUGIN_FILE.
if (!defined('ST_PLUGIN_FILE')) {
    define('ST_PLUGIN_FILE', __FILE__);
}

/**
 * When the_post is called, put product data into a global.
 *
 * @param mixed $post Post Object.
 * @return TST_Product
 */
function st_setup_product_data($post) {
    unset($GLOBALS['product']);
	global $wp;

    if (is_int($post)) {
        $the_post = get_post($post);
    } else {
        $the_post = $post;
    }

    if (empty($the_post->post_type) || !in_array($the_post->post_type, array('product', 'product_variation'), true)) {
        return;
    }
	
    $prodRes = wc_get_product($the_post);
    $GLOBALS['product'] = $prodRes;

    if (isset($prodRes)) {

        if ($prodRes instanceof WC_Product_Simple) {
            //then enque scripts
          
			$postAgain = get_page_by_path( $wp->query_vars['name'], OBJECT, 'post' );
			//$userIP=st_get_the_user_ip();
			$user_idH="12000";
			$meta_keyH=st_get_metaKEy();
			$meta_valueH=$postAgain->ID;
			
			add_user_meta( $user_idH, $meta_keyH, $meta_valueH );
				//print_r($postAgain);
            $url = plugins_url('/assets/style.css', __FILE__);
            wp_enqueue_style('style', $url, array(), '0.1.0', 'all');
           // print_r("<h1> Added Sytle Sheet </h1>");
        } else {
           // print_r("<h1> its not Instance </h1>");
        }
    }

    //print_r($GLOBALS['product']);

    return $GLOBALS['product'];
}

add_action('the_post', 'st_setup_product_data');

add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
 
function bbloomer_redirectcustom( $order_id ){
    $order = new WC_Order( $order_id );
 
    //$url = 'http://yoursite.com/custom-url';
 	//print_r($order);
	echo '<h1>order details ID</h2>';
	print_r($order->data->order);
    if ( $order->status != 'failed' ) {
		$user_idH="12000";
		$meta_keyH=st_get_metaKEy();
		$single = true;
       $user_last_id = get_user_meta( $user_idH, $meta_keyH, $single ); 
		$url=get_permalink($user_last_id);
        wp_redirect($url);
        //header('Location:'.$url);
        exit;
    }
}

function st_get_metaKEy(){
	$userIP=st_get_the_user_ip();
			//$user_idH="12000";
			$meta_keyH=$userIP.'_'.date("Ymd");
	return $meta_keyH;
}

function st_get_the_user_ip() {
if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return apply_filters( 'wpb_get_ip', $ip );
}


/*add_action('template_redirect', 'st_custom_redirect_after_purchase');

function st_custom_redirect_after_purchase() {
    global $wp;

    if (is_checkout() && !empty($wp->query_vars['order-received'])) {

        $order = new WC_Order($wp->query_vars['order-received']);

        $quantity = 0;
        if (count($order->get_items()) > 0) {
            foreach ($order->get_items() as $item) {

                if (!empty($item)) {
                    $quantity+= $item['qty'];
                }
            }
        }

        switch ($quantity) {
            case 1:
                wp_redirect('http://www.example.com/'); // Example Site
                break;
            case 2:
                wp_redirect('http://www.example1.com/');  // Example Site
                break;
            default:

                break;
        }
        exit();
    }
}*/


