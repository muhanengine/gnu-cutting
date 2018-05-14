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
use Cutting\Models\Front_end\Shop\SupplyFrontShopOrderEmailModel as OrderEmail;
use function Cutting\Libs\Utils\_isset;

class SupplyFrontShopOrderEmail
{
	use CuttingTrait, ShopItemTrait;

	function __construct() {}

	/**
	 * 주문완료 메일 보내기에서 재단 목록 추가하기
	 * 주문총금액 재단비용으로 변경하기
	 *
	 * @param int $orderId 주문번호
	 * @param array $row 장바구니 정보
	 * @param array $row2 장바구니 옵션정보
	 * @param array $member 주문회원정보
	 * @param string $optionsLi li css
	 * @param string $pricePlus
	 *
	 * @return string
	 */
	public function emailOrderCuttingLists( $orderId, &$row, &$row2, &$member, $optionsLi, $pricePlus )
	{
		$cuttings = '';

		$memberId         = _isset( 'mb_id', $member );
		$itemId           = _isset( 'it_id', $row );
		$cartQty          = _isset( 'ct_qty', $row2 );
		$cartOption       = _isset( 'ct_option', $row2 );
		$itemOptionPrice  = _isset( 'io_price', $row2 );

		$lumbers  = $this->getLumbers( $orderId, $itemId, $cartOption, 1, $memberId );

		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			$cssList  = &OrderEmail::getScheme();
			$cuttings = $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'], $cssList );
			$ctPrice  = _isset( 'ct_price', $row, 'intval' ); // 상품가격

			/** @var array $item */
			$item = &$this->getItem( $itemId );
			$itemCutOpt = _isset( 'it_cut_opt', $item );

			if ( $itemCutOpt == 3 ) { //길이(자투리 있음)
				/** @var array $cartPrice */
				$cartPrice = $this->getCartCutPrice( $orderId, $itemId, '', 1 );
				$sellPrice = _isset( 'total_cutting_price',$cartPrice );
				$GLOBALS['sum']['price'] = $sellPrice;
			} else {

				$GLOBALS['sum']['price'] = $ctPrice;
			}
		}

		$result = '<li' . $optionsLi . '>' . $cartOption . ' (' . $pricePlus . display_price($itemOptionPrice) . ') ' .
		          $cartQty . '개<br />' . $cuttings . '</li>' . PHP_EOL;
		return $result;
	}
}