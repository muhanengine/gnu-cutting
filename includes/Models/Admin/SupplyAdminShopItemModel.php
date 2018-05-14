<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 28.
 * Time: PM 1:58
 */

namespace Cutting\Models\Admin\Shop;

class SupplyAdminShopItemModel {
	/**
	 * 상품설정 페이지에서 재단기능 추가필드
	 *
	 * @return array|mixed|null
	 */
	public static function &getScheme() {
		static $scheme = null;

		if ( is_null( $scheme ) ) {

			$scheme = array();

			$scheme['it_cut_opt'] = array(
				'value'       => 'it_cut_opt',
				'type'        => 'checkbox',
				'default'     => 0,
				'sql_type'    => 'tinyint',
				'sql_size'    => 4,
				'sql_after'   => 'it_use_avg',
				'title'       => '재단기능 사용',
				'description' => '',
			);
			$scheme['it_cut_jatturi_use'] = array(
				'value'       => 'it_cut_jatturi_use',
				'type'        => 'checkbox',
				'default'     => 0,
				'sql_type'    => 'tinyint',
				'sql_size'    => 4,
				'sql_after'   => '',
				'title'       => '재단기능 중 자투리 사용',
				'description' => '재단후 남은 재단(철재)를 구매자에게 배송',
			);
			$scheme['it_cut_amount'] = array(
				'value'       => 'it_cut_amount',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '컷팅비용',
				'description' => '',
			);
			$scheme['it_cut_amount_basic'] = array(
				'value'       => 'it_cut_amount_basic',
				'type'        => 'checkbox',
				'default'     => 0,
				'sql_type'    => 'tinyint',
				'sql_size'    => 4,
				'sql_after'   => '',
				'title'       => '컷팅비용 기본설정 사용 확인',
				'description' => '',
			);
			$scheme['it_cut_lose'] = array(
				'value'       => 'it_cut_lose',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '컷팅시 손실 길이(mm)',
				'description' => '컷팅시 손실되는 톱날두께',
			);
			$scheme['it_cut_lose_basic'] = array(
				'value'       => 'it_cut_lose_basic',
				'type'        => 'checkbox',
				'default'     => 0,
				'sql_type'    => 'tinyint',
				'sql_size'    => 4,
				'sql_after'   => '',
				'title'       => '컷팅시 손실 길이(mm) 기본설정 사용',
				'description' => '컷팅시 손실되는 톱날두께(mm) 기본설정 사용 확인',
			);
			$scheme['it_min_width'] = array(
				'value'       => 'it_min_width',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '최소구매 가로 길이(mm)',
				'description' => '',
			);
			$scheme['it_max_width'] = array(
				'value'       => 'it_max_width',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '최대가로 가로 길이(mm)',
				'description' => '',
			);
			$scheme['it_min_height'] = array(
				'value'       => 'it_min_height',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '최소구매 세로 길이(mm)',
				'description' => '',
			);
			$scheme['it_max_height'] = array(
				'value'       => 'it_max_height',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '최대구매 세로 길이(mm)',
				'description' => '',
			);
			$scheme['it_sale_unit'] = array(
				'value'       => 'it_sale_unit',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '재단 판매단위 길이, 기본판매단위 길이(mm) 가로, 세로 공통 사용',
				'description' => '',
			);
			$scheme['it_sale_amount'] = array(
				'value'       => 'it_sale_amount',
				'type'        => 'text',
				'default'     => 0,
				'sql_type'    => 'int',
				'sql_size'    => 11,
				'sql_after'   => '',
				'title'       => '재단 판매단위 길이 당 가격',
				'description' => '',
			);
		}

		return $scheme;
	}
}