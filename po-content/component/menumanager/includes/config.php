<?php
/*
 *
 * - PopojiCMS Admin File
 *
 * - File : config.php
 * - Version : 1.0
 * - Author : Jenuar Dalapang
 * - License : MIT License
 *
 *
 * Ini adalah file php untuk konfigurasi menu manager.
 * This is a php file to configuration menu manager.
 *
*/

// Tables Settings
define('MENU_TABLE', 'menu');
define('MENUGROUP_TABLE', 'menu_group');

// Fields Settings
define('MENU_ID', 'id');
define('MENU_PARENT', 'parent_id');
define('MENU_TITLE', 'title');
define('MENU_URL', 'url');
define('MENU_CLASS', 'class');
define('MENU_POSITION', 'position');
define('MENU_GROUP', 'group_id');
define('MENU_ACTIVE', 'active');
define('MENU_TARGET', 'target');
define('MENUGROUP_ID', 'id');
define('MENUGROUP_TITLE', 'title');
?>