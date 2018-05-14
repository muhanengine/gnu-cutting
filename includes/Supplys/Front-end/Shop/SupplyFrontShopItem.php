<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 2:45
 */
namespace Cutting\Supply\Front_end\Shop;

use Cutting\Traits\ShopItemSkinCuttingTrait;
use Cutting\Models\Front_end\Shop\SupplyFrontShopItemModel as ShopItemModel;
use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Functions\includeCssJs;
use function Cutting\Libs\Functions\getTemplatePart;

class SupplyFrontShopItem
{
	use ShopItemSkinCuttingTrait;

	function __construct() {}

	/**
	 * 상품상세 페이지 재단기능 추가시 필수 CSS, JS 파일
	 *
	 * @param $it
	 */
	public function initAssets( &$it )
	{
		$itemCutOpt = _isset( 'it_cut_opt', $it );

		if ( G5_IS_MOBILE ) {
			includeCssJs( '/plugin/cutting/assets/css/ShopMobile.css' );
		} else {
			includeCssJs( '/plugin/cutting/assets/css/Shop.css' );
		}

		if ( $itemCutOpt > 0 ) {
			includeCssJs( '/plugin/cutting/assets/css/Cutting.css' );
			includeCssJs( '/plugin/cutting/assets/js/CuttingItem.js' );
		}
	}

	/**
	 * 상품상세 페이지 스킨 플러그인 안에 스킨으로 변경하기
	 *
	 * @param $it
	 * @param $skinDir
	 */
	public function changeItemFormSkin( &$it, $skinDir )
	{
		$args = &self::itemParameters();
		$args['it'] = $it;
		$args['it_id'] = $it['it_id'];
		$args['default'] = $GLOBALS['default'];

		$templatePath = '';

		if ( G5_IS_MOBILE ) {
			$templateName = 'Front-end/Mobile/Shop/item.form.skin';
		} else {
			$templateName = 'Front-end/Shop/item.form.skin';
		}

		if ( _DODAM_CUTTING_USE_ === false ) {
			$templateName = '/item.form.skin';
			$templatePath = $skinDir;
		}

		getTemplatePart( $templateName, $args, $templatePath );
	}

	/**
	 * 상품상세 페이지에 재단기능 코드 및 주문가능 값 변경
	 *
	 * @param $it
	 * @param $optionItem
	 *
	 * @return bool
	 */
	public function addCuttingFormSkin( &$it, $optionItem )
	{
		$itemCutOpt = _isset( 'it_cut_opt', $it );

		$this->getShopItemCutting( $it, $optionItem  );
		return $this->isShopItemOrderAble( $itemCutOpt, $optionItem );
	}
	/**
	 * 상품상세 페이지에 구매버튼, 장바구니 버튼 및 총 구매 금액 추가하기
	 *
	 * @param bool $isOrderAble
	 */
	public function addButtonTotalPrice( $isOrderAble )
	{
		if ( ! $isOrderAble ) {
			echo '<div id="sit_tot_price"></div>';
		}
        echo '<button type="submit" onclick="document.pressed=this.value;" value="바로구매" id="sit_btn_buy">
				<i class="fa fa-credit-card" aria-hidden="true"></i> 바로구매
			</button>
            <button type="submit" onclick="document.pressed=this.value;" value="장바구니" id="sit_btn_cart">
            	<i class="fa fa-shopping-cart" aria-hidden="true"></i> 장바구니
            </button>';
	}


	/**
	 * 상품페이지에서 사용하는 출력 데이터
	 */
	public static function &itemParameters()
	{
		static $args = null;
		$vars = &ShopItemModel::getScheme();

		if ( is_null($args) ) {
			$args = array();

			foreach ( $vars as $key => $val ) {
				$args[ $key ] = $GLOBALS[ $key ];
			}
		}

		return $args;
	}
}