<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2018. 1. 3.
 * Time: PM 1:57
 */
namespace Cutting\Traits;

use Cutting\Models\Admin\Shop\SupplyAdminShopItemModel as ShopItemModel;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Functions\getNonce;

trait ShopItemSkinCuttingTrait
{
	/**
	 * 상품스킨에 재단기능 사용시 주문가능 값 변경 필요
	 * 주문버튼, 구매수량추가 안보이게 처리
	 *
	 * @param int $itemCutOption 재단 옵션 사용
	 * @param string $optionItem 선택 옵션
	 *
	 * @return bool
	 */
	function isShopItemOrderAble( $itemCutOption, $optionItem )
    {
		if ( $itemCutOption > 0 && ! $optionItem ) {
			// 옵션이 없는 경우
            $GLOBALS['is_orderable'] = false;
		}

		return $GLOBALS['is_orderable'];
	}

	/**
	 * 상품스킨에 재단기능 (목재, 판재) 추가 코드
	 *
	 * @param array $it 상품정보
	 * @param string $optionItem 선택 옵션
	 */
	function getShopItemCutting( &$it, $optionItem )
    {
		$itemCutOption = _isset( 'it_cut_opt', $it );

		if ( $itemCutOption > 0 ) {
			?>
            <div class="clear"></div>
            <!-- 재단 시작 { -->
            <section id="sit_cut_cutting">
                <h3>재단</h3>

                <input type="hidden" name="cut_price" id="cut_price" value=""/>
                <input type="hidden" name="opt_price" id="opt_price" value=""/>
                <input type="hidden" name="cut_option" id="cut_option" value=""/>
                <input type="hidden" name="ajax_action" id="cut_option" value="action_cart_cutting"/>
                <input type="hidden" name="nonce" value="<?= getNonce(); ?>"/>
                <table class="shop_table tb_cut_cutting cutting_options">

					<?php
					$no = 0;
					switch ( $it['it_cut_opt'] ) {
						case '1':
							?>
                            <!-- 길이 -->
                            <input type="hidden" name="it_cutting_total_price" value=""/>
                            <tr>
                                <th class="product-name">재단 길이</th>
                                <th class="product-name">수량</th>
                                <th class="product-name">재단비</th>
                                <th class="product-name add-cutting">+</th>
                            </tr>
                            <tr class="cart-subtotal fields-cutting">
                                <td><input type="number" class="cutting cutting_width" name="cutting_width[]" value=""
                                           title=""/></td>
                                <td><input type="number" class="cutting cutting_count" name="cutting_count[]" value=""
                                           title=""/>
                                </td>
                                <td><input type="text" class="cutting cutting_amount" name="cutting_amount[]" value=""
                                           title=""/></td>
                                <td><span class="delete-cutting" cutting="<?php echo $no; ?>">X</span></td>
                            </tr>
							<?php
							break;

						case '2':
							?>
                            <!-- 판재 -->
                            <input type="hidden" name="it_cutting_total_price" value=""/>
                            <tr>
                                <th class="product-name">재단길이(가로)</th>
                                <th class="product-name">재단길이(세로)</th>
                                <th class="product-name">수량</th>
                                <th class="product-name">재단비</th>
                                <th class="product-name add-cutting">+</th>
                            </tr>
                            <tr class="cart-subtotal fields-cutting board">
                                <td><input type="number" class="cutting cutting_width" name="cutting_width[]" value=""
                                           title=""/>
                                </td>
                                <td><input type="number" class="cutting cutting_height" name="cutting_height[]" value=""
                                           title=""/></td>
                                <td><input type="number" class="cutting cutting_count" name="cutting_count[]" value=""
                                           title=""/>
                                </td>
                                <td><input type="text" class="cutting cutting_amount" name="cutting_amount[]" value=""
                                           title=""/></td>
                                <td><span class="delete-cutting" cutting="<?php echo $no; ?>">X</span></td>
                            </tr>
							<?php
							break;

						case '3':
							?>
                            <!-- 자당 -->
                            <input type="hidden" name="it_cutting_total_price" value=""/>
                            <tr>
                                <th class="product-name">재단 길이</th>
                                <th class="product-name">수량</th>
                                <th class="product-name">재단비</th>
                                <th class="product-name add-cutting">+</th>
                            </tr>
                            <tr class="cart-subtotal fields-cutting">
                                <td><input type="number" class="cutting cutting_width" name="cutting_width[]" value=""
                                           title=""/></td>
                                <td><input type="number" class="cutting cutting_count" name="cutting_count[]" value=""
                                           title=""/>
                                </td>
                                <td><input type="text" class="cutting cutting_amount" name="cutting_amount[]" value=""
                                           title=""/></td>
                                <td><span class="delete-cutting" cutting="<?php echo $no; ?>">X</span></td>
                            </tr>

							<?php
							break;

						case '4':
							?>
                            <!-- 맞춤사이즈 -->
							<?php
							break;
					}
					?>

                </table>

                <!-- 재단설명 -->
                <div id="cutting_status" class="cutting_options">
                    가로길이 최소(<?= $it['it_min_width'] ?>mm) ~ 최대(<?= number_format( $it['it_max_width'] ) ?>mm)
					<?php if ( $it['it_cut_opt'] == 2 ) { ?>
                        <br/>세로길이 최소(<?= $it['it_min_height'] ?>mm) ~ 최대(<?= number_format( $it['it_max_height'] ) ?>mm)
					<?php } ?>

					<?php if ( $it['it_sale_amount'] || $it['it_sale_unit'] ) { ?>
                        <br/>가로<?php if ( $it['it_cut_opt'] == 2 ) { ?>, 세로<?php } ?>길이 <?= number_format( $it['it_sale_unit'] ) ?>mm / <?= number_format( $it['it_sale_amount'] ) ?>원
					<?php } ?>
                </div>

                <!-- 자투리 -->
                <div id="cut_cutting_jatturi" class="cutting_options"></div>
                <textarea name="cut_jatturi" class="cut_jatturi" style="display:none;" title=""></textarea>
				<?php
				echo '<div class="cutting_options opt_result">
                    <div class="sit_opt_price"></div>
                    <div class="sit_opt_qty">&nbsp;&nbsp;수량: <input type="text" name="cutting_qty" value="0" class="cutting_qty frm_input frm_stock" size="5" readonly></div>
                    <div class="btn_add01">
                        <a href="javascript:;" class="button-cutting">재단 저장하기</a>
                    </div>
                </div>';

				if ( $optionItem ) { // 옵션이 없는 경우
					echo '<script>
                        (function($) {
                            $(document).ready(function() {
                                $(".cutting_options").css("display", "none");
                                $("#sit_cut_cutting").css("display", "none");
                            });
                        })(jQuery);                            
                    </script>';
				}
				?>
            </section>

            <script>
                var it_option_item;
                var cut_lose;
                var cut_amount;
                var cutting_url;

				<?php
				$cuttingFields = &ShopItemModel::getScheme();
				foreach ( $cuttingFields as $key => $data ) {
					$value = _isset( $key, $it );
					echo 'var ' . $key . PHP_EOL;
					echo $key . ' = parseInt("' . $value . '");' . PHP_EOL;
				}
				?>

                it_option_item = "<?php echo( $optionItem ? '1' : '0' ); ?>";     // 옵션유무
                cut_lose       = parseInt('<?php echo $GLOBALS['default']['de_cut_lose']; ?>');     // 1회 컷팅시 톱날에 의해 사라지는 길이
                cut_amount     = parseInt('<?php echo $GLOBALS['default']['de_cut_amount']; ?>'); // 기본컷팅비용
                cutting_url    = "<?php echo _DODAM_CUTTING_URL_ . '/ajax.php'; ?>";        // 플러그인 주소

                (function ($) {
                    $(document).ready(function () {
                        $(".it_option").addClass("it_cut_option");
                        $(".it_cut_option").removeClass("it_option");
                    });
                })(jQuery);
            </script>

            <div class="list-cutting">
            <!-- 목재재단 목록 -->
        </div>

            <!-- } 재단 끝 -->
			<?php
		}
	}
}