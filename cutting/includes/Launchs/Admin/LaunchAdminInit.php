<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2018. 1. 4.
 * Time: AM 10:02
 */
namespace Cutting\Launchs\Admin;

use Cutting\Supply\Admin\SupplyAdminInit;

class LaunchAdminInit
{
	/** @var $adminInit SupplyAdminInit */
	private $adminInit;

	function __construct()
	{
		if ( _DODAM_CUTTING_USE_ === true ) {
			$this->adminInit = new SupplyAdminInit();
			$this->installCutting();
		}
	}

	public function installCutting()
	{
		$this->adminInit->createCuttingTableAlterCart();
	}
}