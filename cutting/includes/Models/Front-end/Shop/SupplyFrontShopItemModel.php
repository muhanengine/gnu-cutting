<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 28.
 * Time: PM 1:58
 */
namespace Cutting\Models\Front_end\Shop;

class SupplyFrontShopItemModel
{
	/**
	 * 상품보기 페이지에서 재단기능 추가에 필요한 데이터
	 *
	 * @return array|mixed|null
	 */
	public static function &getScheme()
	{
		static $scheme = null;

		if ( is_null( $scheme ) ) {

			$scheme = array();

			$scheme['action_url'] = array(
				'value'       => 'action_url',
				'description' => '보안서버경로',
			);

			$scheme['prev_title'] = array(
				'value'       => 'prev_title',
				'description' => '이전 상품보기 제목',
			);

			$scheme['prev_href'] = array(
				'value'       => 'prev_href',
				'description' => '이전 상품보기 연결주소',
			);

			$scheme['prev_href2'] = array(
				'value'       => 'prev_href2',
				'description' => '이전 상품보기 연결주소',
			);

			$scheme['next_title'] = array(
				'value'       => 'next_title',
				'description' => '다음 상품보기 제목',
			);

			$scheme['next_href'] = array(
				'value'       => 'next_href',
				'description' => '다음 상품보기 연결주소',
			);

			$scheme['next_href2'] = array(
				'value'       => 'next_href2',
				'description' => '다음 상품보기 연결주소',
			);

			$scheme['star_score'] = array(
				'value'       => 'star_score',
				'description' => '고객선호도 별점수',
			);

			$scheme['item_use_count'] = array(
				'value'       => 'item_use_count',
				'description' => '관리자가 확인한 사용후기의 개수',
			);

			$scheme['item_qa_count'] = array(
				'value'       => 'item_qa_count',
				'description' => '상품문의의 개수',
			);

			$scheme['item_relation_count'] = array(
				'value'       => 'item_relation_count',
				'description' => '관련상품의 개수',
			);

			$scheme['sns_title'] = array(
				'value'       => 'sns_title',
				'description' => '소셜 관련 제목',
			);

			$scheme['sns_url'] = array(
				'value'       => 'sns_url',
				'description' => '소셜 관련 연결 주소',
			);

			$scheme['sns_share_links'] = array(
				'value'       => 'sns_share_links',
				'description' => '소셜 관련 공유 주소',
			);

			$scheme['is_soldout'] = array(
				'value'       => 'is_soldout',
				'description' => '상품품절체크',
			);

			$scheme['is_orderable'] = array(
				'value'       => 'is_orderable',
				'description' => '주문가능체크',
			);

			$scheme['option_item'] = array(
				'value'       => 'option_item',
				'description' => '선택 옵션',
			);

			$scheme['supply_item'] = array(
				'value'       => 'supply_item',
				'description' => '추가 옵션',
			);

			$scheme['option_count'] = array(
				'value'       => 'option_count',
				'description' => '상품 선택옵션 수',
			);

			$scheme['supply_count'] = array(
				'value'       => 'supply_count',
				'description' => '상품 추가옵션 수',
			);
		}

		return $scheme;
	}
}