<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Supply\Front_end\Shop\SupplyFrontShopOrderEmail;
use Cutting\Supply\Front_end\Shop\SupplyFrontShopOrderUpdate;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopOrderUpdate
{
	/** @var $orderUpdate SupplyFrontShopOrderUpdate */
	private $orderUpdate;
	/** @var $shopItem SupplyFrontShopOrderEmail */
	private $orderEmail;
	private $isOrderUpdate;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->orderUpdate   = new SupplyFrontShopOrderUpdate();
			$this->isOrderUpdate = $this->orderUpdate->isOrderUpdate();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isOrderUpdate ) {
			$this->orderEmail  = new SupplyFrontShopOrderEmail();

			addAction( 'order_update_cutting_add_amount', array($this, 'getAddTotalCuttingAmount') );
			addAction( 'order_update_success_after_cutting_complete', array($this, 'getOrderUpdateAfterCutting') );
			addAction( 'order_update_success_email_cutting_list', array($this, 'getEmailOrderCuttingLists') );
		}
	}

	/**
	 * 주문정보 업데이트 재단 합계금액 컷팅비용 추가
	 *
	 * @param $tmpCartId
	 */
	public function getAddTotalCuttingAmount( $tmpCartId )
	{
		$this->orderUpdate->orderUpdateTotalCuttingAmount( $tmpCartId );
	}

	/**
	 * 주문완료후 재단 od_id 변경하기
	 *
	 * @param $tmpCartId
	 * @param $orderId
	 */
	public function getOrderUpdateAfterCutting( $tmpCartId, $orderId )
	{
		$this->orderUpdate->orderUpdateAfterCuttingID( $tmpCartId, $orderId );
	}

	/**
	 * 주문완료 후 메일 보내기에서 재단 리스트 추가하기
	 *
	 * @param int $orderId 주문번호
	 * @param array $row 장바구니 정보
	 * @param array $row2 장바구니 옵션정보
	 * @param array $member
	 * @param string $optionsLi li style
	 * @param string $pricePlus
	 *
	 * @return string
	 */
	public function getEmailOrderCuttingLists( $orderId, $row, $row2, $member, $optionsLi, $pricePlus )
	{
		return $this->orderEmail->emailOrderCuttingLists( $orderId, $row, $row2, $member, $optionsLi, $pricePlus );
	}
}