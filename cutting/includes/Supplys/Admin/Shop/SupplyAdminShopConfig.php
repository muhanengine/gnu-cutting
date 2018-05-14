<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: AM 11:43
 */
namespace Cutting\Supply\Admin\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Models\Admin\Shop\SupplyAdminShopConfigModel;
use function Cutting\Libs\Utils\isStrCaseCmp;
use function Cutting\Libs\Functions\includeCssJs;
use function Cutting\Libs\Functions\getTemplatePart;

class SupplyAdminShopConfig
{
	use CuttingTrait;
	
	function __construct() {}

	/**
	 * 쇼핑몰 설정 페이지 재단기능 추가시 필수 CSS, JS 파일
	 */
	public function initAssets()
	{
		includeCssJs( '/plugin/cutting/assets/css/ShopAdmin.css' );
	}

	/**
	 * 재단기능 사용위해 추가 되는 아이템 테이블 추가필드
	 *
	 * @param $default
	 */
	public function installCuttingTable( &$default )
	{
		$configFields = &SupplyAdminShopConfigModel::getScheme();
		$checkField   = key($configFields);

		if ( ! isset($default[$checkField]) ) {
			$query = $this->getAlterTableAdd( $GLOBALS['g5']['g5_shop_default_table'], $configFields );
			sql_query( $query, true);
		}
	}

	/**
	 * 상품관리 페이지에 재단기능(옵션) 입력폼
	 *
	 * @param $default
	 */
	public function initCuttingConfig( &$default )
	{
		$args = array();
		$args['default'] = $default;
		getTemplatePart( 'Admin/Shop/TemplateAdminShopCuttingConfig', $args );
	}

	/**
	 * 상품관리 업데이트에서 재단기능(옵션) 입력 데이터 저장 쿼리
	 */
	public function updateCuttingConfig()
	{
		$options  = SupplyAdminShopConfigModel::getScheme();
		$addQuery = $this->getAddQuery( array_keys($options), $_POST );

		$query = /** @lang text */" update {$GLOBALS['g5']['g5_shop_default_table']}
            set ". $addQuery;
		sql_query( $query );
	}

	/**
	 * 쇼핑몰 설정 페이지 확인하기
	 */
	public function isConfigPage()
	{
		$url = '/'. G5_ADMIN_DIR .'/shop_admin/configform.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url) ) {
			return true;
		}

		return false;
	}
}
