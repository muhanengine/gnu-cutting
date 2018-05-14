<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Supply\Front_end\Shop\SupplyFrontShopCartUpdate;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopCartUpdate
{
	/** @var $shopItem SupplyFrontShopCartUpdate */
	private $cartUpdate;
	private $isCartUpdate;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->cartUpdate   = new SupplyFrontShopCartUpdate();
			$this->isCartUpdate = $this->cartUpdate->isCartUpdate();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isCartUpdate ) {
			addAction( 'insert_cart_add_fields', array($this, 'getCartAddFields') );
			addAction( 'update_cart_options', array($this, 'getCartUpdateOptions') );
			addAction( 'insert_cart_add_values', array($this, 'getCartAddValues') );
			addAction( 'update_cart_item_change', array($this, 'changeItemData') );
		}
	}

	/**
	 * 장바구니 저장시 재단기능 관련 추가필드
	 *
	 * @param $it
	 * @param $itemId
	 * @param $tmpCartId
	 *
	 * @return string
	 */
	public function getCartAddFields( $it, $itemId, $tmpCartId )
	{
		return $this->cartUpdate->updateCartInsertFields( $it, $itemId, $tmpCartId );
	}

	/**
	 * 장바구니 저장시 재단기능 관련 추가필드 데이터
	 *
	 * @param $it
	 * @param $itemId
	 * @param $tmpCartId
	 *
	 * @return string
	 */
	public function getCartAddValues( $it, $itemId, $tmpCartId )
	{
		return $this->cartUpdate->updateCartInsertValues( $it, $itemId, $tmpCartId );
	}

	/**
	 * 장바구니 추가옵션 업데이트 쿼리
	 *
	 * @param $it
	 * @param $cartId
	 * @param $cartItemQty
	 *
	 * @return string
	 */
	public function getCartUpdateOptions( $it, $cartId, $cartItemQty )
	{
		return $this->cartUpdate->updateCartOptions( $it, $cartId, $cartItemQty );
	}

	/**
	 * 장바구니 담기중 재단 관련 저장 데이터 변경
	 *
	 * @param $tmpCartId
	 * @param $itemId
	 * @param array $it
	 */
	public function changeItemData( $tmpCartId, $itemId, $it )
	{
		$this->cartUpdate->changeItemPrice( $tmpCartId, $itemId, $it );
	}
}