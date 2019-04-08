<?php
/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_IS_MOD_STORE' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$per_home = $page_config['per_home'];
$per_page = $page_config['per_page'];



if ($page_config['viewtype'] == 0) {
$array_data = array();

foreach($global_array_cat as $catalog)
{
	if($catalog['id'] > 0)
	{
		$list_row = $db->query('SELECT * FROM '. STORE . '_rows WHERE status > 0 AND catalog ='.$catalog['id'].' limit '.$per_home)->fetchAll();
		foreach($list_row as $item)
		{	
			$item['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $item['alias'],true);
			$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'] ;
			$item['tinhthanh'] = tinhthanh($item['tinhthanh']);
			$item['quanhuyen'] = quanhuyen($item['quanhuyen']);
            $catalog['data'][] = $item;
		}
	}
	$array_data[] = $catalog;
}
$contents = nv_theme_store_main_gird( $array_data );
}

if ($page_config['viewtype'] == 1) {
$array_data = array();

foreach($global_array_cat as $catalog)
{
	if($catalog['id'] > 0)
	{
		$list_row = $db->query('SELECT * FROM '. STORE . '_rows WHERE status > 0 AND catalog ='.$catalog['id'].' limit '.$per_home)->fetchAll();
		foreach($list_row as $item)
		{	
			$item['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $item['alias'],true);
			$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'] ;
			$item['tinhthanh'] = tinhthanh($item['tinhthanh']);
			$item['quanhuyen'] = quanhuyen($item['quanhuyen']);
            $catalog['data'][] = $item;
		}
	}
	$array_data[] = $catalog;
}
$contents = nv_theme_store_main_list( $array_data );
}

if ($page_config['viewtype'] == 2) {
			$page = $nv_Request->get_int( 'page', 'post,get', 1 );
			$db->sqlreset()
				->select( 'COUNT(*)' )
				->from( '' . STORE . '_rows' )
				->where('status=1');
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
			$num_items = $sth->fetchColumn();

			$db->select( '*' )
				->order( 'weight ASC' )
				->limit( $per_page )
				->offset( ( $page - 1 ) * $per_page );
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
		
		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
			
		$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
		
		
		while( $item = $sth->fetch() )
		{	
			$item['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $item['alias'],true);
			$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'] ;
			$item['tinhthanh'] = tinhthanh($item['tinhthanh']);
			$item['quanhuyen'] = quanhuyen($item['quanhuyen']);
            $catalog['data'][] = $item;
		}
	$array_data[] = $catalog;
$contents = nv_theme_store_list( $array_data, $generate_page );
}

if ($page_config['viewtype'] == 3) {
			$page = $nv_Request->get_int( 'page', 'post,get', 1 );
			$db->sqlreset()
				->select( 'COUNT(*)' )
				->from( '' . STORE . '_rows' )
				->where('status=1');
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
			$num_items = $sth->fetchColumn();

			$db->select( '*' )
				->order( 'weight ASC' )
				->limit( $per_page )
				->offset( ( $page - 1 ) * $per_page );
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
		
		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
			
		$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
		
		
		while( $item = $sth->fetch() )
		{	
			$item['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $item['alias'],true);
			$item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'] ;
			$item['tinhthanh'] = tinhthanh($item['tinhthanh']);
			$item['quanhuyen'] = quanhuyen($item['quanhuyen']);
            $catalog['data'][] = $item;
		}
	$array_data[] = $catalog;
$contents = nv_theme_store_gird( $array_data, $generate_page );
}


include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
