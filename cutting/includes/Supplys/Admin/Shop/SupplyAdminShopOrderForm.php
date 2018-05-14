<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: AM 11:43
 */
namespace Cutting\Supply\Admin\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Traits\ShopItemTrait;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Utils\isStrCaseCmp;
use function Cutting\Libs\Functions\includeCssJs;

class SupplyAdminShopOrderForm
{
	use CuttingTrait, ShopItemTrait;
	
	function __construct() {}

	/**
	 * 주문확인 페이지에서 주문정보 검색시 주문자 추가메모 필드 추가하기
	 */
	public function initAsset()
	{
		includeCssJs( '/plugin/cutting/assets/css/ShopAdmin.css' );
	}

	/**
	 * 주문확인 페이지에서 주문정보 검색시 주문자 추가메모 필드 추가하기
	 */
	public function addCuttingMemoTableFields()
	{
		return ', ct_cutmemo';
	}

	/**
	 * 주문확인 페이지에서 재단 리스트 추가
	 *
	 * @param array $opt 상품옵션 정보
	 * @param array $od 주문정보
	 */
	public function itemCuttingListTable( &$opt, &$od )
	{
		$lumbers = $this->getLumbers( $od['od_id'], $opt['it_id'], $opt['ct_option'], 1, $od['mb_id'] );

		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			echo $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'] );
		}
	}

	/**
	 * 주문확인 페이지에서 재단 리스트 및 재단 총 금액 추가
	 *
	 * @param array $opt 상품옵션 정보
	 * @param array $row 장바구니 정보
	 */
	public function itemCuttingChangePrice( &$opt, &$row )
	{
		$cartId = _isset( 'ct_id', $opt );
		$itemId = _isset( 'it_id', $row );

		/** @var array $item */
		$item = &$this->getItem( $itemId );

		$itemCutOpt = _isset( 'it_cut_opt', $item );
		$sellPrice = $this->getCuttingTotalPrice( $cartId );

		if ( $sellPrice ) {
			if ( $itemCutOpt == 1 || $itemCutOpt == 2 ) { //길이(자투리 없음), 판재인 경우
				$GLOBALS['opt_price'] = $sellPrice;
			}
			$GLOBALS['ct_price']['stotal'] = $sellPrice;
		}
	}

	/**
	 * 주문확인 페이지 확인하기
	 */
	public function isOrderForm()
	{
		$url = '/'. G5_ADMIN_DIR.'/shop_admin/orderform.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}
