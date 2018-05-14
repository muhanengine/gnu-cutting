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

class SupplyAdminShopOrderList
{
	use CuttingTrait, ShopItemTrait;
	
	function __construct() {}

	/**
	 * 주문목록 페이지 주문상품 재단내역 출력하기
	 *
	 * @param string $od_id
	 * @param array $row 상품목록 상품정보
	 * @param array $opt 상품옵션 정보
	 * @param array $od 주문정보
	 */
	public function addCuttingList( $od_id, &$row, &$opt, &$od )
	{
		/*$lumbers = $this->getLumbers( $od_id, $row['it_id'], $opt['ct_option'], 1, $od['mb_id'] );
		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			echo $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'] );
		}*/
	}

	/**
	 * 주문목록 페이지 주문상품 주문금액에 재단 총금액 추가하기
	 *
	 * @param array $opt 상품옵션 정보
	 * @param array $row 상품 정보
	 */
	public function addCuttingTotalAmount( &$opt, &$row )
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
	 * 주문목록, 주문상품목록 페이지 확인하기
	 */
	public function isOrderAjax()
	{
		$adminOrderUrl     = '/'. G5_ADMIN_DIR.'/shop_admin/orderlist.php';
		$adminOrderAjaxUrl = '/'. G5_ADMIN_DIR.'/shop_admin/ajax.orderitem.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $adminOrderUrl)
			 || isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $adminOrderAjaxUrl) ) {
			return true;
		}

		return false;
	}
}
