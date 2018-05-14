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
use function Cutting\Libs\Utils\isStrCaseCmp;

class SupplyFrontShopCartOption
{
	use CuttingTrait;

	function __construct() {}

	/**
	 * 간단메모 옵션 출력
	 *
	 * @param array $row
	 */
	public function getCutMemo( &$row )
	{
		static $memoUse = null;

		$cartCutMemo = _isset( 'ct_cutmemo', $row );

		if ( is_null($memoUse) && $cartCutMemo ) { //__간단메모옵션사용__//
			$memoUse = true;
			echo '<li>' . PHP_EOL;
			echo '<h4>간단내용</h4> <br />';
			echo nl2br( $cartCutMemo );
			echo '</li>' . PHP_EOL;
		}
	}

	/**
	 * 재단 사용 유무
	 *
	 * @param array $row
	 */
	public function getCuttingUse( &$row )
	{
		static $initialUse = null;

		$cartCuttingUse = _isset( 'ct_cutting_use', $row );

		if ( is_null($initialUse) && $cartCuttingUse ) { //__재단 사용 유무__//
			$initialUse = true;
		}
	}

	/**
	 * 장바구니 페이지에서 재단상품의 재단상세 내용, 일반상품의 옵션 보기
	 * @examplewww/shop/cartoption.view.php
	 *
	 * @param array $row 옵션정보
	 * @param int $cartId 장바구니 번호
	 * @param int $count 옵션출력 순서
	 * @param int $itemOptionPrice 옵션가격
	 */
	public function cuttingOptionTable( &$row, $cartId, $count, $itemOptionPrice )
	{
		$itemId     = _isset( 'it_id', $row );
		$cartOption = _isset( 'ct_option', $row );

		$lumbers = $this->getLumbers( $cartId, $itemId, $cartOption );

		if ( count($lumbers) ) { //__재단 리스트 테이블 출력__//
			echo $this->getCuttingTable( $lumbers[0]['cutting_list'], $lumbers[0]['cutting_jatturi'] );
			echo '<div class="opt_count">';
			echo '<input type="hidden" name="ct_qty['. $itemId .'][]" value="'. $row['ct_qty'] .'" id="ct_qty_' . $count . '" class="num_input" size="5">';
	        echo '</div>';
		} else {
			echo '<div class="opt_count">';
			echo '<button type="button" class="sit_qty_minus btn_frmline"><i class="fa fa-minus" aria-hidden="true"></i><span class="sound_only">감소</span></button>';
			echo '<label for="ct_qty_' . $count . '" class="sound_only">수량</label>';
			echo '<input type="text" name="ct_qty['. $itemId .'][]" value="'. $row['ct_qty'] .'" id="ct_qty_' . $count . '" class="num_input" size="5">';
			echo '<button type="button" class="sit_qty_plus btn_frmline"><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only">증가</span></button>';
			echo '<span class="sit_opt_prc">' . $itemOptionPrice . '</span>';
			echo '<button type="button" class="sit_opt_del"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">삭제</span></button>';
	        echo '</div>';
		}
	}

	/**
	 * 장바구니 상품별 상세옵션 보기에서 재단비용 및 수정된 총금액 출력하기
	 * @example www/shop/cartoption.view.php
	 *
	 * @param array $it
	 * @param int $cartId
	 */
	public function cuttingOptionPrice( &$it, $cartId )
	{
		$it_id = _isset( 'it_id', $it );

		$cut = $this->getTotalCutPrice( $cartId, $it_id );
		$cut_price = _isset( 'price', $cut ); // 재단비용

		echo '<input type="hidden" name="cut_price" id="cut_price" value="'. $cut_price .'">';
        echo '<input type="hidden" name="io_cut_price['. $it_id .'][]" value="'. $cut_price .'">';
		echo '<div id="sit_tot_price"><span>총 금액 :</span> '. number_format($cut_price) .'원</div>';
	}

	/**
	 * 장바구니 페이지 확인하기
	 */
	public function isCartOption()
	{
		$url = '/'. G5_SHOP_DIR .'/cartoption.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}