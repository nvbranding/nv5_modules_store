<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
define('TMS_STORE', $db_config['dbsystem']. '.' .NV_PREFIXLANG. '_' . $module_data);
define('TMS_STORE_ADD', $db_config['dbsystem']. '.' .$db_config['prefix']. '_location');
define( 'NV_IS_FILE_ADMIN', true );


$allow_func = array( 'main', 'catalogy','block', 'add', 'config');

function getOutputJson( $json )
{
	global $global_config, $db, $lang_global, $lang_module, $language_array, $nv_parse_ini_timezone, $countries, $module_info, $site_mods;

	@Header( 'Content-Type: application/json' );
	@Header( 'Content-Type: text/html; charset=' . $global_config['site_charset'] );
	@Header( 'Content-Language: ' . $lang_global['Content_Language'] );
	@Header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( '-1 day' ) ) . " GMT" );
	@Header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', NV_CURRENTTIME - 60 ) . " GMT" );
	
	echo json_encode( $json );
	unset( $GLOBALS['db'], $GLOBALS['lang_module'], $GLOBALS['language_array'], $GLOBALS['nv_parse_ini_timezone'],$GLOBALS['countries'], $GLOBALS['module_info'], $GLOBALS['site_mods'], $GLOBALS['lang_global'], $GLOBALS['global_config'], $GLOBALS['client_info'] );
	
	exit();
}
// Get Config Module
$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
$page_config = array();
foreach ($list as $values) {
    $page_config[$values['config_name']] = $values['config_value'];
}
