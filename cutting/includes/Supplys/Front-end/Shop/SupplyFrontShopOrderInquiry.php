<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 2:45
 */
namespace Cutting\Supply\Front_end\Shop;

use Cutting\Libs\Functions;
use Cutting\Traits\CuttingTrait;
use Cutting\Traits\ShopItemTrait;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Utils\isStrCaseCmp;

class SupplyFrontShopOrderInquiry
{
	use CuttingTrait, ShopItemTrait;

	function __construct() {}

	/**
	 * 상품상세 페이지 재단기능 추가시 필수 CSS, JS 파일
	 */
	public function initAssets()
	{
		if ( G5_IS_MOBILE ) {
			Functions\includeCssJs( '/plugin/cutting/assets/css/ShopMobile.css' );
		} else {
			Functions\includeCssJs( '/plugin/cutting/assets/css/Shop.css' );
		}
	}
	/**
	 * 주문확인 페이지에서 주문 상품 주문자 추가 간단메모 출력하기
	 *
	 * @param array $opt 주문 장바구니 옵션정보
	 * @param int $k 장바구니 옵션 목록 순서
	 *
	 * @return string
	 */
	public function orderInquiryCutMemo( &$opt, $k )
	{
		$memo = '';

		if ( $opt['ct_cutmemo'] && $k == 0 ) {
			$memo = '<div class="cut-memo"><label for="cut-memo">간단내용</label><br /><span>' . nl2br( stripslashes( $opt['ct_cutmemo'] ) ) . '</span></div>';
		}

		return $memo;
	}

	/**
	 * 주문확인 페이지에서 재단 리스트 출력
	 *
	 * @param array $od 주문정보
	 * @param array $opt 주문 장바구니 옵션정보
	 * @param array $row 주문 장바구니 정보
	 */
	public function orderInquiryCuttingList( &$od, &$opt, &$row )
	{
		$cutting = '';
		$lumbers = $this->getLumbers( $od['od_id'], $row['it_id'], $opt['ct_option'], 1 );

		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			$cutting = $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'] );
		}

		echo $cutting;
	}

	/**
	 * 주문확인 페이지에서 재단 상품의 경우 판매가, 소계 변경하기
	 *
	 * @param array $opt 주문 장바구니 옵션정보
	 * @param array $row 상품정보
	 */
	public function orderInquiryChangePrice( &$opt, &$row )
	{
		$cartId = _isset( 'ct_id', $opt );
		$itemId = _isset( 'it_id', $row );

		/** @var array $item */
		$item = &$this->getItem( $itemId );

		$itemCutOpt = _isset( 'it_cut_opt', $item );
		$sellPrice  = $this->getCuttingTotalPrice( $cartId );

		if ( $sellPrice ) {
			if ( $itemCutOpt == 1 || $itemCutOpt == 2 ) { //길이(자투리 없음), 판재인 경우
				$GLOBALS['opt_price'] = $sellPrice;
			}
			$GLOBALS['sell_price'] = $sellPrice;
		}
	}

	/**
	 * 주문확인 페이지 확인하기
	 */
	public function isOrderInquiryView()
	{
		$url = '/'. G5_SHOP_DIR .'/orderinquiryview.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}