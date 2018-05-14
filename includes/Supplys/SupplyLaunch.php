<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 27.
 * Time: AM 9:36
 */

namespace Cutting\Supply;

use Cutting\Models\LaunchSupplyModel;
use function Cutting\Libs\Utils\isStrCaseCmp;

class SupplyLaunch
{
	private $nameSpace;
	private $InstallDir;
	private $composerDir;
	private $adminDir;
	private $frontEndDir;
	private $siteDir;
	private $ajax;
	private $classMapFile;
	private static $autoLoadClass = null;

	public function __construct() {}

	/**
	 * @param string $dirName composer directory location
	 * @param string $nameSpace
	 * @param string $InstallDir Installs directory name
	 */
	public function install( $dirName, $nameSpace, $InstallDir = 'Launchs' )
	{
		$this->nameSpace    = $nameSpace;
		$this->InstallDir   = $InstallDir;
		$this->composerDir  = LaunchSupplyModel::composerDirectory( $dirName );
		$this->adminDir     = LaunchSupplyModel::adminDirectory();
		$this->frontEndDir  = LaunchSupplyModel::FrontEndDirectory();
		$this->siteDir      = LaunchSupplyModel::siteDirectory();
		$this->ajax         = LaunchSupplyModel::ajaxDirectory();
		$this->classMapFile = LaunchSupplyModel::autoloadClassMapFile();

		$this->addClasses();
		$this->installEntrance();
	}

	/**
	 * Install Admin
	 */
	private function installAdmin()
	{
		$this->runClasses( $this->adminDir );
	}

	/**
	 * Install Ajax
	 */
	private function installAjax()
	{
		$this->runClasses( $this->ajax );
	}

	/**
	 * Install Front-end
	 */
	private function installFrontEnd()
	{
		$this->runClasses( $this->frontEndDir );
	}

	/**
	 * Install site/admin All
	 */
	private function installSite()
	{
		$this->runClasses( $this->siteDir );
	}

	/**
	 * Install Page Check
	 */
	private function installEntrance()
	{
		if ( $this->urlAdminCheck() ) {
			$this->installAdmin();
		} else if( $this->urlAjaxCheck() ) {
			$this->installAjax();
		} else {
			$this->installFrontEnd();
		}

		$this->installSite();
	}

	/**
	 * @param $dir
	 */
	private function runClasses( $dir )
	{
		if ( isset( self::$autoLoadClass[ $dir ] ) && is_array( self::$autoLoadClass[ $dir ] ) ) {
			foreach ( self::$autoLoadClass[ $dir ] as $key => $Install ) {
				new $Install[0];
			}
		}
	}

	/**
	 * Install Classes
	 */
	private function addClasses()
	{
		if ( is_null( self::$autoLoadClass ) ) {
			self::$autoLoadClass = array();

			$classMapFile = $this->composerDir . '/' . $this->classMapFile;

			if ( file_exists( $classMapFile ) && is_file( $classMapFile ) ) {
				$nameSpace = $this->nameSpace . '\\' . $this->InstallDir;
				$classMap  = require $classMapFile;

				foreach ( $classMap as $key => $value ) {
					if ( strpos( $key, $nameSpace . '\\' . $this->adminDir . '\\' ) !== false ) {
						self::$autoLoadClass[ $this->adminDir ][] = array( $key, $value );
					} else if ( strpos( $key, $nameSpace . '\\' . $this->frontEndDir . '\\' ) !== false ) {
						self::$autoLoadClass[ $this->frontEndDir ][] = array( $key, $value );
					} else if ( strpos( $key, $nameSpace . '\\' . $this->ajax . '\\' ) !== false ) {
						self::$autoLoadClass[ $this->ajax ][] = array( $key, $value );
					} else if ( strpos( $key, $nameSpace . '\\' ) !== false ) {
						self::$autoLoadClass[ $this->siteDir ][] = array( $key, $value );
					}
				}

			}
		}
	}

	/**
	 * Ajax Page Check
	 *
	 * @return bool
	 */
	private function urlAjaxCheck()
	{
		$ajaxUrl = _DODAM_CUTTING_PATH_ . '/ajax.php';

		if ( isStrCaseCmp($_SERVER['SCRIPT_NAME'], $ajaxUrl) ) {
			return true;
		}

		return false;
	}

	/**
	 * Admin Page Check
	 *
	 * @return bool
	 */
	private function urlAdminCheck()
	{
		$isAdmin  = is_admin( $GLOBALS['member']['mb_id'] );
		$adminUrl = ( isset($_SERVER['HTTPS']) ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$adminUrl = substr( $adminUrl, 0, strlen(G5_ADMIN_URL) );

		if ( $isAdmin && isStrCaseCmp(G5_ADMIN_URL, $adminUrl) ) {
			return true;
		}

		return false;
	}
}