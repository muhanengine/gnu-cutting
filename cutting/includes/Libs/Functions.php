<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:27
 */

namespace Cutting\Libs\Functions;

use function Cutting\Libs\Utils\isStrCaseCmp;

/**
 * set nonce
 *
 * @param string $keyName
 */
function setNonce( $keyName = 'ss_uniqid_nonce' )
{
	$nonce = md5( uniqid(rand(), true) );
	set_session( $keyName, $nonce );
}

/**
 * get nonce
 *
 * @param string $keyName
 *
 * @return string
 */
function getNonce( $keyName = 'ss_uniqid_nonce' )
{
	$nonce = get_session( $keyName );
	if ( empty($nonce) ) {
		setNonce();
		$nonce = get_session( $keyName );
	}

	return $nonce;
}
/**
 * compare nonce
 *
 * @param $nonce
 * @param string $keyName
 *
 * @return bool
 */
function checkNonce( $nonce, $keyName = 'ss_uniqid_nonce' )
{
	return isStrCaseCmp( getNonce($keyName), $nonce );
}

/**
 * 템플릿 파일 불러오기
 *
 * @param string $template_name 파일명
 * @param array $args
 * @param string $template_path 파일경로
 * @param string $extension
 */
function getTemplatePart( $template_name, &$args = array(), $template_path = '', $extension = 'php' )
{
	if ( ! empty($args) && is_array( $args ) ) {
		extract( $args );
	}

	$located = getTemplateFilePath( $template_name, $template_path, $extension );

	if ( file_exists($located) ) {
		@require( $located );
	}
}

/**
 * 템플릿 파일경로
 * @param string $template_name 파일명
 * @param string $template_path 파일경로
 * @param string $extension 파일확장자
 * @return string
 */
function getTemplateFilePath( $template_name, $template_path = '', $extension = 'php' )
{
	$located = '';

	if ( ! empty($template_name) ) {
		if ( ! $template_path ) {
			$located = dirname( dirname( __FILE__ ) ) . '/Templates/' . $template_name . '.'. $extension;
		} else {
			$located = $template_path . $template_name . '.'. $extension;
		}
	}

	return $located;
}

/**
 * css, js 파일 불러오기
 *
 * @param  string $filePath 파일경로명
 * @param  string $version   버전정보
 */
function includeCssJs( $filePath, $version = '' )
{
	$pathParts = pathinfo( $filePath );
	$srcUrl    = $filePath;

	if ( ! empty( $version ) ) {
		$srcUrl .= '?ver=' . $version;
	}

	// 동일한 host url 인지
	check_url_host( $srcUrl );

	if ( $pathParts['extension'] == 'css' ) {
		add_stylesheet('<link rel="stylesheet" href="//'. $_SERVER['HTTP_HOST'] . $srcUrl .'">', 0);
	} else if ( $pathParts['extension'] == 'js' ) {
		add_javascript('<script src="//'. $_SERVER['HTTP_HOST'] . $srcUrl .'"></script>', 0);
	}
}

/**
 * 훅 추가하기
 *
 * @param string $keyName
 * @param mixed $addFunction
 * @param int $priority
 *
 * @return bool
 */
function addAction( $keyName = '', $addFunction = '', $priority = 10 )
{
	static $includeList;

	$keyName = trim( $keyName );

	if ( empty($keyName) ) {
		return $includeList;
	}

	$includeList[ $keyName ][ $priority ][] = $addFunction;

	return true;
}

/**
 * 훅 실행하기
 *
 * @param string $keyName
 * @param array $args Parameter
 * @param bool $filter
 *
 * @return mixed
 */
function doAction( $keyName, $args = array(), $filter = false )
{
	/** @var $includeList */
	$includeList = getActions();

	$keyName = trim( $keyName );
	$valAction = null;

	if ( isset( $includeList[ $keyName ] ) ) {
		ksort( $includeList[ $keyName ] );

		foreach ( $includeList[ $keyName ] as $actions ) {
			foreach ( $actions as $key => $function ) {
				if ( is_callable( $function ) || function_exists( $function ) ) {
					$valAction .= call_user_func_array( $function, $args );
				}
			}
		}

		unset( $includeList[ $keyName ] );
	}

	if ( true === $filter && is_null($valAction)) {
		return $args[0];
	} else {
		return $valAction;
	}
}

/**
 * 훅 목록 출력
 */
function getActions()
{
	return addAction();
}