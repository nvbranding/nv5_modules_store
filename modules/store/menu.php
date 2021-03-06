<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV SYSTEMS LTD <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2019 NV SYSTEMS LTD. All rights reserved
 * @License: Not free read more http://nukeviet.systems/
 * @Createdate Sun, 17 Mar 2019 11:32:42 GMT
 */

if ( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_catalogy WHERE status=1 ORDER BY weight ASC';
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_item[$row['id']] = array(
        'key' => $row['id'],
        'title' => $row['title'],
        'alias' => $row['alias']
    );
}