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

$getcity = getCity();

$page_title = $lang_module['city'];

if( ACTION_METHOD == 'weight' )
{
	$json = array();

	$city_id = $nv_Request->get_int( 'city_id', 'post', 0 );

	$token = $nv_Request->get_title( 'token', 'post', '', 1 );

	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

	if( ! isset( $json['error'] ) )
	{
		if( $new_vid > 0 )
		{
			$sql = 'SELECT city_id FROM ' . TMS_LOCALION . '_city WHERE city_id != ' . $city_id . ' ORDER BY weight ASC';

			$result = $db_slave->query( $sql );

			$weight = 0;

			while( $row = $result->fetch() )
			{
				$weight++;

				if( $weight == $new_vid ) $weight++;

				$query = 'UPDATE ' . TMS_LOCALION . '_city SET weight=' . $weight . " WHERE city_id=" . $row['city_id'];
				$db_slave->query( $query );
			}

			$query = 'UPDATE ' . TMS_LOCALION . '_city SET weight=' . $new_vid . ' WHERE city_id=' . $city_id;

			$db_slave->query( $query );

			$nv_Cache->delMod( $module_name );

			nv_insert_logs( NV_LANG_DATA, $module_name, 'Change Weight city', "city_id: " . $city_id, $admin_info['userid'] );

			$json['success'] = $lang_module['city_update_success'];
		}
	}

	get_output_json( $json );

}

if( ACTION_METHOD == 'delete' )
{
	$json = array();

	$city_id = $nv_Request->get_int( 'city_id', 'post', 0 );

	$token = $nv_Request->get_title( 'token', 'post', '', 1 );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );

	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $city_id ) )
	{
		$del_array = array( $city_id );
	}

	if( ! empty( $del_array ) )
	{

		$_del_array = array();

		$a = 0;
		foreach( $del_array as $city_id )
		{

			if( $ward_total = $db_slave->query( 'SELECT COUNT(*) total FROM ' . TMS_LOCALION . '_ward WHERE city_id = ' . ( int )$city_id )->fetchColumn() )
			{
				$json['error'] = sprintf( $lang_module['city_error_ward'], $ward_total );
			}
			elseif( $district_total = $db_slave->query( 'SELECT COUNT(*) total FROM ' . TMS_LOCALION . '_district WHERE city_id = ' . ( int )$city_id )->fetchColumn() )
			{
				$json['error'] = sprintf( $lang_module['city_error_district'], $district_total );
			}
			else
			{
				$db_slave->query( 'DELETE FROM ' . TMS_LOCALION . '_city WHERE city_id = ' . ( int )$city_id );

				$json['id'][$a] = $city_id;

				$_del_array[] = $city_id;

				++$a;
			}
		}

		$count = sizeof( $_del_array );

		if( $count )
		{
			fixWeightCity();

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_city', implode( ', ', $_del_array ), $admin_info['userid'] );

			$nv_Cache->delMod( $module_name );

			$json['success'] = $lang_module['city_delete_success'];
		}

	}
	else
	{
		$json['error'] = $lang_module['city_error_security'];
	}

	get_output_json( $json );

}

if( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{
	$data = array(
		'city_id' => 0,
		'title' => '',
		'alias' => '',
		'weight' => 0,
		'status' => 0,
		'area' => 0,
		'important' => 0,
		);
	$error = array();

	$data['city_id'] = $nv_Request->get_int( 'city_id', 'get,post', 0 );

	if( $data['city_id'] > 0 )
	{
		$result = $db_slave->query( 'SELECT * FROM ' . TMS_LOCALION . '_city WHERE city_id=' . $data['city_id'] );
		$data = $result->fetch();
		$caption = $lang_module['city_edit'];

	}
	else
	{
		$caption = $lang_module['city_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['city_id'] = $nv_Request->get_int( 'city_id', 'post', 0 );
		$data['area'] = $nv_Request->get_int( 'area', 'post', 0 );
		$data['important'] = $nv_Request->get_int( 'important', 'post', 0 );
		$data['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );

		if( empty( $data['title'] ) )
		{
			$error['title'] = $lang_module['city_error_title'];
		}

		if( empty( $error ) )
		{
			$data['alias'] = change_alias( $data['title'] );

			if( $data['city_id'] == 0 )
			{

				$weight = $db_slave->query( 'SELECT max(weight) FROM ' . TMS_LOCALION . '_city' )->fetchColumn();
				$weight = intval( $weight ) + 1;

				$query = 'INSERT INTO ' . TMS_LOCALION . '_city(title,alias,weight,status,area,important) VALUES(   
					' . $db_slave->quote( $data['title'] ) . ', 
					' . $db_slave->quote( $data['alias'] ) . ', 
					' . intval( $weight ) . ', 1, 
					' . intval( $data['area'] ) . ', 
					' . intval( $data['important'] ) . '  
					)';
				$data['city_id'] = ( int )$db_slave->insert_id( $query );

				if( $data['city_id'] > 0 )
				{

					$nv_Request->set_Session( $module_data . '_success', $lang_module['city_insert_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A city', 'city_id: ' . $data['city_id'], $admin_info['userid'] );

				}
				else
				{
					$error['warning'] = $lang_module['city_error_save'];

				}

			}
			else
			{
				$sql = 'UPDATE ' . TMS_LOCALION . '_city SET 
					title = ' . $db_slave->quote( $data['title'] ) . ', 
					alias = ' . $db_slave->quote( $data['alias'] ) . ',
					area = ' . intval( $data['area'] ) . ', 
					important = ' . intval( $data['important'] ) . '  					
					WHERE city_id=' . $data['city_id'];

				if( $db_slave->query( $sql ) )
				{
					$nv_Request->set_Session( $module_data . '_success', $lang_module['city_update_success'] );

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A city', 'city_id: ' . $data['city_id'], $admin_info['userid'] );
				}
				else
				{
					$error['warning'] = $lang_module['city_error_save'];

				}

			}

			if( empty( $error ) )
			{
				$nv_Cache->delMod( $module_name );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}

		}

	}
	
	
	$data['important'] = ( $data['important'] == 1 ) ? 'checked="checked"' : '';
	
	$xtpl = new XTemplate( "city_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
	$xtpl->assign( 'DATA', $data );
	
	
	$array_area = array('1'=> 'Miền bắc', '2'=> 'Miền Trung', '3'=> 'Miền Nam');
	foreach( $array_area as $key => $name )
	{
		$xtpl->assign( 'AREA', array( 'key'=> $key, 'name'=> $name, 'selected'=> ( $data['area'] == $key ) ? 'selected="selected"': '' ) );
		$xtpl->parse( 'main.area' );
	}
	
	
	if( isset( $error['city'] ) )
	{
		$xtpl->assign( 'error_city', $error['city'] );
		$xtpl->parse( 'main.error_city' );
	}
	if( isset( $error['title'] ) )
	{
		$xtpl->assign( 'error_title', $error['title'] );
		$xtpl->parse( 'main.error_title' );
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

/* Hiện danh sách thành phố*/

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );

$city_id = $nv_Request->get_int( 'city_id', 'get', 0 );

$sql = TMS_LOCALION . '_city WHERE 1';

if( $city_id > 0 )
{
	$sql .= ' AND city_id = ' . $city_id;
}

$db_slave->sqlreset()->select( 'COUNT(*)' )->from( $sql );

$num_items = $db_slave->query( $db_slave->sql() )->fetchColumn();

$sort = $nv_Request->get_string( 'sort', 'get', '' );

$order = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$sort_data = array( 'title', 'weight' );

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= ' ORDER BY ' . $sort;
}
else
{
	$sql .= ' ORDER BY weight';
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= ' DESC';
}
else
{
	$sql .= ' ASC';
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db_slave->sqlreset()->select( '*' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db_slave->query( $db_slave->sql() );

$array = array();

while( $rows = $result->fetch() )
{
	$array[] = $rows;
}



$xtpl = new XTemplate( "city.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;action=add' );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

if( ! empty( $array ) )
{
	$a = 1;
	foreach( $array as $item )
	{

		$item['class'] = ( $a % 2 ) ? 'class="second"' : '';

		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['city_id'] );

		$item['distrist_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=district&city_id=" . $item['city_id'];

		$item['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=city&action=edit&token=' . $item['token'] . '&city_id=' . $item['city_id'];

		$xtpl->assign( 'LOOP', $item );

		for( $i = 1; $i <= $num_items; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array( 'w' => $i, 'selected' => ( $i == $item['weight'] ) ? ' selected="selected"' : '' ) );

			$xtpl->parse( 'main.loop.weight' );
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
