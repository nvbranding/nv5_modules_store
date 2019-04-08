<?php
/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_IS_MOD_STORE' ) ) die( 'Stop!!!' );

/**
 * nv_theme_store_main_gird()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_main_gird ($array_data)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config,$global_array_cat, $module_info, $op;

    $xtpl = new XTemplate('maingird.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	//print_r($global_array_cat); die();
	
	
    foreach($array_data as $cata)
	{
		$xtpl->assign( 'cata', $cata );
		if(!empty($cata['data']))
		{
			foreach($cata['data'] as $row)
			{
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.cata.loop' );
			}
		}
		
	if(!empty ($cata['data']))
	{		
		$xtpl->parse( 'main.cata' );
	}
	
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_store_main_list()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_main_list ($array_data)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config,$global_array_cat, $module_info, $op;

    $xtpl = new XTemplate('mainlist.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	//print_r($global_array_cat); die();
	
	
    foreach($array_data as $cata)
	{
		$xtpl->assign( 'cata', $cata );
		if(!empty($cata['data']))
		{
			foreach($cata['data'] as $row)
			{
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.cata.loop' );
			}
		}
		
	if(!empty ($cata['data']))
	{		
		$xtpl->parse( 'main.cata' );
	}
	
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_store_list()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_list ($array_data, $generate_page)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config,$global_array_cat, $module_info, $op;
    $xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	//print_r($global_array_cat); die();
	
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
			
    foreach($array_data as $cata)
	{
		if(!empty($cata['data']))
		{
			foreach($cata['data'] as $row)
			{
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.loop' );
			}
		}
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}



/**
 * nv_theme_store_gird()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_gird ($array_data, $generate_page)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config,$global_array_cat, $module_info, $op;
    $xtpl = new XTemplate('gird.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	//print_r($global_array_cat); die();
	
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
			
    foreach($array_data as $cata)
	{
		if(!empty($cata['data']))
		{
			foreach($cata['data'] as $row)
			{
				$xtpl->assign( 'ROW', $row );
				$xtpl->parse( 'main.loop' );
			}
		}
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}


function nv_theme_store_map ( $array_data,$list_store,$global_raovat_city )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $nv_Cache, $id_tinhthanh, $id_quanhuyen, $db, $db_config;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'module_name', $module_name );

	foreach($list_store as $row)
			{
				$row['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $row['alias'],true);
				$row['googmaps'] = @unserialize( $row['googmaps'] );
				if( $row['googmaps'] )
				{
					$xtpl->assign( 'lat', isset( $row['googmaps']['lat'] ) ? $row['googmaps']['lat'] : '' );
					$xtpl->assign( 'lng', isset( $row['googmaps']['lng'] ) ? $row['googmaps']['lng'] : '' );
					$xtpl->assign( 'zoom', isset( $row['googmaps']['zoom'] ) ? $row['googmaps']['zoom'] : '' );
				}else{
					$xtpl->assign( 'lat', 21.01324600018122 );
					$xtpl->assign( 'lng', 105.83596636250002 );
					$xtpl->assign( 'GOOGLEMAPZOOM1', 15 );
				}
				// HÌNH ẢNH LOẠI
				$xtpl->assign( 'anh_chinhanh', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' .$module. '/' .$global_array_store[$row['catalog']]['image']);
				$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
				$xtpl->assign( 'row', $row );
				$xtpl->parse( 'main.loop' );
				$xtpl->parse( 'main.loop_left' );
			}
foreach( $global_raovat_city as $key => $item )
			{
				$xtpl->assign( 'CITY', array(
					'key' => $key,
					'alias' =>  $item['alias'],
					'name' => $item['title'],
					'selected' => ( $id_tinhthanh == $key ) ? 'selected="selected"' : '' ) );
				$xtpl->parse( 'main.city' );
			}
			
 
			foreach($array_data as $row)
			{
				$row['googmaps'] = @unserialize( $row['googmaps'] );
				//print_r($row['googmaps']);die;
				if( $row['googmaps'] )
				{
					$xtpl->assign( 'lat', isset( $row['googmaps']['lat'] ) ? $row['googmaps']['lat'] : '' );
					$xtpl->assign( 'lng', isset( $row['googmaps']['lng'] ) ? $row['googmaps']['lng'] : '' );
					$xtpl->assign( 'zoom', isset( $row['googmaps']['zoom'] ) ? $row['googmaps']['zoom'] : '' );

				}else{
					$xtpl->assign( 'lat', 21.01324600018122 );
					$xtpl->assign( 'lng', 105.83596636250002 );
					$xtpl->assign( 'GOOGLEMAPZOOM1', 15 );

					
				}
				// HÌNH ẢNH LOẠI
				$xtpl->assign( 'anh_chinhanh', $cata['image'] );
				$xtpl->assign( 'row', $row );
				$xtpl->parse( 'main.loop' );
				$xtpl->parse( 'main.loop_left' );
			}
		

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_province';
$global_raovat_city = $nv_Cache->db( $sql, 'provinceid', 'location' );
foreach( $global_raovat_city as $key => $item )
{
	$xtpl->assign( 'CITY', array(
		'key' => $key,
		'alias' =>  $item['alias'],
		'name' => $item['title'],
		'selected' => ( $row['tinhthanh'] == $key ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.city' );
}


if( $id_tinhthanh )
{
	$sql = 'SELECT districtid, title FROM ' . $db_config['prefix'] . '_location_district WHERE status = 1 AND provinceid= ' . intval( $id_tinhthanh ) . ' ORDER BY weight ASC';
	$result = $db->query( $sql );

	while( $data = $result->fetch() )
	{
		$xtpl->assign( 'DISTRICT', array(
			'key' => $data['districtid'],
			'alias' =>  $item['alias'],
			'name' => $data['title'],
			'selected' => ( $data['districtid'] == $id_tinhthanh) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.district' );
	}
	

}

if( $id_quanhuyen )
{
	$sql = 'SELECT wardid, title FROM ' . $db_config['prefix'] . '_location_ward WHERE status = 1 AND districtid= ' . intval( $id_quanhuyen );
	$result = $db->query( $sql );

	while( $data = $result->fetch() )
	{
		$xtpl->assign( 'WARD', array(
			'key' => $data['wardid'],
			'alias' =>  $item['alias'],
			'name' => $data['title'],
			'selected' => ( $data['wardid'] == $row['xaphuong'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.ward' );
	}
	
	
	
}




    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}


/**
 * nv_theme_store_detail()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_detail ( $array_data, $array_lienquan )
{
    global $global_config, $module_name, $module_file,$client_info, $lang_module,$page_config, $module_config, $module_info, $op, $global_array_cat, $module_upload;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module ); 
	$xtpl->assign( 'TEMPLATE', $module_info['template'] );
	
    $xtpl->assign( 'CATA', $global_array_cat[$array_data['catalog']] );
	$xtpl->assign( 'LINKFB',$client_info['selfurl'] );
    $xtpl->assign( 'row', $array_data );
	
		
	// lấy hình ảnh khác
	
	if(!empty($array_data['bodytext'])){$xtpl->parse( 'main.bodytext' );}
	if($page_config['thaoluan']==1){$xtpl->parse( 'main.thaoluan' );}
	
	if(!empty($array_data['images_orther']))
{

	$array_images = explode('|',$array_data['images_orther']);
	foreach( $array_images as $img)
	{
		$anh_orther = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' .$img;
		$xtpl->assign( 'IMAGE', $anh_orther );
		
		$xtpl->parse( 'main.image_loop' );
	}
}


	$row['googmaps'] = @unserialize( $array_data['googmaps'] );
	//print_r($row['googmaps']['lat']);die;
	if( $row['googmaps'] )
	{
		$xtpl->parse( 'main.addMarker' );
		
		
		$xtpl->assign( 'GOOGLEMAPLAT1', isset( $row['googmaps']['lat'] ) ? $row['googmaps']['lat'] : '' );
		$xtpl->assign( 'GOOGLEMAPLNG1', isset( $row['googmaps']['lng'] ) ? $row['googmaps']['lng'] : '' );
		$xtpl->assign( 'GOOGLEMAPZOOM1', isset( $row['googmaps']['zoom'] ) ? $row['googmaps']['zoom'] : '' );

	}else{
		$xtpl->assign( 'GOOGLEMAPLAT1', 21.01324600018122 );
		$xtpl->assign( 'GOOGLEMAPLNG1', 105.83596636250002 );
		$xtpl->assign( 'GOOGLEMAPZOOM1', 15 );

		
	}

//print_r($array_data);die;
		if(!empty($array_lienquan))
		{
			foreach($array_lienquan as $row)
			{
				$xtpl->assign( 'LQ', $row );
				$xtpl->parse( 'main.loop_liequan' );
			}
		}
    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_store_search()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_search ( $array_data )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_theme_store_catalogy()
 * 
 * @param mixed $array_data
 * @return
 */
function nv_theme_store_catalogy ( $array_data, $generate_page)
{
    global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $op, $catid, $global_array_cat;

    $xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
	
    $xtpl->assign( 'cata', $global_array_cat[$catid] );

	foreach($array_data as $row)
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}
	
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}
    

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}