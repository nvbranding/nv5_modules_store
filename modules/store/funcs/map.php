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


$xtpl = new XTemplate( 'map.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
			
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'module_name', $module_name );
			
			$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
			
			$global_array_store = array();
			$sql = 'SELECT * FROM '. STORE . '_catalogy WHERE status > 0';
			$list = $nv_Cache->db($sql, 'id', $module_name);
			if (!empty($list)) {
				foreach ($list as $l) {
					$global_array_store[$l['id']] = $l;
					$global_array_store[$l['id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				}
			}
		   $id_tinhthanh  = $id_quanhuyen = $id_xaphuong = 0;
		   
		   $where ='';
			$catid_search = 0;
			if(count($array_op) > 0)
			{
				
				if(!empty($array_op[1]))
				$catid_search = $db->query('SELECT id FROM '. STORE . '_catalogy WHERE alias like "%'. $array_op[1] .'%"')->fetchColumn();
				
				if($catid_search)
				{
					if($catid_search > 0)
					{
						$base_url .= '/'. $array_op[1];
						$where .=' AND catalog='.$catid_search;
					}
				
					elseif(isset($catid) and $catid > 0)
					{
						$where .=' AND catalog='.$catid;
						$catid_search = $catid;
					}	
				
					// tiếp tục phân tích tỉnh thành quận huyện xã phường
					if(!empty($array_op[2]))
					{
						// TÌM ID TỈNH THÀNH DỰA VÀO ALIAS 
						$id_tinhthanh = $db->query("SELECT provinceid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_province WHERE alias like '". $array_op[2] ."'  ORDER BY weight ASC")->fetchColumn();
						if($id_tinhthanh > 0)
						{
							$base_url .= '/'.$array_op[2];
							$where .=' AND tinhthanh='.$id_tinhthanh;
						}
					}
					
					if(!empty($array_op[2]) and !empty($array_op[3]) and $id_tinhthanh > 0 )
					{
						$id_quanhuyen = $db->query("SELECT districtid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_district WHERE provinceid =". $id_tinhthanh ." AND alias like '". $array_op[3] ."'")->fetchColumn();
						if($id_quanhuyen > 0)
						{
							$base_url .= '/'. $array_op[3];
							$where .=' AND quanhuyen='.$id_quanhuyen;
						}
					}
					
					if(!empty($array_op[1]) and !empty($array_op[3]) and !empty($array_op[4])  and $id_tinhthanh > 0 and $id_quanhuyen > 0)
					{
						$id_xaphuong = $db->query("SELECT wardid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_ward WHERE  districtid =". $id_quanhuyen ." AND alias like '". $array_op[4] ."'")->fetchColumn();
						if($id_xaphuong > 0)
						{
							$base_url .= '/'. $array_op[4];
							$where .=' AND xaphuong='.$id_xaphuong;
						}
					}
					
				}
				else
				{
					
					// tiếp tục phân tích tỉnh thành quận huyện xã phường
					if(!empty($array_op[1]))
					{
						// TÌM ID TỈNH THÀNH DỰA VÀO ALIAS 
						$id_tinhthanh = $db->query("SELECT provinceid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_province WHERE alias like '". $array_op[1] ."'  ORDER BY weight ASC")->fetchColumn();
						if($id_tinhthanh > 0)
						{
							$base_url .= '/'.$array_op[1];
							$where .=' AND tinhthanh='.$id_tinhthanh;
						}
					}
					
					if(!empty($array_op[1]) and !empty($array_op[2]) and $id_tinhthanh > 0 )
					{
						$id_quanhuyen = $db->query("SELECT districtid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_district WHERE provinceid =". $id_tinhthanh ." AND alias like '". $array_op[2] ."'")->fetchColumn();
						if($id_quanhuyen > 0)
						{
							$base_url .= '/'. $array_op[2];
							$where .=' AND quanhuyen='.$id_quanhuyen;
						}
					}
					
					if(!empty($array_op[1]) and !empty($array_op[2]) and !empty($array_op[3])  and $id_tinhthanh > 0 and $id_quanhuyen > 0)
					{
						$id_xaphuong = $db->query("SELECT wardid FROM ".$db_config['dbsystem']. "." .$db_config['prefix']. "_location_ward WHERE  districtid =". $id_quanhuyen ." AND alias like '". $array_op[3] ."'")->fetchColumn();
						if($id_xaphuong > 0)
						{
							$base_url .= '/'. $array_op[3];
							$where .=' AND xaphuong='.$id_xaphuong;
						}
					}
					
				
				}
				
				
				
				
			}
			
			
			
			
			// $sql = 'SELECT * FROM '. STORE . '_rows WHERE status=1 '. $where .' ORDER BY weight DESC';
			 
			$per_page = 20;
			$page = $nv_Request->get_int( 'page', 'post,get', 1 );
			$db->sqlreset()
				->select( 'COUNT(*)' )
				->from( '' . STORE . '_rows' )
				->where('status=1 '. $where);
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
			$num_items = $sth->fetchColumn();

			$db->select( '*' )
				->order( 'weight ASC' )
				->limit( $per_page )
				->offset( ( $page - 1 ) * $per_page );
			$sth = $db->prepare( $db->sql() );

			$sth->execute();
			
			
			$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
			if( !empty( $generate_page ) )
			{
				$xtpl->assign( 'NV_GENERATE_PAGE', $generate_page );
				$xtpl->parse( 'main.generate_page' );
			}
			
			 //$list_store = $db->query($sql)->fetchAll();
			// print_r($sql);die; 
			while( $row = $sth->fetch() )
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
				$xtpl->assign( 'anh_chinhanh', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' .$module_upload. '/' .$global_array_store[$row['catalog']]['image']);
				$row['tinhthanh'] = tinhthanh($row['tinhthanh']);
				$row['quanhuyen'] = quanhuyen($row['quanhuyen']);
				$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'] ;;
				$xtpl->assign( 'row', $row );
				$xtpl->parse( 'main.loop' );
				$xtpl->parse( 'main.loop_left' );
			}
			$sql = 'SELECT * FROM '  .$db_config['dbsystem']. '.' .$db_config['prefix']. '_location_province ORDER BY weight ASC';
			$global_raovat_city = $nv_Cache->db( $sql, 'provinceid', 'location' );
			foreach( $global_raovat_city as $key => $item )
			{
				$xtpl->assign( 'CITY', array(
					'key' => $key,
					'alias' =>  $item['alias'],
					'name' => $item['title'],
					'selected' => ( $id_tinhthanh == $key ) ? 'selected="selected"' : '' ) );
				$xtpl->parse( 'main.city' );
			}
			if( $id_tinhthanh )
			{
				$sql = 'SELECT districtid, title, alias, type FROM ' .$db_config['dbsystem']. '.' .$db_config['prefix']. '_location_district WHERE status = 1 AND provinceid= ' . intval( $id_tinhthanh ) . ' ORDER BY weight ASC';
				$result = $db->query( $sql );
				while( $data = $result->fetch() )
				{
					$xtpl->assign( 'DISTRICT', array(
						'key' => $data['districtid'],
						'alias' =>  $data['alias'],
						'type' =>  $data['type'],
						'name' => $data['title'],
						'selected' => ( $data['districtid'] == $id_quanhuyen) ? 'selected="selected"' : '' ) );
					$xtpl->parse( 'main.district' );
				}
			}
			if( $id_quanhuyen )
			{
				$sql = 'SELECT wardid, title, alias, type FROM ' .$db_config['dbsystem']. '.' .$db_config['prefix']. '_location_ward WHERE status = 1 AND districtid= ' . intval( $id_quanhuyen );
				$result = $db->query( $sql );
				while( $data = $result->fetch() )
				{
					$xtpl->assign( 'WARD', array(
						'key' => $data['wardid'],
						'alias' =>  $data['alias'],
						'type' =>  $data['type'],
						'name' => $data['title'],
						'selected' => ( $data['wardid'] == $id_xaphuong ) ? 'selected="selected"' : '' ) );
					$xtpl->parse( 'main.ward' );
				}
			}
			// xuất danh sách loại sản phẩm ra
			$sql = 'SELECT id, title, alias FROM '. STORE . '_catalogy ORDER BY weight ASC';
			
			$result = $db->query( $sql );
				while( $data = $result->fetch() )
				{
					$xtpl->assign( 'CATALOGY', array(
						'id' => $data['id'],
						'title' =>  $data['title'],
						'alias' =>  $data['alias'],
						'selected' => ( $data['id'] == $catid_search ) ? 'selected="selected"' : '' ) );
					$xtpl->parse( 'main.CATALOGY' );
				}
			//print_r($global_array_store);die;
			
            $xtpl->parse( 'main' );
			$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
