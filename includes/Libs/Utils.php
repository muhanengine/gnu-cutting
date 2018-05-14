<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:27
 */

namespace Cutting\Libs\Utils;

/**
 * 설정된 변수 확인
 * @param  string $needle   체크하려는 슬러그
 * @param  string $callback
 * @param  string $default
 * @return array|string
 */
function getIsset( $needle, $callback = '', $default = '' )
{
	if ( ! empty($needle) ) {
		return _isset( $needle, $_GET, $callback, $default );
	}

	return null;
}

/**
 * 설정된 변수 확인
 * @param  string $needle   체크하려는 슬러그
 * @param  string $callback
 * @param  string $default
 * @return array|string
 */
function postIsset( $needle, $callback = '', $default = '' )
{
	if ( ! empty($needle) ) {
		return _isset( $needle, $_POST, $callback, $default );
	}

	return null;
}


/**
 * 설정된 변수 확인
 * @param string $key 체크하려는 이름
 * @param array $request 설정된 변수
 * @param string $callback
 * @param string $default
 * @return string
 */
function _isset( $key, &$request, $callback = '', $default = '' )
{
	$variable = null;

	if ( is_array($request) && isset($request[$key]) ) {
		$variable = $request[$key];
	} else if ( is_object($request) && isset($request->{$key}) ) {
		$variable = $request->{$key};
	}

	if ( ! is_null($variable) ) {
		return getFuncVar( $variable, $callback, $default );
	} else {
		return $default;
	}
}

/**
 * Call the callback given by the first parameter
 * @param string $variable parameter
 * @param string $callback callback
 * @param string $default
 * @return string
 */
function getFuncVar( $variable, $callback = '', $default = '' )
{
	if ( is_callable( $callback ) ) {
		$variable = call_user_func( $callback, $variable );
	}

	if ( empty($variable) || is_null($variable) ) {
		return $default;
	} else {
		return $variable;
	}
}

/**
 * string comparison
 *
 * @param $str1
 * @param $str2
 *
 * @return bool
 */
function isStrCaseCmp( $str1, $str2 )
{
	$str1 = trim($str1);
	$str2 = trim($str2);

	if ( strcasecmp( $str1, $str2 ) == 0 ) {
		return true;
	}

	return false;
}

/**
 * 입력값이 숫자인 경우 number format 적용
 * @param $val
 * @param string $unit
 *
 * @return string
 */
function isIntVal( $val, $unit = '' )
{
	$val = trim($val);

	if ( is_numeric($val) ) {
		return number_format($val) . $unit;
	}

	return $val;
}