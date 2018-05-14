<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Admin\Shop;

use Cutting\Supply\Admin\Shop\SupplyAdminShopOrderForm;
use function Cutting\Libs\Functions\addAction;

class LaunchAdminShopOrderForm
{
	/** @var $orderForm SupplyAdminShopOrderForm */
	private $orderForm;
	private $isOrderForm;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->orderForm   = new SupplyAdminShopOrderForm();
			$this->isOrderForm = $this->orderForm->isOrderForm();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isOrderForm ) {
			addAction( 'order_form_cutting_memo', array($this, 'getAddCuttingMemoTableFields') );
			addAction( 'order_form_cutting_list_total_amount', array($this, 'getCuttingListTotalAmount') );
		}
	}

	/**
	 * 주문페이지에서 주문정보 검색시 주문자 추가메모 필드 추가하기
	 */
	public function getAddCuttingMemoTableFields()
	{
		$this->orderForm->addCuttingMemoTableFields();
	}

	/**
	 * 주문페이지에서 재단 리스트 및 재단 총 금액 추가
	 *
	 * @param array $opt 상품옵션 정보
	 * @param array $od 주문정보
	 * @param array $row 장바구니 정보
	 */
	public function getCuttingListTotalAmount( $opt, $od, $row )
	{
		$this->orderForm->initAsset();
		$this->orderForm->itemCuttingListTable( $opt, $od );
		$this->orderForm->itemCuttingChangePrice( $opt, $row );
	}
}