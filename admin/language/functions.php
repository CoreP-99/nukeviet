<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 1:58
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

unset( $page_title, $select_options );
$select_options = array();

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_language']
);

$allow_func = array( 'main' );
if( empty( $global_config['idsite'] ) )
{
	$allow_func[] = 'read';
	$allow_func[] = 'copy';
	$allow_func[] = 'edit';
	$allow_func[] = 'download';
	$allow_func[] = 'interface';
	$allow_func[] = 'check';
	$allow_func[] = 'countries';
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$allow_func[] = 'setting';
		$allow_func[] = 'write';
		$allow_func[] = 'delete';
	}
}
define( 'ALLOWED_HTML_LANG', 'a, b, blockquote, br, em, h1, h2, h3, h4, h5, h6, hr, p, span, strong' );

$allowed_html_tags = array_map( 'trim', explode( ',', ALLOWED_HTML_LANG ) );
$allowed_html_tags = '<' . implode( '><', $allowed_html_tags ) . '>';

define( 'NV_ALLOWED_HTML_LANG', $allowed_html_tags );
define( 'NV_IS_FILE_LANG', true );

$dirlang = $nv_Request->get_title( 'dirlang', 'get', '' );

/**
 * nv_admin_add_field_lang()
 *
 * @param mixed $dirlang
 * @return
 */
function nv_admin_add_field_lang( $dirlang )
{
	global $module_name, $db_config, $db, $language_array;

	if( isset( $language_array[$dirlang] ) and ! empty( $language_array[$dirlang] ) )
	{
		$add_field = true;

		$result = $db->query( 'SHOW COLUMNS FROM `' . NV_LANGUAGE_GLOBALTABLE . '_file`' );
		while( $row = $result->fetch() )
		{
			if( $row['field'] == 'author_' . $dirlang )
			{
				$add_field = false;
				break;
			}
		}

		if( $add_field == true )
		{
			$db->exec( "ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "_file` ADD `author_" . $dirlang . "` VARCHAR( 255 ) NOT NULL DEFAULT ''" );
			$db->exec( "ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "` ADD `lang_" . $dirlang . "` VARCHAR( 255 ) NOT NULL DEFAULT '', ADD `update_" . $dirlang . "` INT( 11 ) NOT NULL DEFAULT '0'" );
			$db->exec( "ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "_file` CHANGE `author_" . $dirlang . "` `author_" . $dirlang . "` TEXT CHARACTER SET utf8 COLLATE " . $db->db_collation . " NULL DEFAULT NULL" );
			$db->exec( "ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "` CHANGE `lang_" . $dirlang . "` `lang_" . $dirlang . "` TEXT CHARACTER SET utf8 COLLATE " . $db->db_collation . " NULL DEFAULT NULL" );
		}
	}
}

?>