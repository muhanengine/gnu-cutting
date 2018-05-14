<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 10:59
 */

namespace Cutting\Launchs\Admin\Shop;

use Cutting\Supply\Admin\Shop\SupplyAdminShopConfig;
use function Cutting\Libs\Utils\postIsset;
use function Cutting\Libs\Functions\addAction;

class LaunchAdminShopConfig
{
	/** @var $shopConfig SupplyAdminShopConfig */
	private $shopConfig;
	private $company_owner;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->shopConfig    = new SupplyAdminShopConfig();
			$this->company_owner = postIsset( 'de_admin_company_owner' );

			$this->init();
		}
	}

	public function init()
	{
		if ( $this->shopConfig->isConfigPage() ) {
			$this->shopConfig->installCuttingTable( $GLOBALS['default'] );
			addAction( 'add_cutting_config', array($this, 'addCuttingConfig') );
		}

		if ( $this->company_owner ) {
			$this->shopConfig->updateCuttingConfig();
		}
	}

	/**
	 * 쇼핑몰설정 페이지에 재단옵션 추가
	 *
	 */
	public function addCuttingConfig()
	{
		$this->shopConfig->initAssets();
		$this->shopConfig->initCuttingConfig( $GLOBALS['default'] );
	}
}