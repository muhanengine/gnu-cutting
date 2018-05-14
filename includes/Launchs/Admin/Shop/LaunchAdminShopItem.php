<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Admin\Shop;

use Cutting\Supply\Admin\Shop\SupplyAdminShopItem;
use Cutting\Traits\ShopItemTrait;
use function Cutting\Libs\Utils\getIsset;
use function Cutting\Libs\Functions\addAction;

class LaunchAdminShopItem
{
	use ShopItemTrait;
	
	/** @var $shopItem SupplyAdminShopItem */
	private $shopItem;
	private $it_id;
	private $isItemForm;
	private $isItemUpdate;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->shopItem = new SupplyAdminShopItem();
			$this->it_id    = getIsset( 'it_id', 'intval' );

			$this->isItemForm   = $this->shopItem->isItemForm();
			$this->isItemUpdate = $this->shopItem->isItemFormUpdate();

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->isItemForm ) {
			$this->shopItem->installCuttingTable();
			addAction( 'add_item_cutting_options', array($this, 'addCuttingOptions') );
		}

		if ( $this->isItemUpdate ) {
			addAction( 'update_item_cutting_options', array($this, 'updateCuttingOptions') );
		}
	}

	/**
	 * 상품설정 페이지에 재단옵션 추가
	 *
	 */
	public function addCuttingOptions()
	{
		/** @var array $it */
		$it = &$this->getItem( $this->it_id );

		$this->shopItem->initAssets();
		$this->shopItem->initCuttingOptions( $it, $GLOBALS['default'] );
	}

	/**
	 * 상품관리 업데이트에서 재단기능(옵션) 입력 데이터 저장 SQL
	 *
	 */
	public function updateCuttingOptions()
	{
		$this->shopItem->updateItemCuttingOptions();
	}
}