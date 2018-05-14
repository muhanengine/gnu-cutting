<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Admin\Shop;

use Cutting\Supply\Admin\Shop\SupplyAdminShopOrderList;
use function Cutting\Libs\Functions\addAction;

class LaunchAdminShopOrderList
{
	/** @var $orderItem SupplyAdminShopOrderList */
	private $orderItem;
	private $isOrderAjax;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->orderItem   = new SupplyAdminShopOrderList();
			$this->isOrderAjax = $this->orderItem->isOrderAjax();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isOrderAjax ) {
			addAction( 'order_list_cutting_item', array($this, 'getAddCuttingList') );
		}
	}

	/**
	 * 상품설정 페이지에 재단옵션 추가
	 *
	 * @param string $od_id
	 * @param array $row 장바구니 주문 상품목록 상품정보
	 * @param array $opt 상품옵션 정보
	 * @param array $od 주문정보
	 */
	public function getAddCuttingList( $od_id, $row, $opt, $od )
	{
		$this->orderItem->addCuttingList( $od_id, $row, $opt, $od );
		$this->orderItem->addCuttingTotalAmount( $opt, $row );
	}
}