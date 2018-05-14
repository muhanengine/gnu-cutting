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

class SupplyFrontShopOrder
{
	use CuttingTrait, ShopItemTrait;
	
	function __construct() {}

	/**
	 * 주문하기 페이지에서 재단 합계금액 컷팅비용 추가
	 * 주문 옵션에 따라 판매수량 조정하기
	 *
	 * @param $sessionCartId
	 * @param array $row
	 */
	public function cuttingTotalAmount( $sessionCartId, &$row )
	{
		$it_id = _isset( 'it_id', $row );

		/** @var array $cut */
		$cut   = $this->getTotalCutPrice( $sessionCartId, $it_id, 1 );
		$price = _isset( 'price', $cut );

		/** @var array $item */
		$item       = &$this->getItem( $it_id );
		$itCutOpt = _isset( 'it_cut_opt', $item );

		if ( $price > 0 || $itCutOpt > 0 ) {
			// 합계금액 컷팅비용 추가
			/** @var array $GLOBALS */
			$GLOBALS['sum']['price'] = (int) $price;
		}

		switch ( $itCutOpt ) {
			case '1': case '2': # 길이, 판재
				$GLOBALS['sum']['qty'] = 1;
				$GLOBALS['row']['ct_price'] = $GLOBALS['sum']['price'];
				break;
			case '3': # 길이(자투리 있음)
				//$GLOBALS['sum']['qty'] = 1;
				//$GLOBALS['row']['ct_price'] = $GLOBALS['sum']['price'];
				break;
			case '4':
				# 맟춤형
				break;
		}
	}

	/**
	 * 주문 페이지에서 재단 리스트 및 재단 총 금액 추가
	 *
	 * @param array $sessionCartId 장바구니 번호
	 * @param array $row 장바구니 정보
	 */
	public function itemCuttingList( $sessionCartId, &$row )
	{
		$lumbers = $this->getLumbers( $sessionCartId, $row['it_id'], $row['ct_option'] );

		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			echo $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'] );
		}
	}
	/**
	 * 주문 페이지 확인하기
	 */
	public function isOrder()
	{
		$url = '/'. G5_SHOP_DIR .'/orderform.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}