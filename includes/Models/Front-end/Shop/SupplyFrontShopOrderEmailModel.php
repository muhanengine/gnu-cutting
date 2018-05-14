<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 28.
 * Time: PM 1:58
 */
namespace Cutting\Models\Front_end\Shop;

class SupplyFrontShopOrderEmailModel
{
	/**
	 * 주문완료메일 내용중 재단목록 테이블 CSS 정보
	 *
	 * @return array|mixed|null
	 */
	public static function &getScheme()
	{
		static $scheme = null;

		if ( is_null( $scheme ) ) {

			$scheme = array();

			$scheme['table'] = self::styleTable();
			$scheme['th']    = self::styleTableTh();
			$scheme['td']    = self::styleTableTd();
		}

		return $scheme;
	}

	/**
	 * Table Style
	 *
	 * @return string
	 */
	public static function styleTable()
	{
		$css = array();
		$css['border-collapse'] = 'collapse';
		$css['border']          = '1px solid #D1D1D1';

		return self::generateStyle( $css );
	}

	/**
	 * Table th Style
	 * @return string
	 */
	public static function styleTableTh()
	{
		$css = array();
		$css['border']     = '1px solid #D1D1D1';
		$css['text-align'] = 'center';
		$css['padding']    = '3px 10px';

		return self::generateStyle( $css );
	}

	/**
	 * Table td Style
	 *
	 * @return string
	 */
	public static function styleTableTd()
	{
		$css = array();
		$css['border']           = '1px solid #D1D1D1';
		$css['text-align']       = 'right';
		$css['padding']          = '3px 10px';
		$css['background-color'] = '#f2f2f2';

		return self::generateStyle( $css );
	}

	/**
	 * @param array $css
	 *
	 * @return string
	 */
	public static function generateStyle( $css )
	{
		$style = '';

		if ( ! is_array($css) ) {
			return null;
		}

		foreach ( $css as $key=>$value ) {
			$style .= $key .':'. $value .';';
		}

		return $style;
	}
}