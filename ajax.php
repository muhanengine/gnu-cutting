<?php
define( 'DOING_AJAX', true );

require_once( dirname( dirname( dirname( __FILE__ ) ) ) .'/common.php' );

$action = Cutting\Libs\Utils\_isset( 'action', $_REQUEST );
$action = str_replace( '-', '_', $action );

if ( ! empty($action) ) {
	Cutting\Libs\Functions\doAction( _DODAM_AJAX_ . $action );
}

die( '0' );
