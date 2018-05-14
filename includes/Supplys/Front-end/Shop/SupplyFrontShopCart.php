<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 2:45
 */
namespace Cutting\Supply\Front_end\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Traits\ShopItemTrait;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Utils\isStrCaseCmp;
use function Cutting\Libs\Functions\includeCssJs;

class SupplyFrontShopCart
{
	use CuttingTrait, ShopItemTrait;
	
	function __construct() {}

	/**
	 * 상품상세 페이지 재단기능 추가시 필수 CSS, JS 파일
	 */
	public function initAssets()
	{
		if ( G5_IS_MOBILE ) {
			includeCssJs( '/plugin/cutting/assets/css/ShopMobile.css' );
		} else {
			includeCssJs( '/plugin/cutting/assets/css/Shop.css' );
		}

		includeCssJs( '/plugin/cutting/assets/css/Cutting.css' );
		includeCssJs( '/plugin/cutting/assets/js/CuttingCartOption.js' );

		//장바구니 페이지에서 shop.override.js 파일을 불러오는 경우 삭제하세요.
		includeCssJs( '/js/shop.override.js' );
	}

	/**
	 * 장바구니 총금액에 재단금액 컷팅비용 추가
	 * 선택 옵션에 따라 판매수량 조정하기
	 *
	 * @param $s_cart_id
	 */
	public function cuttingCartAmount( $s_cart_id )
	{
		$itemId   = _isset( 'it_id', $GLOBALS['row'] );
		//$sumPrice = _isset( 'price', $GLOBALS['sum'] );

		/** @var array $item */
		$item       = &$this->getItem( $itemId );
		$itemCutOpt = _isset( 'it_cut_opt', $item );

		/** @var array $cut */
		$cut = $this->getTotalCutPrice( $s_cart_id, $itemId );
		/** @var int $cutPrice */
		$cutPrice = _isset( 'price', $cut, 'intval' );

		if ( $cutPrice > 0 || $itemCutOpt > 0 ) {
			$lumbers = $this->getLumbers( $s_cart_id );

			$GLOBALS['sum']['price'] = $cutPrice;

			$GLOBALS['mod_options'] = '<div class="sod_option_btn">';
			if ( ! empty( $lumbers ) ) {
				$GLOBALS['mod_options'] .= '<button type="button" class="view_options">선택사항보기</button>';
			} else {
				$GLOBALS['mod_options'] .= '<button type="button" class="null_options">재단정보가 없습니다.</button>';
			}
			$GLOBALS['mod_options'] .= '</div>';
		}

		switch ( $itemCutOpt ) {
			case '3': // 길이(자투리 있음)
				//$GLOBALS['sum']['qty'] = 1;
				//$GLOBALS['row']['ct_price'] = $cutPrice;
				break;
			case '1': case '2': // 길이, 판재
				$GLOBALS['sum']['qty'] = 1;
				$GLOBALS['row']['ct_price'] = $cutPrice;
				break;
			case '4':
				# 맟춤형
				break;
		}
	}

	/**
	 * 장바구니 페이지 확인하기
	 */
	public function isCart()
	{
		$url = '/'. G5_SHOP_DIR .'/cart.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}