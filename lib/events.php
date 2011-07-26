<?php
require_once( '../../../../wp-load.php' );

global $Calendar, $wpdb;
if(isset($_GET['id']))
{
	$events = $wpdb->get_results("SELECT * FROM wp_calendar WHERE category_id = '" . $_GET['id'] . "'" );
}
else 
{
	$events = $wpdb->get_results("SELECT * FROM wp_calendar" );
}

$final = array();
foreach($events as $event){
	$ret_data = array();
	$ret_data['id'] = $event ->ID;
	$ret_data['title'] = $event -> title;
	$ret_data['start'] = $event -> begin;
	$ret_data['end'] = $event -> end;
	$ret_data['url'] = 'events/?id=' . $event->ID;
	$final[] = $ret_data;
}
echo json_encode($final);
?>
