<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Supply\Front_end\Shop\SupplyFrontShopCart;
use Cutting\Supply\Front_end\Shop\SupplyFrontShopCartOption;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopCart
{
	use CuttingTrait;
	
	/** @var $shopItem SupplyFrontShopCart */
	private $cart;
	/** @var $shopItem SupplyFrontShopCart */
	private $cartOption;
	private $isCart;
	private $isCartOption;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->cart         = new SupplyFrontShopCart();
			$this->cartOption   = new SupplyFrontShopCartOption();
			$this->isCart       = $this->cart->isCart();
			$this->isCartOption = $this->cartOption->isCartOption();

			$this->init();
		}
	}

	public function init()
	{
		$this->initUpdateCuttingCartId();

		if ( $this->isCart ) {
			addAction( 'cart_add_assets', array($this, 'getCartCuttingAssets') );
			addAction( 'cart_option_amount', array($this, 'getCartCuttingAmount') );
		}

		if ( $this->isCartOption ) {
			addAction( 'cart_option_view_cutting_memo', array($this, 'getCartOptionView') );
			addAction( 'cart_option_view_cutting_table', array($this, 'getCartOptionTable') );
			addAction( 'cart_option_view_cutting_price', array($this, 'getCartOptionPrice') );
		}
	}

	/**
	 * 재단테이블 cart_id 업데이트 하기
	 */
	public function initUpdateCuttingCartId()
	{
		$this->updateCuttingCartId();
	}

	/**
	 * 장바구니 페이지에 CSS, JS 추가
	 */
	public function getCartCuttingAssets()
	{
		$this->cart->initAssets();
	}

	/**
	 * 재단 합계금액 컷팅비용 추가
	 *
	 * @param $sessionCartId
	 */
	public function getCartCuttingAmount( $sessionCartId )
	{
		$this->cart->cuttingCartAmount( $sessionCartId );
	}

	/**
	 * 장바구니 옵션 페이지에 구입메모 출력하기
	 * 현재 사용하고 있지 않음
	 *
	 * @param $row
	 */
	public function getCartOptionView( $row )
	{
		$this->cartOption->getCutMemo( $row );
		$this->cartOption->getCuttingUse( $row );
	}

	/**
	 * 장바구니 옵션 페이지에 재단 상세옵션 보개
	 *
	 * @param array $row
	 * @param int $cartId
	 * @param int $count
	 * @param int $itemOptionPrice
	 */
	public function getCartOptionTable( $row, $cartId, $count, $itemOptionPrice )
	{
		$this->cartOption->cuttingOptionTable( $row, $cartId, $count, $itemOptionPrice );
	}

	/**
	 * 장바구니 옵션 페이지에 재단 옵션별 옵션 구매 비용 보기
	 *
	 * @param $it
	 * @param $cartId
	 */
	public function getCartOptionPrice( $it, $cartId )
	{
		$this->cartOption->cuttingOptionPrice( $it, $cartId );
	}
}