<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2018. 1. 4.
 * Time: AM 10:03
 */
namespace Cutting\Supply\Admin;

class SupplyAdminInit
{
	/**
	 * 재단기능 설정시 추가 테이블 및 장바구니 추가 컬럼
	 */
	public function createCuttingTableAlterCart()
	{
		$query = /** @lang text */ " SHOW TABLES LIKE '". _DODAM_CUTTING_TABLE_ ."' ";
		if ( ! sql_fetch($query) ) {
			// 재단정보 저장 테이블
			$query = /** @lang text */
				" CREATE TABLE IF NOT EXISTS `" . _DODAM_CUTTING_TABLE_ . "` (
				`cutting_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '재단 번호',
				`ct_id` int(11) NOT NULL DEFAULT '0' COMMENT '장바구니 번호',
				`od_id` bigint(20) unsigned NOT NULL COMMENT '주문번호',
				`it_id` varchar(20) NOT NULL DEFAULT '' COMMENT '상품 번호',
				`mb_id` varchar(255) NOT NULL DEFAULT '' COMMENT '회원 아이디',
				`cutting_use` tinyint(4) NOT NULL DEFAULT '0' COMMENT '재단 사용 유무',
				`cutting_list` text NOT NULL COMMENT '재단 목록',
				`cutting_option` varchar(255) NOT NULL DEFAULT '' COMMENT '재단 옵션',
				`cutting_qty` int(11) NOT NULL DEFAULT '0' COMMENT '재단(상품) 갯수',
				`cutting_jatturi` text NOT NULL COMMENT '재단후 남은 나머지 목록',
				`cutting_price` int(11) NOT NULL DEFAULT '0' COMMENT '재단(컷팅) 총 가격',
				`cutting_total_price` int(11) NOT NULL DEFAULT '0' COMMENT '재단 총가격',
				`cutting_opt_price` int(11) NOT NULL DEFAULT '0' COMMENT '옵션 가격',
				`cutting_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '저장시간',
				PRIMARY KEY (`cutting_id`),
				KEY `ct_id` (`ct_id`),
				KEY `od_id` (`od_id`),
				KEY `it_id` (`it_id`),
				KEY `mb_id_use` (`mb_id`, `cutting_use`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
			sql_query( $query, true );

			// 장바구니 테이블 추가 컬럼
			$query = /** @lang text */
				" ALTER TABLE `{$GLOBALS['g5']['g5_shop_cart_table']}`
			        ADD COLUMN `ct_cutmemo` text NOT NULL COMMENT '추가 재단메모' AFTER `ct_notax`,
			        ADD COLUMN `ct_cutting_use` tinyint(4) NOT NULL DEFAULT '0' COMMENT '재단 사용 유무' AFTER `ct_cutmemo`,
			        ADD COLUMN `ct_cut_price` int(11) NOT NULL DEFAULT '0' COMMENT '재단 총가격' AFTER `ct_cutting_use` ";
			sql_query( $query, true );
		}
	}
}