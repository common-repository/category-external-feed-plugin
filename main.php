<?php
/* 
   Plugin Name: Category show external feed  
   Plugin URI: http://www.smartango.com/articles/wordpress-category-external-feed-plugin
   Description: Plugin for show external feed in category
   Author: Daniele Cruciani
   Version: 1.5
   Author URI: http://www.smartango.com 
*/


if (basename($_SERVER['SCRIPT_NAME']) == 'plugins.php' && isset($_GET['activate']) && $_GET['activate'] == 'true') {
  add_action('init','catfeed_install');
}

add_action('edit_category','catfeed_edit');
add_action('edit_category_form','catfeed_edit_form');
add_action("create_category",'catfeed_create');

function catfeed_get_tablename() {
 global $wpdb;
 $table_name = $wpdb->prefix.'category_ext_feed';
 return $table_name;
}

function catfeed_create($catid) {
  global $wpdb;
  $table_name = catfeed_get_tablename();
  $catfeedtitle = '';
  $catfeedurl = '';
  if($_POST['catfeedtitle']!='')
    $catfeedtitle = $wpdb->escape($_POST['catfeedtitle']);
  if($_POST['catfeedurl']!='')
    $catfeedurl = $wpdb->escape($_POST['catfeedurl']);
  $wpdb->query("REPLACE INTO `$table_name` VALUES($catid,'$catfeedtitle','$catfeedurl')");
}

function catfeed_install() {
  global $wpdb;
  $table_name = catfeed_get_tablename();

  $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
				`cat_id` INT NOT NULL,
				`feedtitle` VARCHAR(255),
				`feedurl` VARCHAR(255),
				PRIMARY KEY (`cat_id`));";
  require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
  dbDelta( $sql );
  add_option('catfeed_db_version',"1.0");
  @chmod(dirname(__FILE__).'/cache',755);
}


function catfeed_edit($par) {
  global $wpdb;
  $table_name = catfeed_get_tablename();
  if(!isset($_POST['tag_ID']) || !is_numeric($_POST['tag_ID'])) return;
  $catid = $_POST['tag_ID'];
  $catfeedtitle = '';
  $catfeedurl = '';
  if($_POST['catfeedtitle']!='')
    $catfeedtitle = $wpdb->escape($_POST['catfeedtitle']);
  if($_POST['catfeedurl']!='')
    $catfeedurl = $wpdb->escape($_POST['catfeedurl']);
  $wpdb->query("REPLACE INTO `$table_name` VALUES($catid,'$catfeedtitle','$catfeedurl')");
}

function get_catfeed_title($catid) {
  global $wpdb;
  $table_name = catfeed_get_tablename();
  return $wpdb->get_var("SELECT feedtitle FROM $table_name WHERE cat_id=$catid");
}

function get_catfeed_url($catid) {
  global $wpdb;
  $table_name = catfeed_get_tablename();
  return $wpdb->get_var("SELECT feedurl FROM $table_name WHERE cat_id=$catid");
}

function catfeed_edit_form($cat){
  ?>
	<table class="form-table">

		<tr>
			<th><label for="catfeedtitle">Category feed title</label></th>

			<td>
						   <input type="text" name="catfeedtitle" id="catfeedtitle" value="<?php echo get_catfeed_title($cat->term_id) ?>" class="regular-text" /><br />
				<span class="description">Insert title of category feed.</span>
			</td>
		</tr>

	</table>
	<table class="form-table">

		<tr>
			<th><label for="catfeedurl">Category feed url</label></th>

			<td>
						   <input type="text" name="catfeedurl" id="catfeedurl" value="<?php echo get_catfeed_url($cat->term_id) ?>" class="regular-text" /><br />
				<span class="description">Insert category feed url.</span>
			</td>
		</tr>

	</table>

<?php

}

//require "simplepie.inc";
include_once(ABSPATH . WPINC . '/class-simplepie.php');
/*
  get_catfeed_feed : return feed object
*/
function get_catfeed_feed($catid) {
  $url = get_catfeed_url($catid);
  if(!$url) return FALSE;
  $feed = new SimplePie();
  $feed->set_feed_url($url);
  //$feed->enable_cache(FALSE);
  $feed->set_cache_location(dirname(__FILE__)."/cache");
  $feed->init();
  $feed->handle_content_type();
  return $feed;
}

include "catwidget.php";

# end file main.php
