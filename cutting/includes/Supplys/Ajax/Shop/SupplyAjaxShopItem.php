<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2018. 1. 3.
 * Time: PM 3:49
 */
namespace Cutting\Supply\Ajax\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Traits\ShopItemTrait;
use function Cutting\Libs\ShopFunctions\getUniqueCartOrderId;
use function Cutting\Libs\Utils\postIsset;
use function Cutting\Libs\Utils\_isset;

class SupplyAjaxShopItem
{
    use CuttingTrait, ShopItemTrait;
    
	function __construct() {}

	/**
	 * 재단 내역 삭제하기
     * @example /shop/item.php
	 */
	public function itemCuttingDelete()
	{
		$cutting_id = postIsset( 'cutting_id', 'intval' );

		if ( $cutting_id ) {
			$this->deleteCuttingTableTerm( $GLOBALS['member']['mb_id'], $cutting_id );
			die( "101" ); //삭제성공
		}

		die( "100" ); //재단 완료
	}

	/**
	 * 재단 내역 저장하기
     * @example /shop/item.php
	 */
	public function itemCuttingUpdate()
	{
		$tmpCartId = getUniqueCartOrderId(); //cart id 설정
		$cutOption = postIsset( 'cut_option', 'sql_real_escape_string' );
		$itemIds   = postIsset( 'it_id' );
		$itemId    = (int) $itemIds[0];

		//분류사용, 상품사용하는 상품의 정보를 얻음
		$item = &$this->getItem( $itemId ); /** @var array $item */
		$itCutOpt = _isset( 'it_cut_opt', $item, 'intval' );

		$itCuttingTotalPrice = postIsset( 'it_cutting_total_price', 'intval', 0 );
		if ( $itCuttingTotalPrice ) {
			$itPrice = $itCuttingTotalPrice;
		} else {
			$itPrice = _isset( 'it_sale_amount', $item, 'intval' );
		}

		if ( ! $item['it_id'] )
			die( "901" ); //자료가 없습니다.
		if ( ! ( $item['ca_use'] && $item['it_use']) ) {
			if ( ! $GLOBALS['is_admin'] )
				die( "902" ); //현재 판매가능한 상품이 아닙니다.
		}

		//브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
		if ( ! $tmpCartId ) {
			//브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.
            //\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.
			die( "900" );
		}

		//보관된 자료 cart id 변경
		if ( $GLOBALS['member']['mb_id'] && $tmpCartId ) {
			if ( $GLOBALS['default']['de_cart_keep_term'] ) {
				$lifeTime = date( 'Y-m-d', G5_SERVER_TIME - ( $GLOBALS['default']['de_cart_keep_term'] * 86400 ) );
			} else {
				//장바구니 보관기간이 없는 경우 하루지난 목재재단 정보 삭제
				$lifeTime = date( 'Y-m-d', G5_SERVER_TIME - 86400 );
			}

			$this->deleteCuttingTableTerm( '', '', $lifeTime );
			$this->updateCuttingCartId( $tmpCartId );
		}

		$res = $this->selectCuttingTable( $tmpCartId, $GLOBALS['member']['mb_id'], $itemId, $cutOption );
		$row = sql_fetch_array( $res );

		if ( $row['cutting_id'] ){
			$this->updateCuttingTable( $_POST, $itCutOpt, $itPrice, $GLOBALS['member']['mb_id'] );
		} else {
			$this->insertCuttingTable( $_POST, $itCutOpt, $itPrice );
		}

		die( "100" ); //재단 저장 완료
	}


	/**
	 * 재단목록 결과 출력페이지
	 * @example /shop/item.php
	 */
	public function itemCuttingList()
	{
		$itemId    = postIsset( 'it_id', 'intval' );
		$tmpCartId = getUniqueCartOrderId();

		//브라우저에서 쿠키를 허용하지 않은 경우라고 볼 수 있음.
		if ( ! $tmpCartId ) {
			//브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.
            //\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.
			return;
		}

		//보관된 자료 cart id 변경
		$this->updateCuttingCartId( $tmpCartId );

		//분류사용, 상품사용하는 상품의 정보를 얻음
		$item     = &$this->getItem( $itemId );
		$cuttings = $this->selectCuttingTable( $tmpCartId, $GLOBALS['member']['mb_id'], $itemId );

		if ( $cuttings ) {
			?><ul id="sit_opt_added"><?php

			while ( $cutting = sql_fetch_array( $cuttings ) ) {
				$cuttingList   = unserialize( $cutting['cutting_list'] );
				$cuttingOption = '';
				$optionName    = $item['it_name'];

				$io = &$this->getItemOption( $itemId, $cutting['cutting_option'] );

				if ( $cutting['cutting_option'] ) {
					$cuttingOption = '(' . $cutting['cutting_option'] . ')';
				}

				if ( $item['it_option_subject']  ) {
					$optionName = $item['it_option_subject'] . ':' . $io['io_id'];
				}
				?>
				<li class="sit_opt_list">
					<input type="hidden" name="io_type[<?=$itemId?>][]" value="<?=$io['io_type']?>">
					<input type="hidden" name="io_id[<?=$itemId?>][]" value="<?=$io['io_id']?>">
					<input type="hidden" name="io_value[<?=$itemId?>][]" value="<?=$optionName?>">
					<input type="hidden" name="io_cut_price[<?=$itemId?>][]" class="io_cut_price" value="<?=$cutting['cutting_total_price']?>">
					<input type="hidden" name="io_opt_price[<?=$itemId?>][]" class="io_opt_price" value="<?=$cutting['cutting_opt_price']?>">
					<input type="hidden" name="io_qty[<?=$itemId?>][]" class="io_qty" value="<?=$cutting['cutting_qty']?>">
					<input type="hidden" name="io_cutting_price[<?=$itemId?>][]" class="io_cutting_price" value="<?=$cutting['cutting_price']?>">
					<input type="hidden" class="io_price" value="<?=$cutting['cutting_price']?>">
					<input type="hidden" class="io_stock" value="<?=$item['it_stock_qty']?>">
					<span class="sit_opt_subj"><?=$item['it_name']?> <?=$cuttingOption?></span>

					<table class="cutting_table">
						<tr>
							<?php
							foreach ( $cuttingList[0] as $key => $value) {
								echo '<th class="title-name">'.$value.'</th>' . PHP_EOL;
							}
							?>
						</tr>
						<?php
						for ( $c = 1; $c < count( $cuttingList ); $c++ ) {
							echo '<tr>';
							foreach ( $cuttingList[$c] as $key => $value) {
								echo '<td>';
								echo '<input type="text" value="'.$value.'" readonly class="input_readonly" />';
								echo '</td>' . PHP_EOL;
							}
							echo '</tr>';
						}
						?>
					</table>

					<span><?=$cutting['cutting_jatturi']?></span>
					<div>
                        <span><?=number_format( ( $cutting['cutting_total_price'] + $cutting['cutting_opt_price'] ) * $cutting['cutting_qty'] + $cutting['cutting_price'] )?>원
                            <?php if ( $item['it_cut_opt'] == '1' || $item['it_cut_opt'] == '2' ) { ?>
                                <input type="hidden" name="ct_qty[<?=$itemId?>][]" value="1" alt="수량">
                            <?php } else { ?>
                                &nbsp;&nbsp;수량: <input type="text" name="ct_qty[<?=$itemId?>][]" value="<?=$cutting['cutting_qty']?>" class="frm_stock input_readonly" size="1" title="" readonly>
                            <?php } ?>
                        </span>

						<button type="button" class="sit_opt_delete btn_frmline" cutting_id="<?=$cutting['cutting_id']?>">삭제</button>
					</div>
				</li>

				<?php
			} //END while ( $cuttings = sql_fetch_array( $cuttings ) )

			?></ul><?php
		} //END if ( mysql_num_rows( $cuttings) )

		die(); //재단 목록 완료
	}
}