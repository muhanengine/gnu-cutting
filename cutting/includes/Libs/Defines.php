<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: PM 12:35
 */
namespace Cutting\Libs\Defines;

define( '_DODAM_CUTTING_USE_', true );
define( '_DODAM_CUTTING_PATH_', '/plugin/cutting' );
define( '_DODAM_CUTTING_URL_', '//'. $_SERVER['HTTP_HOST'] . _DODAM_CUTTING_PATH_ );
define( '_DODAM_CUTTING_TABLE_', G5_SHOP_TABLE_PREFIX .'cutting' );
define( '_DODAM_AJAX_', 'gnu_ajax_' );