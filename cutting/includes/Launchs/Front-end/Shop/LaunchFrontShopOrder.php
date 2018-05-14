<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Supply\Front_end\Shop\SupplyFrontShopOrder;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopOrder
{
	/** @var $shopItem SupplyFrontShopOrder */
	private $order;
	private $isOrder;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->order   = new SupplyFrontShopOrder();
			$this->isOrder = $this->order->isOrder();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isOrder ) {
			addAction( 'order_cutting_total_amount', array($this, 'getCuttingTotalAmount') );
		}
	}

	/**
	 * 주문하기 페이지에서 재단 합계금액 컷팅비용 추가
	 *
	 * @param string $sessionCartId 장바구니 아이디
	 * @param array $row 상품정보
	 */
	public function getCuttingTotalAmount( $sessionCartId, $row )
	{
		$this->order->cuttingTotalAmount( $sessionCartId, $row );
	}
}