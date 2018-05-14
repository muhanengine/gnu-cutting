<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Front_end\Shop;

use Cutting\Traits\ShopItemTrait;
use Cutting\Supply\Front_end\Shop\SupplyFrontShopItem;
use function Cutting\Libs\Utils\getIsset;
use function Cutting\Libs\Functions\addAction;

class LaunchFrontShopItem
{
	use ShopItemTrait;
	
	/** @var $shopItem SupplyFrontShopItem */
	private $shopItem;
	private $itemId;

	function __construct()
	{
			$this->itemId = getIsset( 'it_id', 'intval' );
			$this->init();
	}

	public function init()
	{
		if ( $this->itemId ) {
			$this->shopItem = new SupplyFrontShopItem();
			addAction( '/item.form.skin.php', array($this, 'itemFormSkin') );

			if ( _DODAM_CUTTING_USE_ === true ) {
				addAction( 'item_form_add_cutting_skin', array( $this, 'addCuttingForm' ) );
				addAction( 'item_form_add_submit_button_price', array( $this, 'addButtonPrice' ) );
			}
		}
	}

	/**
	 * 상품정보 스킨 플러그인의 스킨으로 변경하기
	 *
	 * @param $skinDir
	 */
	public function itemFormSkin( $skinDir )
	{
		$it = &$this->getItem( $this->itemId );

		$this->shopItem->initAssets( $it );
		$this->shopItem->changeItemFormSkin( $it, $skinDir );
	}

	/**
	 * 상품상세 페이지에 재단기능 추가
	 *
	 * @param $it
	 * @param $optionItem
	 *
	 * @return bool
	 */
	public function addCuttingForm( $it, $optionItem )
	{
		return $this->shopItem->addCuttingFormSkin( $it, $optionItem );
	}

	/**
	 * 상품상세 페이지에 버튼 및 재단옵션 구매 총액 출력하기
	 *
	 * @param $isOrderAble
	 */
	public function addButtonPrice( $isOrderAble )
	{
		$this->shopItem->addButtonTotalPrice( $isOrderAble );
	}
}