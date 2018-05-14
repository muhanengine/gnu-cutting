<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:27
 */

namespace Cutting\Libs\ShopFunctions;

/**
 * 영카트 유일한 키값 받아오기
 * @return string
 */
function getUniqueCartOrderId()
{
	$cartId = get_session('ss_cart_id');

	if ( ! $cartId ) {
		if ( ! function_exists('set_cart_id') ) {
			include_once( G5_LIB_PATH .'/shop.lib.php' );
		}

		set_cart_id( '' ); //cart id 설정
		$cartId = get_session( 'ss_cart_id' );
	}

	return $cartId;
}