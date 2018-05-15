<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Ajax\Shop;

use Cutting\Supply\Ajax\Shop\SupplyAjaxShopItem;
use function Cutting\Libs\Utils\postIsset;
use function Cutting\Libs\Functions\addAction;
use function Cutting\Libs\Functions\checkNonce;

class LaunchAjaxShopItem
{
	/** @var $shopAjaxItem SupplyAjaxShopItem */
	private $shopAjaxItem;
	private $ajaxAction;
	private $nonce;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->ajaxAction = postIsset( 'ajax_action' );
			$this->nonce      = postIsset( 'nonce' );

			$this->init();
		}
	}

	public function init()
	{
		if ( checkNonce($this->nonce) && $this->ajaxAction == 'action_cart_cutting' ) {
			$action = postIsset( 'action' );

			$this->shopAjaxItem = new SupplyAjaxShopItem();

			addAction( _DODAM_CUTTING_AJAX_ . $action, array($this->shopAjaxItem, $action) );
		}
	}
}