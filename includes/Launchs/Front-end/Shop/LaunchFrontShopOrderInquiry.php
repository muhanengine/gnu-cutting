<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Supply\Front_end\Shop\SupplyFrontShopOrderInquiry;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopOrderInquiry
{
	/** @var $orderInquiry SupplyFrontShopOrderInquiry */
	private $orderInquiry;
	private $isOrderInquiry;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->orderInquiry   = new SupplyFrontShopOrderInquiry();
			$this->isOrderInquiry = $this->orderInquiry->isOrderInquiryView();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isOrderInquiry ) {
			addAction( 'order_inquiry_cutting_memo', array($this, 'getOrderInquiryCutMemo') );
			addAction( 'order_inquiry_cutting_list', array($this, 'getOrderInquiryCuttingList') );
		}
	}

	/**
	 * 주문확인 페이지에서 주문 상품 주문자 추가 간단메모 출력하기
	 *
	 * @param array $opt
	 * @param int $k
	 */
	public function getOrderInquiryCutMemo( $opt, $k )
	{
		$this->orderInquiry->orderInquiryCutMemo( $opt, $k );
	}

	/**
	 * 주문확인 페이지에서 재단 리스트 및 재단 가격 변경 출력하기
	 *
	 * @param $od
	 * @param $opt
	 * @param $row
	 */
	public function getOrderInquiryCuttingList( $od, $opt, $row )
	{
		$this->orderInquiry->initAssets();
		$this->orderInquiry->orderInquiryCuttingList( $od, $opt, $row );
		$this->orderInquiry->orderInquiryChangePrice( $opt, $row );
	}
}