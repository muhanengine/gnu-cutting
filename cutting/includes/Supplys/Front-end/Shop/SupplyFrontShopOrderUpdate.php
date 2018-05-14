<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 2:45
 */
namespace Cutting\Supply\Front_end\Shop;

use Cutting\Traits\CuttingTrait;
use function Cutting\Libs\Utils\isStrCaseCmp;

class SupplyFrontShopOrderUpdate
{
	use CuttingTrait;
	
	function __construct() {}

	/**
	 * 주문정보 업데이트 재단 합계금액 컷팅비용 추가로 인해 주문금액 다시 계산하기
	 *
	 * @param $tmpCartId
	 */
	public function orderUpdateTotalCuttingAmount( $tmpCartId )
	{
		$cut = $this->getTotalCutPrice( $tmpCartId, '', 1 );
		$GLOBALS['row']['od_price'] = $cut['price'];
		$GLOBALS['row']['od_price'] += $this->getOrderPrice( $tmpCartId );
	}

	/**
	 * 주문정보 업데이트 중 재단상품 제외한 총 주문금액 출력
	 *
	 * @param $tmpCartId
	 *
	 * @return mixed
	 */
	public function getOrderPrice( $tmpCartId )
	{
		$sql = /** @lang text */
			" SELECT SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as od_price
            FROM {$GLOBALS['g5']['g5_shop_cart_table']}
            WHERE od_id = '$tmpCartId' AND ct_select = '1'
                AND ct_cutting_use = '0' ";
		$row = sql_fetch($sql);

		return $row['od_price'];
	}

	/**
	 * 주문완료후 재단 od_id 변경하기
	 *
	 * @param $tmpCartId
	 * @param $orderId
	 */
	public function orderUpdateAfterCuttingID( $tmpCartId, $orderId )
	{
		$sql = /** @lang text */ " SELECT it_id FROM {$GLOBALS['g5']['g5_shop_cart_table']} WHERE od_id = '{$orderId}' AND ct_select = '1' ";
		$res = sql_query( $sql );
		while ( $row = sql_fetch_array( $res ) ) {
			$this->setCuttingCartOrder( $tmpCartId, $orderId, $row['it_id'], 'order' );
		}
	}

	/**
	 * 주문 페이지 확인하기
	 */
	public function isOrderUpdate()
	{
		$pcUrl     = '/'. G5_SHOP_DIR .'/orderformupdate.php';
		$mobileUrl = '/'. G5_MOBILE_DIR .'/'. G5_SHOP_DIR .'/orderformupdate.php';
		$inicisUrl = '/'. G5_SHOP_DIR .'/inicis/inistdpay_return.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $pcUrl)
			|| isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $mobileUrl)
			|| isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $inicisUrl) ) {
			return true;
		}

		return false;
	}
}