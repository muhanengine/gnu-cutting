<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 2:45
 */
namespace Cutting\Supply\Front_end\Shop;

use Cutting\Traits\CuttingTrait;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Utils\postIsset;
use function Cutting\Libs\Utils\isStrCaseCmp;

class SupplyFrontShopCartUpdate
{
	use CuttingTrait;
	
	function __construct() {}

	/**
	 * 장바구니 저장시 재단기능 관련 추가된 필드 출력하기
	 *
	 * @param $it
	 * @param $itemId
	 * @param $tmpCartId
	 *
	 * @return string
	 */
	public function updateCartInsertFields( &$it, $itemId, $tmpCartId )
	{
		$itemCutOpt = _isset( 'it_cut_opt', $it );

		if ( $itemCutOpt > 0 ) {
			$query = /** @lang text */
				" delete from {$GLOBALS['g5']['g5_shop_cart_table']} 
					where od_id = '$tmpCartId' and it_id = '$itemId' ";
			sql_query( $query );
		}

		return ' , ct_cutting_use, ct_cut_price ';
	}

	/**
	 * 장바구니 저장시 재단상품의 경우 추가옵션 업데이트 구매수량 변경 부분 처리
	 *
	 * @param array $it
	 * @param $cartId
	 * @param $cartItemQty
	 *
	 * @return string
	 */
	public function updateCartOptions( &$it, $cartId, $cartItemQty )
	{
		$it_cut_opt = _isset( 'it_cut_opt', $it );

		if ( $it_cut_opt > 0 ) {
			$query = /** @lang text */
				" update {$GLOBALS['g5']['g5_shop_cart_table']}
                    set ct_qty = '{$cartItemQty}'
                    where ct_id = '{$cartId}' ";
		} else {
			$query = /** @lang text */
				" update {$GLOBALS['g5']['g5_shop_cart_table']}
                    set ct_qty = ct_qty + '{$cartItemQty}'
                    where ct_id = '{$cartId}' ";
		}

		return $query;
	}

	/**
	 * 장바구니 저장시 재단상품의 상품가격을 재단가격 포함한 가격으로 변경하기
	 *
	 * @param $tmpCartId
	 * @param $itemId
	 * @param array $it
	 */
	public function changeItemPrice( $tmpCartId, $itemId, &$it )
	{
		$itemCutOpt = _isset( 'it_cut_opt', $it );

		/** @var array $cut */
		$cut = $this->getCartCutPrice( $tmpCartId, $itemId );
		$itemPrice = _isset( 'total_cutting_price', $cut, 'intval' );

		if ( $itemPrice > 0 && ($itemCutOpt == 1 || $itemCutOpt == 2) ) {
			$GLOBALS['it']['it_price'] = $itemPrice;
		}
	}

	/**
	 * 장바구니 저장시 재단기능 관련 추가필드의 입력 데이터
	 *
	 * @param $it
	 * @param $it_id
	 * @param $tmp_cart_id
	 *
	 * @return string
	 */
	public function updateCartInsertValues( &$it, $it_id, $tmp_cart_id )
	{
		$cutUse   = postIsset( 'ct_cutting_use', 'intval', 0 );

		/** @var array $cartPrice */
		$cartPrice = $this->getCartCutPrice( $tmp_cart_id, $it_id );
		$cutPrice  = _isset( 'cutting_price', $cartPrice );

		$it_cut_opt = _isset( 'it_cut_opt', $it );
		if ( $it_cut_opt > 0 ) {
			$cutUse = 1;
		}

		return ", '{$cutUse}', '{$cutPrice}' ";
	}

	/**
	 * 장바구니 업데이트 페이지 확인하기
	 */
	public function isCartUpdate()
	{
		$url = '/'. G5_SHOP_DIR .'/cartupdate.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}