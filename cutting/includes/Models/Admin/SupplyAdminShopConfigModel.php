<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 28.
 * Time: PM 1:58
 */

namespace Cutting\Models\Admin\Shop;

class SupplyAdminShopConfigModel {
	/**
	 * 쇼핑몰 설정 페이지에서 재단기능 추가필드
	 *
	 * @return array|mixed|null
	 */
	public static function &getScheme() {
		static $scheme = null;

		if ( is_null( $scheme ) ) {

			$scheme = array();

			$scheme['de_cut_amount'] = array(
				'value'       => 'de_cut_amount',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '기본 커팅비용',
				'description' => '',
			);
			$scheme['de_cut_lose'] = array(
				'value'       => 'de_cut_lose',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '기본 손실두께',
				'description' => '컷팅시 손실되는 톱날두께(mm)',
			);
		}

		return $scheme;
	}
}