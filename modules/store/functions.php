<?php
/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_STORE', true );
define('TMS_STORE', $db_config['dbsystem']. '.' .NV_PREFIXLANG. '_' . $module_data);
define('TMS_STORE_ADD', $db_config['dbsystem']. '.' .$db_config['prefix']. '_location');

// Get Config Module
$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
$page_config = array();
foreach ($list as $values) {
    $page_config[$values['config_name']] = $values['config_value'];
}

function tinhthanh($tinhthanh =0)
	{
		global $db,$module_data,$db_config;
		$sql = 'SELECT  provinceid, title, alias, type FROM ' .$db_config['dbsystem']. '.' .$db_config['prefix']. '_location_province WHERE provinceid="' . $tinhthanh.'"';
		$data = $db->query($sql)->fetch();
		return $data['title'];
	}
	
function quanhuyen($id =0)
	{
		global $db,$module_data,$db_config;
		$sql = 'SELECT districtid, title, alias, type FROM ' .$db_config['dbsystem']. '.' .$db_config['prefix']. '_location_district WHERE districtid="' . $id.'"';
		$data = $db->query($sql)->fetch();
		return $data['title'];
	}
	
	

			
// LẤY DANH SÁCH CATALOGY

global $global_array_cat;
$global_array_cat = array();
$sql = 'SELECT * FROM '. TMS_STORE . '_catalogy WHERE status > 0';
$list = $nv_Cache->db($sql, 'id', $module_name);
if (!empty($list)) {
    foreach ($list as $l) {
        $global_array_cat[$l['id']] = $l;
        $global_array_cat[$l['id']]['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $l['alias'],true);
		
		$global_array_cat[$l['id']]['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $global_array_cat[$l['id']]['image'] ;
       
    }
}

//THÊM
if($link_true)
{
$count_op = sizeof($array_op);
if($count_op == 1)
{
	// LẤY id danh mục ra
	
	$catid_tam = $db->query("SELECT id FROM ". TMS_STORE . "_catalogy WHERE status > 0 AND alias ='" .$array_op[0] ."'")->fetchColumn();
	
	if($catid_tam > 0)
	{
		$catid = $catid_tam;
		$op = 'catalogy';
	}
}
if($count_op == 2)
{
	if(!empty($array_op[1]))
	{
		
		$id_tam = $db->query("SELECT id FROM ". TMS_STORE . "_rows WHERE status > 0 AND alias ='" .$array_op[1] ."'")->fetchColumn();
	
		if($id_tam > 0)
		{
			$id = $id_tam;
			$op = 'detail';
		}
		else
		{
			$id_tinhthanh = $db->query("SELECT provinceid FROM " . TMS_STORE_ADD."_province WHERE alias ='" .$array_op[1] ."'")->fetchColumn();
			if($id_tinhthanh > 0)
				$op = 'map';
		}
	}
}
}
