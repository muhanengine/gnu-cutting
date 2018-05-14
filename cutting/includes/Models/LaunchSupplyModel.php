<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 9:38
 */

namespace Cutting\Models;

use Cutting\Libs\Utils;

class LaunchSupplyModel
{
	/**
	 * @param string $key
	 * @return array|mixed|null
	 */
	public static function &getScheme( $key = '' )
	{
		static $scheme = null;

		if ( is_null( $scheme ) ) {

			$scheme = array();

			$scheme['Admin'] = array(
				'value'       => 'Admin',
				'description' => 'Install admin directory',
			);

			$scheme['Site'] = array(
				'value'       => 'Site',
				'description' => 'Install site directory',
			);

			$scheme['Front-end'] = array(
				'value'       => 'Front_end',
				'description' => 'Install front-end directory',
			);

			$scheme['Ajax'] = array(
				'value'       => 'Ajax',
				'description' => 'Install ajax directory',
			);

			$scheme['autoload_classmap'] = array(
				'value'       => 'autoload_classmap.php',
				'description' => 'Composer Autoload Load Class Map File Name',
			);
		}

		$result = '';
		if ( ! empty($key) ) {
			$result = Utils\_isset( $key, $scheme );
		}
		return $result;
	}

	/**
	 * Composer Directory
	 * @param string $dirName
	 * @return string
	 */
	public static function composerDirectory( $dirName )
	{
		$composerDir = $dirName .'/vendor/composer';
		return $composerDir;
	}

	/**
	 * Install admin directory
	 * @return string
	 */
	public static function adminDirectory()
	{
		return self::getValue( 'Admin' );
	}

	/**
	 * Install Front-end directory
	 * @return string
	 */
	public static function FrontEndDirectory()
	{
		return self::getValue( 'Front-end' );
	}

	/**
	 * @return string
	 */
	public static function siteDirectory()
	{
		return self::getValue( 'Site' );
	}

	/**
	 * @return string
	 */
	public static function ajaxDirectory()
	{
		return self::getValue( 'Ajax' );
	}

	/**
	 * Composer Autoload Load Class Map File
	 * @return string
	 */
	public static function autoloadClassMapFile()
	{
		return self::getValue( 'autoload_classmap' );
	}

	/**
	 * return scheme value
	 * @param string $key key name
	 * @return string
	 */
	public static function getValue( $key )
	{
		$data = &self::getScheme( $key );
		return Utils\_isset( 'value', $data );
	}
}