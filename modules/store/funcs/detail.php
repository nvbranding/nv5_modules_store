<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */
if ( ! defined( 'NV_IS_MOD_STORE' ) ) die( 'Stop!!!' );

if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
    //Send request and receive json data by latitude and longitude
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyA7_ZyiNQBuJxZKsoOWWNGshZx8kewMt7o&latlng='.$_POST['latitude'].','.$_POST['longitude'].'&sensor=false';
    $json = @file_get_contents($url);
    $data = json_decode($json);
    
    echo $data;
	die;
}


$page_title = $module_info['custom_title'];
$key_words = $global_config['site_description'];
$related_articles = $page_config['related_articles'];
$array_data = array();
$array_images = array();
if($id > 0)
{
	 $time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $id, 'session');
    if (empty($time_set)) {
        $nv_Request->set_Session($module_data . '_' . $op . '_' . $id, NV_CURRENTTIME);
        $sql = 'UPDATE ' .TMS_STORE . '_rows SET hitstotal=hitstotal+1 WHERE id=' . $id;
        $db->query($sql);
    }
	

	
	$array_data = $db->query("SELECT * FROM ". TMS_STORE . "_rows WHERE status > 0 AND id =" .$id)->fetch();
	$page_title = empty($array_data['title_seo']) ? $array_data['title'] : $array_data['title_seo'];
	$description = empty($array_data['bodytext_seo']) ? nv_clean60(strip_tags($array_data['bodytext']), 160) : $array_data['bodytext_seo'];
	$key_words = empty($array_data['keywords']) ? $global_config['site_description'] : $array_data['keywords']; 
	
    $array_data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_data['image'];
		
	
	// DANH SÁCH CỬA HÀNG CÙNG QUẬN 
	// LẤY DANH SÁCH CỬA HÀNG
		$array_lienquan = array();
		$list_row = $db->query('SELECT * FROM '. TMS_STORE . '_rows WHERE status > 0 AND id !='. $id .' AND tinhthanh ='.$array_data['tinhthanh'] .' AND quanhuyen ='.$array_data['quanhuyen'].' limit '.$related_articles)->fetchAll();
		
		foreach($list_row as $itemlq)
		{
			//print_r($itemlq); die();
			$itemlq['link'] =  nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $itemlq['alias'],true);
			$itemlq['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $itemlq['image'] ;
			$itemlq['tinhthanh'] = tinhthanh($itemlq['tinhthanh']);
			$itemlq['quanhuyen'] = quanhuyen($itemlq['quanhuyen']);
            $array_lienquan[] = $itemlq;
		}
}

$contents = nv_theme_store_detail( $array_data, $array_lienquan);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
