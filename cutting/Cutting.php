<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:37
 */

# Busted!
! defined( '_GNUBOARD_' ) AND exit(
	// Incorrect connection
);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require_once __DIR__ . '/vendor/autoload.php';

final class Cutting
{
	/** var $initiated */
	private static $initiated = null;

	public function __construct()
	{
		if ( is_null(self::$initiated) ) {
			$this->init();
		}
	}

	/**
	 * this init
	 */
	public function init()
	{
		self::$initiated = true;

		$Install = new Cutting\Supply\SupplyLaunch();
		$Install->install( __DIR__, __CLASS__ );
	}
}

new Cutting();