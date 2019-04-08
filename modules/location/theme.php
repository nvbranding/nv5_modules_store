<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANG DINH TU (dlinhvan@gmail.com)
 * @Website http://dangdinhtu.com
 * @copyright 2009
 * @License GNU/GPL version 3 or any later version
 * @Createdate Sun, 28 Feb 2016 19:00:00 GMT
 */

if ( ! defined( 'NV_IS_MOD_LOCATION' ) ) die( 'Stop!' );

function nv_theme_samples_main ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info;
    $xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_theme_samples_detail ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info;
    $xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}