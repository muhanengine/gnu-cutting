<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:27
 */

namespace Cutting\Traits;

use function Cutting\Libs\Utils\_isset;
use function Cutting\Libs\Utils\isIntVal;
use function Cutting\Libs\ShopFunctions\getUniqueCartOrderId;

trait CuttingTrait
{
	/**
	 * 주문 또는 장바구니 담기시 재단 정보 연결위해
	 * order id, cutting_use(주문시 1) 업데이트
	 * @example shop/cartupdate.php, orderformupdate.php
	 *
	 * @param string $cartId 장바구니 번호
	 * @param string $orderId 주문번호
	 * @param string $itemId 상품번호
	 * @param string $cartOrder
	 */
	public function setCuttingCartOrder( $cartId, $orderId = '', $itemId = '', $cartOrder = 'cart' )
	{
		$query    = '';
		$whereAnd = '';

		if ( $itemId ) {
			$whereAnd = " AND it_id = '{$itemId}' ";
		}

		switch ( $cartOrder ) {
			case 'cart':
				if ( $GLOBALS['member']['mb_id'] ) {
					$query = /** @lang text */
						" UPDATE " . _DODAM_CUTTING_TABLE_ . " SET od_id = '{$cartId}' 
				  WHERE mb_id = '{$GLOBALS['member']['mb_id']}' AND cutting_use = '0' " . $whereAnd;
				}
				break;
			case 'order':
				$query = /** @lang text */
					" UPDATE " . _DODAM_CUTTING_TABLE_ . " SET od_id = '{$orderId}', cutting_use = '1' 
				  WHERE od_id = '{$cartId}' " . $whereAnd; /*mb_id = '{$GLOBALS['member']['mb_id']}' AND*/
				break;
		}

		sql_query( $query );
	}

	/**
	 * 주문금액에 재단 비용 추가하기
	 * @example shop/orderformupdate.php
	 *
	 * @param $cartId
	 *
	 * @return array|null
	 */
	public function getOrderPriceAddCutting( $cartId )
	{
		// 장바구니 합계
		$sql = /** @lang text */
			" SELECT SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) AS od_price,
            COUNT(distinct it_id) AS cart_count
          FROM {$GLOBALS['g5']['g5_shop_cart_table']} WHERE od_id = '{$cartId}' AND ct_select = '1' ";
		$row = sql_fetch( $sql );

		// 재단 컷팅비 더하기
		$sql  = /** @lang text */
			" SELECT SUM( cutting_total_price ) AS cut_price FROM " . _DODAM_CUTTING_TABLE_ . " 
		  WHERE od_id = '{$cartId}' AND cutting_use = '1' ";
		$wood = sql_fetch( $sql );

		$row['od_price'] += $wood['cut_price'];

		return $row;
	}

	/**
	 * 재단 총 직각컷팅비용
	 *
	 * @param $cartId
	 * @param string $itemId 상품번호
	 * @param int $cartSelect
	 * @param int $cuttingUse 테이블 주문이전-0, 주문이후-1
	 *
	 * @return mixed
	 */
	public function getTotalCutPrice( $cartId, $itemId = '', $cartSelect = 0, $cuttingUse = 0 )
	{
		$cut['price'] = 0;

		$query = /** @lang text */
			" SELECT (cutting_total_price + cutting_opt_price) * cutting_qty + cutting_price as total_price
            FROM " . _DODAM_CUTTING_TABLE_ . " slt LEFT JOIN {$GLOBALS['g5']['g5_shop_cart_table']} sct 
                ON ( slt.it_id = sct.it_id AND slt.od_id = sct.od_id )
            WHERE slt.od_id = '{$cartId}' 
              AND slt.cutting_use = '{$cuttingUse}'  
              AND slt.mb_id = sct.mb_id
              AND slt.mb_id = '{$GLOBALS['member']['mb_id']}'
              AND sct.ct_select = '{$cartSelect}' ";
		if ( $itemId ) {
			$query .= " AND slt.it_id = '{$itemId}' ";
		}

		$query .= " GROUP BY slt.cutting_id ";

		$res = sql_query( $query );
		while ( $row = sql_fetch_array( $res ) ) {
			$cut['price'] += $row['total_price'];
		}

		return $cut;
	}

	/**
	 * 재단 총 직각컷팅비용
	 *
	 * @param $cartId
	 * @param string $itemId 상품번호
	 * @param string $itemOptionId 상품옵션
	 * @param int $cuttingUse 테이블 주문이전-0, 주문이후-1
	 *
	 * @return int
	 */
	public function getCartCutPrice( $cartId, $itemId = '', $itemOptionId = '', $cuttingUse = 0 )
	{
		$cut['cutting_price']       = 0;
		$cut['total_cutting_price'] = 0;

		$sql = /** @lang text */
			" SELECT cutting_price, cutting_total_price, cutting_opt_price, cutting_qty
				, (cutting_total_price + cutting_opt_price) * cutting_qty + cutting_price as total_price
            FROM " . _DODAM_CUTTING_TABLE_ . "
            WHERE od_id = '{$cartId}' 
              AND cutting_use = '{$cuttingUse}'  
              AND mb_id = '{$GLOBALS['member']['mb_id']}' ";

		if ( $itemId ) {
			$sql .= " AND it_id = '{$itemId}' ";
		}

		if ( $itemOptionId ) {
			$sql .= " AND cutting_option = '{$itemOptionId}' ";
		}

		$sql .= " GROUP BY cutting_id ";

		$res = sql_query( $sql );
		while ( $row = sql_fetch_array( $res ) ) {
			$cuttingPrice = _isset( 'cutting_price', $row );
			$totalPrice   = _isset( 'total_price', $row );

			$cut['cutting_price']       += (int) $cuttingPrice;
			$cut['total_cutting_price'] += (int) $totalPrice;
		}

		return $cut;
	}

	/**
	 * DB에 저장된 재단 table 형태로 출력
	 *
	 * @param array $cutting 재단 배열
	 * @param array $jatturi 재단 자투리
	 * @param array $css table, tr, th, td style sheet
	 *
	 * @return string
	 */
	public function getCuttingTable( $cutting, $jatturi, &$css = array() )
	{
		$divStyle   = _isset( 'div', $css );
		$tableStyle = _isset( 'table', $css );
		$trStyle    = _isset( 'tr', $css );
		$thStyle    = _isset( 'th', $css );
		$tdStyle    = _isset( 'td', $css );
		$spanStyle  = _isset( 'span', $css );

		$cutTable = '';
		$cutTable .= '<div class="it_cut_list" style="' . $divStyle . '">' . PHP_EOL;
		$cutTable .= '<table style="' . $tableStyle . '">' . PHP_EOL;
		$cutTable .= '<tr style="' . $trStyle . '">' . PHP_EOL;

		foreach ( $cutting[0] as $key => $value ) {
			$cutTable .= '<th class="title-name" style="' . $thStyle . '">' . $value . '</th>' . PHP_EOL;
		}

		$cutTable .= '</tr>' . PHP_EOL;

		for ( $c = 1; $c < count( $cutting ); $c ++ ) {
			$cutTable .= '<tr style="' . $trStyle . '">' . PHP_EOL;
			foreach ( $cutting[ $c ] as $key => $value ) {
				$cutTable .= '<td style="' . $tdStyle . '">' . isIntVal( $value ) . '</td>' . PHP_EOL;
			}
			$cutTable .= '</tr>' . PHP_EOL;
		}

		$cutTable .= '</table>' . PHP_EOL;
		$cutTable .= '<span style="' . $spanStyle . '">' . $jatturi . '</span>' . PHP_EOL;
		$cutTable .= '</div>' . PHP_EOL;

		return $cutTable;
	}

	/**
	 * 재단 정보 출력 (미사용:삭제가능)
	 *
	 * @param $cartId
	 * @param string $itemId 상품번호
	 * @param string $option
	 * @param int $cuttingUse 0-장바구니, 1-주문
	 * @param string $mb_id
	 *
	 * @return array
	 */
	public function getLumbers( $cartId, $itemId = '', $option = '', $cuttingUse = 0,
		/** @noinspection PhpUnusedParameterInspection */ $mb_id = '' )
	{
		$lumbers = array();
		$query   = /** @lang text */
			" SELECT * FROM " . _DODAM_CUTTING_TABLE_ . " 
			WHERE od_id = '{$cartId}' AND cutting_use = '{$cuttingUse}' ";

		/*if ( $mb_id && $cart_id ) {
			$query .= " AND mb_id = '{$mb_id}' ";
		}*/

		if ( $itemId ) {
			$query .= " AND it_id = '{$itemId}' ";
		}

		if ( $option ) {
			$tmpOption = explode( ':', $option );
			$query     .= " AND cutting_option = '{$tmpOption[1]}' ";
		}

		$res = sql_query( $query );
		for ( $c = 0; $row = sql_fetch_array( $res ); $c ++ ) {
			foreach ( $row as $key => $value ) {
				$lumbers[ $c ][ $key ] = $value;
			}

			$lumbers[ $c ]['cutting_list'] = unserialize( $lumbers[ $c ]['cutting_list'] );
		}

		return $lumbers;
	}

	/**
	 * 재단테이블의 상품별 총합 금액 출력
	 *
	 * @param int $cartId 장바구니 번호
	 *
	 * @return int
	 */
	public function getCuttingTotalPrice( $cartId )
	{
		$query = /** @lang text */
			" SELECT od_id, it_id, io_id
			FROM {$GLOBALS['g5']['g5_shop_cart_table']}
			WHERE ct_id = '{$cartId}' ";
		/** @var array $row */
		$row = sql_fetch( $query );

		$orderId      = _isset( 'od_id', $row );
		$itemId       = _isset( 'it_id', $row );
		$itemOptionId = _isset( 'io_id', $row );

		return $this->getCartTotalPrice( $orderId, $itemId, $itemOptionId );
	}

	/**
	 * 장바구니 상품별 총합 금액 출력
	 *
	 * @param int $orderId 주문번호
	 * @param int $itemId 상품번호
	 * @param int $itemOptionId 옵션정보
	 *
	 * @return int
	 */
	public function getCartTotalPrice( $orderId, $itemId, $itemOptionId )
	{
		$query = /** @lang text */
			" SELECT (cutting_total_price + cutting_opt_price) * cutting_qty + cutting_price  as total_price 
			FROM " . _DODAM_CUTTING_TABLE_ . " 
			WHERE od_id='{$orderId}' 
				AND it_id='{$itemId}' 
				AND cutting_option='{$itemOptionId}' ";
		/** @var array $row */
		$row = sql_fetch( $query );

		return _isset( 'total_price', $row, 'intval' );
	}

	/**
	 * 재단기능 사용위해 추가 되는 테이블 추가필드 쿼리
	 * @param string $tableName
	 * @param array $fields
	 *
	 * @return string
	 */
	public function getAlterTableAdd( $tableName, &$fields )
	{
		$queryCommon = array();
		$sqlAfter    = '';
		$query       = /** @lang text */
			" ALTER TABLE `{$tableName}` ";

		reset( $fields );

		foreach ( $fields as $key => $value ) {
			if ( $value['sql_after'] ) {
				$sqlAfter = " AFTER `{$value['sql_after']}` ";
			}
			$queryCommon[] = " ADD `{$key}` {$value['sql_type']}({$value['sql_size']}) 
							NOT NULL DEFAULT '{$value['default']}' {$sqlAfter} ";
			$sqlAfter      = $sqlAfter = " AFTER `{$key}` ";
		}

		return $query . implode( ', ', $queryCommon );
	}

	/**
	 * @param array $fields
	 * @param array $values
	 *
	 * @return string
	 */
	public function getAddQuery( &$fields, &$values )
	{
		$addQuery = array();

		foreach ( $fields as $key ) {
			${$key} = _isset( $key, $values, 'sql_real_escape_string' );
			array_push( $addQuery, $key . " = '" . ${$key} . "'" );
		}

		return implode( ', ', $addQuery );
	}

	/**
	 * 재단정보 검색 및 출력
	 *
	 * @param string $cartId
	 * @param string $memberId
	 * @param string $itemId
	 * @param string $cutOption
	 * @param int $cuttingUse
	 *
	 * @return bool|\mysqli_result|resource
	 */
	public function selectCuttingTable( $cartId = '', $memberId = '', $itemId = '', $cutOption = '', $cuttingUse = 0 )
	{
		if ( empty( $cartId ) ) {
			$cartId = getUniqueCartOrderId(); //cart id 설정
		}

		if ( ! $cartId ) {
			return false;
		}

		$query = /** @lang text */
			" SELECT * FROM " . _DODAM_CUTTING_TABLE_ . " 
			WHERE od_id = '{$cartId}'
				AND cutting_use = '{$cuttingUse}' ";

		if ( $memberId ) {
			$query .= " AND mb_id = '{$memberId}' ";
		}

		if ( $itemId ) {
			$query .= " AND it_id = '{$itemId}' ";
		}

		if ( $cutOption ) {
			$query .= " AND cutting_option = '{$cutOption}' ";
		}

		$res = sql_query( $query );

		return $res;
	}

	/**
	 * 재단정보 재단테이블에 업데이트
	 *
	 * @param $request
	 * @param $itemCutOption
	 * @param $itemSaleAmount
	 * @param $memberId
	 *
	 * @return bool|\mysqli_result|resource
	 */
	public function updateCuttingTable( &$request, $itemCutOption, $itemSaleAmount, $memberId = '' )
	{
		$orderId = getUniqueCartOrderId(); //cart id 설정

		$cutOption = _Isset( 'cut_option', $request, 'sql_real_escape_string' );
		$itemIds   = _Isset( 'it_id', $request );
		/** @noinspection PhpUnusedLocalVariableInspection */
		$itemId = (int) $itemIds[0];

		$cuttingWidth  = _Isset( 'cutting_width', $request );
		$cuttingHeight = _Isset( 'cutting_height', $request ); /** @var array $cuttingHeight */
		$cuttingCount  = _Isset( 'cutting_count', $request ); /** @var array $cuttingCount */
		$cuttingAmount = _Isset( 'cutting_amount', $request );
		$unit_price    = _Isset( 'unit_price', $request );

		$cuttingQty = _Isset( 'cutting_qty', $request, 'sql_real_escape_string' );
		$cutJatturi = _Isset( 'cut_jatturi', $request, 'sql_real_escape_string' );
		$cutPrice   = _Isset( 'cut_price', $request, 'sql_real_escape_string' );
		$optPrice   = _Isset( 'opt_price', $request, 'sql_real_escape_string' );

		$itemCuttingTotalPrice = _Isset( 'it_cutting_total_price', $request, 'intval', 0 );
		if ( $itemCuttingTotalPrice ) {
			$itemTotalPrice = $itemCuttingTotalPrice;
		} else {
			$itemTotalPrice = $itemSaleAmount;
		}

		$cuttingList = $this->getCuttingList( $itemCutOption, $cuttingWidth, $cuttingHeight, $cuttingCount, $cuttingAmount, $unit_price );

		$queryCommon = "
		cutting_list        = '{$cuttingList}', 
		cutting_option      = '{$cutOption}', 
		cutting_qty         = '{$cuttingQty}', 
		cutting_jatturi     = '{$cutJatturi}', 
		cutting_price       = '{$cutPrice}', 
		cutting_total_price = '{$itemTotalPrice}',
		cutting_opt_price   = '{$optPrice}' ";

		$query = /** @lang text */
			" UPDATE " . _DODAM_CUTTING_TABLE_ . " SET {$queryCommon} 
			WHERE od_id = '{$orderId}' 
				AND cutting_use = '0' ";

		if ( $memberId ) {
			$query .= " AND mb_id = '{$memberId}' ";
		}

		if ( $itemId ) {
			$query .= " AND it_id = '{$itemId}' ";
		}

		if ( $cutOption ) {
			$query .= " AND cutting_option = '{$cutOption}' ";
		}

		$result = sql_query( $query );

		return $result;
	}

	/**
	 * cart_id 확인 후 재단테이블의 od_id 와 다른 경우 업데이트 하기
	 *
	 * @param string $cartId
	 */
	public function updateCuttingCartId( $cartId = '' )
	{
		$memberId = _isset( 'mb_id', $GLOBALS['member'] );

		if ( empty( $cartId ) ) {
			$cartId = getUniqueCartOrderId();
		}

		$query = /** @lang text */
			" SELECT od_id FROM " . _DODAM_CUTTING_TABLE_ . " WHERE mb_id = '{$memberId}' AND cutting_use = '0' ";
		$row   = sql_fetch( $query );

		// 보관된 자료 cart id 변경
		if ( $memberId && $cartId != $row['od_id'] ) {
			$query = /** @lang text */
				" UPDATE " . _DODAM_CUTTING_TABLE_ . " SET od_id = '{$cartId}' 
			        WHERE mb_id = '{$GLOBALS['member']['mb_id']}' AND cutting_use = '0' ";
			sql_query( $query );
		}
	}

	/**
	 * 재단정보 재단테이블에 저장하기
	 *
	 * @param $request
	 * @param $itemCutOpt
	 * @param $itemSaleAmount
	 *
	 * @return bool|\mysqli_result|resource
	 */
	public function insertCuttingTable( &$request, $itemCutOpt, $itemSaleAmount )
	{
		$orderId   = getUniqueCartOrderId(); //cart id 설정
		$cutOption = _Isset( 'cut_option', $request, 'sql_real_escape_string' );
		$itemIds   = _Isset( 'it_id', $request );
		$cartId    = _Isset( 'ct_id', $request );
		$itemId    = sql_real_escape_string( $itemIds[0] );

		$cuttingWidth  = _Isset( 'cutting_width', $request );
		$cuttingHeight = _Isset( 'cutting_height', $request ); /** @var array $cuttingHeight */
		$cuttingCount  = _Isset( 'cutting_count', $request ); /** @var array $cuttingCount */
		$cuttingAmount = _Isset( 'cutting_amount', $request );
		$unit_price    = _Isset( 'unit_price', $request );

		$cuttingQty = _Isset( 'cutting_qty', $request, 'sql_real_escape_string' );
		$cutJatturi = _Isset( 'cut_jatturi', $request, 'sql_real_escape_string' );
		$cutPrice   = _Isset( 'cut_price', $request, 'sql_real_escape_string' );
		$optPrice   = _Isset( 'opt_price', $request, 'sql_real_escape_string' );

		$itemCuttingTotalPrice = _Isset( 'it_cutting_total_price', $request, 'intval', 0 );
		if ( $itemCuttingTotalPrice ) {
			$itemTotalPrice = $itemCuttingTotalPrice;
		} else {
			$itemTotalPrice = $itemSaleAmount;
		}

		$cutting_list = $this->getCuttingList( $itemCutOpt, $cuttingWidth, $cuttingHeight, $cuttingCount, $cuttingAmount, $unit_price );

		$queryCommon = "
		ct_id               = '{$cartId}', 
		od_id               = '{$orderId}', 
		mb_id               = '{$GLOBALS['member']['mb_id']}', 
		it_id               = '{$itemId}', 
		cutting_use         = '0', 
		cutting_list        = '{$cutting_list}', 
		cutting_option      = '{$cutOption}', 
		cutting_qty         = '{$cuttingQty}', 
		cutting_jatturi     = '{$cutJatturi}', 
		cutting_price       = '{$cutPrice}', 
		cutting_total_price = '{$itemTotalPrice}', 
		cutting_opt_price   = '{$optPrice}',
		cutting_time        = '" . G5_TIME_YMDHIS . "' ";

		$query = /** @lang text */
			" INSERT INTO " . _DODAM_CUTTING_TABLE_ . " SET {$queryCommon} ";

		$result = sql_query( $query );

		return $result;
	}

	/**
	 * 재단테이블중 설정된 기간이 자닌 정보 삭제하기
	 *
	 * @param string $memberId
	 * @param string $cuttingId
	 * @param string $lifeTime
	 *
	 * @return bool|\mysqli_result|resource
	 */
	public function deleteCuttingTableTerm( $memberId = '', $cuttingId = '', $lifeTime = '' )
	{
		$queryCommon = "";

		if ( $memberId ) {
			$queryCommon .= " AND mb_id = '{$memberId}' ";
		}

		if ( $cuttingId ) {
			$queryCommon .= " AND cutting_id = '{$cuttingId}' ";
		}

		if ( $lifeTime ) {
			$queryCommon .= " AND SUBSTRING(cutting_time, 1, 10) < '{$lifeTime}' ";
		}

		//주문상태가 아닌 재단정보 중 보관기간 지난 재단정보 삭제
		$query  = /** @lang text */
			" DELETE FROM " . _DODAM_CUTTING_TABLE_ . " 
	        WHERE cutting_use = '0' " . $queryCommon;
		$result = sql_query( $query );

		return $result;
	}

	/**
	 * 재단정보
	 *
	 * @param int $itemCutOpt
	 * @param $cuttingWidth
	 * @param array $cuttingHeight
	 * @param array $cuttingCount
	 * @param array $cuttingAmount
	 * @param array $unitPrice
	 *
	 * @return array
	 */
	public function getCuttingList(
		$itemCutOpt,
		&$cuttingWidth,
		&$cuttingHeight = array(),
		&$cuttingCount,
		&$cuttingAmount,
		&$unitPrice = array()
	)
	{
		$cuttingList = array(); //재단 정보

		switch ( $itemCutOpt ) {
			case "1":
				array_push( $cuttingList, array( '재단길이', '단가', '수량', '재단비', '소계' ) );

				for ( $c = 0; $c < count( $cuttingWidth ); $c ++ ) {
					$subTotal = $unitPrice[ $c ] * $cuttingCount[ $c ] + $cuttingAmount[ $c ];
					$addArray = array(
						'cutting_width'  => sql_real_escape_string( number_format($cuttingWidth[ $c ]) ),
						'unit_price'     => sql_real_escape_string( number_format($unitPrice[ $c ]) ),
						'cutting_count'  => sql_real_escape_string( number_format($cuttingCount[ $c ]) ),
						'cutting_amount' => sql_real_escape_string( number_format($cuttingAmount[ $c ]) ),
						'sub_total'      => sql_real_escape_string( number_format($subTotal) )
					);
					array_push( $cuttingList, $addArray );
				}
				break;

			case "2";
				array_push( $cuttingList, array( '재단(가로)', '재단(세로)', '단가', '수량', '재단비', '소계' ) );

				for ( $c = 0; $c < count( $cuttingHeight ); $c ++ ) {
					$subTotal = $unitPrice[ $c ] * $cuttingCount[ $c ] + $cuttingAmount[ $c ];
					$addArray = array(
						'cutting_width'  => sql_real_escape_string( number_format($cuttingWidth[ $c ]) ),
						'cutting_height' => sql_real_escape_string( number_format($cuttingHeight[ $c ]) ),
						'unit_price'     => sql_real_escape_string( number_format($unitPrice[ $c ]) ),
						'cutting_count'  => sql_real_escape_string( number_format($cuttingCount[ $c ]) ),
						'cutting_amount' => sql_real_escape_string( number_format($cuttingAmount[ $c ]) ),
						'sub_total'      => sql_real_escape_string( number_format($subTotal) )
					);
					array_push( $cuttingList, $addArray );
				}
				break;

			case "3";
				array_push( $cuttingList, array( '재단길이', '수량', '재단비' ) );

				for ( $c = 0; $c < count( $cuttingWidth ); $c ++ ) {
					$addArray = array(
						'cutting_width'  => sql_real_escape_string( number_format($cuttingWidth[ $c ]) ),
						'cutting_count'  => sql_real_escape_string( number_format($cuttingCount[ $c ]) ),
						'cutting_amount' => sql_real_escape_string( number_format($cuttingAmount[ $c ]) )
					);
					array_push( $cuttingList, $addArray );
				}
				break;

			case "4";
				break;
		}

		$cuttingList = serialize( $cuttingList );

		return $cuttingList;
	}
}