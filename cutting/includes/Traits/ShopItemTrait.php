<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:27
 */

namespace Cutting\Traits;

trait ShopItemTrait
{
	/**
	 * 상품정보
	 *
	 * @param $itemId
	 *
	 * @return bool
	 */
	public function &getItem( $itemId )
	{
		$itemId = (int) $itemId;
		$query  = /** @lang text */
			" SELECT a.*, b.ca_name, b.ca_use 
			FROM {$GLOBALS['g5']['g5_shop_item_table']} a, {$GLOBALS['g5']['g5_shop_category_table']} b 
			WHERE a.it_id = '{$itemId}' AND a.ca_id = b.ca_id ";
		$item   = sql_fetch( $query );

		return $item;
	}

	/**
	 * 상품 옵션정보 불러오기
	 *
	 * @param $itemId
	 * @param $optionId
	 *
	 * @return array|null
	 */
	public function &getItemOption( $itemId, $optionId )
	{
		$query  = /** @lang text */
			" SELECT * 
	        FROM {$GLOBALS['g5']['g5_shop_item_option_table']} 
	        WHERE it_id = '{$itemId}' 
	            AND io_type = '0' 
	            AND io_id = '{$optionId}' ";
		$option = sql_fetch( $query );

		return $option;
	}
}