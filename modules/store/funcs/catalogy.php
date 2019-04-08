<?php
/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_IS_MOD_STORE' ) ) die( 'Stop!!!' );

$key_words = $global_config['site_description'];
$per_home = $page_config['per_home'];
$per_page = $page_config['per_page'];

	
$array_data = array();

if($catid > 0)
	{
		$page_title = empty($global_array_cat[$catid]['title_seo']) ? $global_array_cat[$catid]['title'] : $global_array_cat[$catid]['title_seo'];
		$description = empty($global_array_cat[$catid]['bodytext_seo']) ? nv_clean60(strip_tags($global_array_cat[$catid]['bodytext']), 160) : $global_array_cat[$catid]['bodytext_seo'];
		$key_words = empty($global_array_cat[$catid]['keywords']) ? $global_config['site_description'] : $global_array_cat[$catid]['keywords']; 
	
		// LẤY DANH SÁCH CỬA HÀNG
		//$list_row = $db->query('SELECT * FROM '. STORE . '_rows WHERE status > 0 AND catalog ='.$catid)->fetchAll();
		
			$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'];
		
			
			$page = $nv_Request->get_int( 'page', 'post,get', 1 );
			$db->sqlreset()
				->select( 'COUNT(*)' )
				->from( '' . STORE . '_rows' )
				->where('status = 1 AND catalog ='.$catid);

			$sth = $db->prepare( $db->sql() );

			$sth->execute();
			$num_items = $sth->fetchColumn();

			$db->select( '*' )
				->order( 'weight ASC' )
				->limit( $per_page )
				->offset( ( $page - 1 ) * $per_page );
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
	

		while( $item = $sth->fetch() )
		{
			//print_r($global_array_cat);die;
			$item['link'] =  nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $item['alias'],true);
			$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'] ;
			$item['tinhthanh'] = tinhthanh($item['tinhthanh']);
			$item['quanhuyen'] = quanhuyen($item['quanhuyen']);
		  $array_data[] = $item;
			
			
		}
	}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$contents = nv_theme_store_catalogy( $array_data , $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
