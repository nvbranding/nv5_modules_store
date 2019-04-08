<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANG DINH TU (dlinhvan@gmail.com)
 * @Website http://dangdinhtu.com
 * @copyright 2009
 * @License GNU/GPL version 3 or any later version
 * @Createdate Sun, 28 Feb 2016 19:00:00 GMT
 */

 
if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['street'];

$getCity = getCity();
$getDistrict = getDistrict();
$getstreet = getstreet();
$getStreet = getStreet();

if( ACTION_METHOD == 'district' )
{
	$json = array();
	$city_id = $nv_Request->get_int( 'city_id', 'get', 0 );
 
	$sql = 'SELECT district_id, title FROM ' . TMS_LOCALION . '_district WHERE city_id=' . $city_id;
	$result = $db_slave->query( $sql );

	while( list( $district_id, $title ) = $result->fetch( 3 ) )
	{
		$json['district'][] = array(
			'city_id' => $city_id,
			'name' => nv_htmlspecialchars( $title ),
			'district_id' => $district_id );

	}

	get_output_json( $json );
}

if( ACTION_METHOD == 'weight' )
{
	$json = array();

	$street_id = $nv_Request->get_int( 'street_id', 'get,post', 0 );

	$token = $nv_Request->get_title( 'token', 'post', '', 1 );

	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
	
	$district_id = $nv_Request->get_int( 'district_id', 'post', 0 );
	
	//$city_id = $nv_Request->get_int( 'city_id', 'post', 0 );

	if( ! isset( $getstreet[$street_id] ) )
	{
		$json['error'] = $lang_module['street_error_security'];
	}

	if( ! isset( $json['error'] ) )
	{
		if( $new_vid > 0 )
		{
			$sql = 'SELECT street_id FROM ' . TMS_LOCALION . '_street WHERE street_id != ' . $street_id . ' AND district_id = ' . $district_id . ' ORDER BY weight ASC';
			$result = $db_slave->query( $sql );

			$weight = 0;

			while( $row = $result->fetch() )
			{
				$weight++;

				if( $weight == $new_vid ) $weight++;

				$query = 'UPDATE ' . TMS_LOCALION . '_street SET weight=' . $weight . " WHERE street_id=" . $row['street_id'];
				$db_slave->query( $query );
			}

			$query = 'UPDATE ' . TMS_LOCALION . '_street SET weight=' . $new_vid . ' WHERE street_id=' . $street_id;

			$db_slave->query( $query );

			$nv_Cache->delMod($module_name);

			nv_insert_logs( NV_LANG_DATA, $module_name, 'Change Weight street', "street_id: " . $street_id, $admin_info['userid'] );

			$json['success'] = $lang_module['street_update_success'];
		}
	}

	get_output_json( $json );

}

if( ACTION_METHOD == 'delete' )
{
	$json = array();

	$street_id = $nv_Request->get_int( 'street_id', 'post', 0 );

	$token = $nv_Request->get_title( 'token', 'post', '', 1 );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );

	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $street_id ) )
	{
		$del_array = array( $street_id );
	}

	if( ! empty( $del_array ) )
	{

		$_del_array = array();

		$a = 0;
		foreach( $del_array as $street_id )
		{

			$db_slave->query( 'DELETE FROM ' . TMS_LOCALION . '_street WHERE street_id = ' . ( int )$street_id );

			$json['id'][$a] = $street_id;

			$_del_array[] = $street_id;

			++$a;
		}

		$count = sizeof( $_del_array );

		if( $count )
		{
			fixWeightstreet();

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_street', implode( ', ', $_del_array ), $admin_info['userid'] );

			$nv_Cache->delMod($module_name);

			$json['success'] = $lang_module['street_delete_success'];
		}
		else
		{
			$json['error'] = $lang_module['street_error_delete'];
		}

	}
	else
	{
		$json['error'] = $lang_module['street_error_security'];
	}

	get_output_json( $json );

}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$data = array(
		'street_id' => 0,
		'district_id' => 0,
		'city_id' => 0,
		'title' => '',
		'alias' => '',
		'weight' => 0,
		'status' => 0,
		);
	$error = array();

	$data['district_id'] = $nv_Request->get_int( 'district_id', 'get,post', 0 );
	$data['city_id'] = $nv_Request->get_int( 'city_id', 'get,post', 0 );
	$data['street_id'] = $nv_Request->get_int( 'street_id', 'get,post', 0 );

	if( $data['street_id'] > 0 )
	{
		$data = $db_slave->query( 'SELECT * FROM ' . TMS_LOCALION . '_street WHERE street_id=' . $data['street_id'] )->fetch();

		$caption = $lang_module['street_edit'];

	}
	else
	{
		$caption = $lang_module['street_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['street_id'] = $nv_Request->get_int( 'street_id', 'post', 0 );
		$data['city_id'] = $nv_Request->get_int( 'city_id', 'post', 0 );
		$data['district_id'] = $nv_Request->get_int( 'district_id', 'post', 0 );
		$data['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );

		if( empty( $data['title'] ) )
		{
			$error['title'] = $lang_module['street_error_title'];
		}
		if( empty( $data['city_id'] ) )
		{
			$error['city'] = $lang_module['street_error_city'];
		}
		if( empty( $data['district_id'] ) )
		{
			$error['district'] = $lang_module['street_error_district'];
		}
		if( empty( $error ) )
		{
			$data['alias'] = change_alias( $data['title']."-".$data['street_id'] );

			if( $data['street_id'] == 0 )
			{

				$weight = $db_slave->query( 'SELECT max(weight) FROM ' . TMS_LOCALION . '_street' )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$query = 'INSERT INTO ' . TMS_LOCALION . '_street VALUES(  
					NULL,  
					' . intval( $data['district_id'] ) . ', 
					' . intval( $data['city_id'] ) . ', 
					' . $db_slave->quote( $data['title'] ) . ', 
					' . $db_slave->quote( $data['alias'] ) . ', 
					' . intval( $weight ) . ', 1 )';

				$data['street_id'] = ( int )$db_slave->insert_id( $query );

				if( $data['street_id'] > 0 )
				{

					$nv_Request->set_Session( $module_data . '_success', $lang_module['street_insert_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A street', 'street_id: ' . $data['street_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_module['street_error_save'];

				}

			}
			else
			{
				$sql = 'UPDATE ' . TMS_LOCALION . '_street SET 
					district_id = ' . intval( $data['district_id'] ) . ', 
					city_id = ' . intval( $data['city_id'] ) . ', 
					title = ' . $db_slave->quote( $data['title'] ) . ', 
					alias = ' . $db_slave->quote( $data['alias'] ) . ' 
					WHERE street_id=' . $data['street_id'];
 
				if( $db_slave->query( $sql ) )
				{
					$nv_Request->set_Session( $module_data . '_success', $lang_module['street_update_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A street', 'street_id: ' . $data['street_id'], $admin_info['userid'] );
				}
				else
				{
					$error['warning'] = $lang_module['street_error_save'];

				}

			}

			if( empty( $error ) )
			{
				$nv_Cache->delMod($module_name);
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&city_id='. $data['city_id'] . '&district_id='. $data['district_id'] );
				die();
			}

		}

	}
	$xtpl = new XTemplate( "street_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	$xtpl->assign( 'GET_DISTRICT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	
	$xtpl->assign( 'DATA', $data );

	foreach( $getCity as $key => $city )
	{
		$xtpl->assign( 'CITY', array(
			'key' => $key,
			'name' => $city['title'],
			'selected' => ( $key == $data['city_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.city' );
	}

	foreach( $getDistrict as $key => $district )
	{
		$xtpl->assign( 'DISTRICT', array(
			'key' => $key,
			'name' => $district['title'],
			'selected' => ( $key == $data['district_id'] ) ? 'selected="selected"' : '' ) );
		$xtpl->parse( 'main.district' );
	}

	if( isset( $error['title'] ) )
	{
		$xtpl->assign( 'error_title', $error['title'] );
		$xtpl->parse( 'main.error_title' );
	}

	if( isset( $error['city'] ) )
	{
		$xtpl->assign( 'error_city', $error['city'] );
		$xtpl->parse( 'main.error_city' );
	}
	
	if( isset( $error['district'] ) )
	{
		$xtpl->assign( 'error_district', $error['district'] );
		$xtpl->parse( 'main.error_district' );
	}
	
	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'error_warning', $error['warning'] );
		$xtpl->parse( 'main.error_warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

/* Hiện danh sách quốc gia*/

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 0 );
if($page==0){
	$page=1;
}
$page = ($page-1)*$per_page;

$district_id = $nv_Request->get_int( 'district_id', 'get', 0 );
$city_id = $nv_Request->get_int( 'city_id', 'get', 0 );

$sql = TMS_LOCALION . '_street WHERE 1';

if( $district_id > 0 )
{
	$sql .= ' AND district_id = ' . $district_id;
}

if( $city_id > 0 )
{
	$sql .= ' AND city_id = ' . $city_id;
}
if( $district_id > 0 ){
	$num_items = $db_slave->query( 'SELECT COUNT(*) FROM ' . $sql .' AND district_id = ' . $district_id)->fetchColumn();
}else
{
	$num_items = $db_slave->query( 'SELECT COUNT(*) FROM ' . $sql)->fetchColumn();
}
$sort = $nv_Request->get_string( 'sort', 'get', '' );

$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'title', 'weight' );

$sql .= " ORDER BY  district_id ASC";

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= ", " . $sort;
}
else
{
	$sql .= ", weight";
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=' . $sort . '&amp;order=' . $order . '&amp;per_page=' . $per_page;
$result = $db_slave->query( 'SELECT * FROM ' . $sql . ' LIMIT ' . $page . ',' . $per_page );
$array = array();

while( $rows = $result->fetch() )
{
	$array[] = $rows;
}
$xtpl = new XTemplate( "street.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;action=add&amp;district_id=' . $district_id . '&amp;city_id=' . $city_id );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

if( ! empty( $array ) )
{
	$a = 1;
	if($district_id >0){ 
		$xtpl->parse( 'main.weightshowlang' );
	}
	foreach( $array as $item )
	{

		$item['class'] = ( $a % 2 ) ? 'class="second"' : '';

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['street_id'] );

		$item['city'] = isset( $getCity[$item['city_id']] ) ? $getCity[$item['city_id']]['title'] : '';
		
		$item['district'] = isset( $getDistrict[$item['district_id']] ) ? $getDistrict[$item['district_id']]['title'] : '';

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=street&action=edit&token=" . $item['token'] . "&street_id=" . $item['street_id'];

		$item['district_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=street&district_id=" . $item['district_id'];

		$item['city_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=district&city_id=" . $item['city_id'];

 
		$xtpl->assign( 'LOOP', $item );
		if($district_id >0){ 
			
			 for( $i = 1; $i <= $num_items; ++$i )
			 {
				 $xtpl->assign( 'WEIGHT', array( 'w' => $i, 'selected' => ( $i == $item['weight'] ) ? ' selected="selected"' : '' ) );

				 $xtpl->parse( 'main.loop.weightshow.weight' );
			 }
			 $xtpl->parse( 'main.loop.weightshow' );
		}
		$xtpl->parse( 'main.loop' );
		
		++$a;
	}

}
 
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

if( ! empty( $generate_page ) )
{

	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
