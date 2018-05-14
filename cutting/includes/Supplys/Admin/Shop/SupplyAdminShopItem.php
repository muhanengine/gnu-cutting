<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: AM 11:43
 */
namespace Cutting\Supply\Admin\Shop;

use Cutting\Traits\CuttingTrait;
use Cutting\Models\Admin\Shop\SupplyAdminShopItemModel;
use function Cutting\Libs\Utils\postIsset;
use function Cutting\Libs\Utils\isStrCaseCmp;
use function Cutting\Libs\Functions\includeCssJs;
use function Cutting\Libs\Functions\getTemplatePart;

class SupplyAdminShopItem
{
	use CuttingTrait;
	
	function __construct() {}

	/**
	 * 쇼핑몰 설정 페이지 재단기능 추가시 필수 CSS, JS 파일
	 */
	public function initAssets()
	{
		includeCssJs( '/plugin/cutting/assets/css/ShopAdmin.css' );
		includeCssJs( '/plugin/cutting/assets/js/AdminCuttingItemForm.js' );
	}

	/**
	 * 재단기능 사용위해 추가 되는 아이템 테이블 추가필드
	 */
	public function installCuttingTable()
	{
		$cuttingFields = &SupplyAdminShopItemModel::getScheme();
		$checkField    = key($cuttingFields);

		if ( ! sql_query( /** @lang text */ " SELECT {$checkField} FROM {$GLOBALS['g5']['g5_shop_item_table']} LIMIT 1", false ) ) {
			$query = $this->getAlterTableAdd( $GLOBALS['g5']['g5_shop_item_table'], $cuttingFields );
			sql_query( $query, true );
		}
	}

	/**
	 * 상품관리 페이지에 재단기능(옵션) 입력폼
	 *
	 * @param array $it 상품정보
	 * @param array $default 쇼핑몰 설정
	 */
	public function initCuttingOptions( &$it, &$default )
	{
		$args = array();
		$args['it']      = $it;
		$args['default'] = $default;

		getTemplatePart( 'Admin/Shop/TemplateAdminShopCuttingOptions', $args );
	}

	/**
	 * 상품관리 업데이트에서 재단기능(옵션) 입력 데이터 저장 쿼리
	 */
	public function updateItemCuttingOptions()
	{
		$it_id         = postIsset( 'it_id' );
		$cuttingFields = &SupplyAdminShopItemModel::getScheme();
		$addQuery      = $this->getAddQuery( array_keys($cuttingFields), $_POST );

		$query = /** @lang text */" update {$GLOBALS['g5']['g5_shop_item_table']}
            set {$addQuery}
          where it_id = '{$it_id}' ";

		sql_query($query);
	}

	/**
	 * 관리자 상품등록(수정) 페이지 확인하기
	 */
	public function isItemForm()
	{
		$url = '/'. G5_ADMIN_DIR.'/shop_admin/itemform.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 관리자 상품업데이트 페이지 확인하기
	 */
	public function isItemFormUpdate()
	{
		$url = '/'. G5_ADMIN_DIR.'/shop_admin/itemformupdate.php';

		if ( isStrCaseCmp( $_SERVER['SCRIPT_NAME'], $url ) ) {
			return true;
		}

		return false;
	}
}
