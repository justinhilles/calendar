<?php
/*
Plugin Name: Calendar2.0
Plugin URI: http://www.justinhilles.com
Description: Calendar
Author: Justin Hilles
Author URI: http://www.justinhilles.com
Version: 0.1

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Calendar
{
	
  var $vars;
  var $table = 'wp_calendar';

  public function __construct()
  {
    register_activation_hook(__FILE__, array($this,'install'));
    $this->PLUGINPATH = trailingslashit(WP_PLUGIN_DIR .'/'. dirname(plugin_basename(__FILE__)));
    $this->PLUGINURL  = '/' . PLUGINDIR .'/'. dirname(plugin_basename(__FILE__));
    $this -> vars = $_REQUEST;
    $this -> init();
  }

  public function init()
  {
    if($this->version_check())
    {
      if(is_admin())
      {
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_controller'));
      }
      else
      {
        add_action('wp_print_scripts', array(&$this, 'public_scripts'));
        add_action('wp_print_styles', array(&$this, 'public_styles'));
        add_shortcode('calendar', array(&$this, 'shortcode'));
      }
    }
  }
	
  public function install()
  {
    ob_start();
    include(dirname(__FILE__) . '/lib/data/database.sql.php');
    $sql = ob_get_contents();
    ob_end_clean();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
	
  public function version_check()
  {
    global $wp_version;
    if(version_compare($wp_version,"2.5","<"))
    {
      exit($this->EXTMSG);
    }
    return true;
  }
	
  public function getData()
  {
    if(isset($_POST['data']))
    {
      $this->fields = $this -> showColumns($this->table);
      $this->data = array_intersect_key( $_REQUEST['data'] , $this->fields );
      $this -> data['begin'] = $this -> formatDateTime($_REQUEST['datebegin'], $_REQUEST['timebegin']);
      $this -> data['end'] = $this -> formatDateTime($_REQUEST['dateend'], $_REQUEST['timeend']);
      return $this->data;
    }
  }
	
	function showColumns( $table ){
		global $wpdb;
		$fields =  $wpdb->get_results("SHOW COLUMNS FROM $table");
		foreach($fields as $field){$temp[$field->Field] = $field->Field;}
		return $temp;
	}
	
	function _New()
	{
		$fields = $this -> showColumns($this->table);
		$keys = array_keys($fields);
		$ret_data = array_fill_keys($keys, '');
		$ret_data = $this -> a2o($ret_data);
		$ret_data->datebegin = null;
		$ret_data->dateend = null;
		$ret_data->timebegin = null;
		$ret_data->timeend = null;
		$this -> event = $ret_data;
	}
	
	function Insert()
	{
		global $wpdb;
		$data = $this -> getData();
		$wpdb->insert( $this->table , $data );
		return $wpdb->insert_id;
	}
	
	function formatDateTime($date, $time)
	{
		return date('Y-m-d G:i:s',strtotime($date . $time));	
	}
	
	function Update($id)
	{
		global $wpdb;
		$data = $this -> getData();
		$wpdb->update( $this->table , $data, array('ID' => $id) );
		return $wpdb->insert_id;
	}
	
	function Find( $id = null )
	{
		global $wpdb;
		if(!empty($id)):
			return $wpdb->get_row("SELECT * FROM $this->table WHERE `ID` = '" . $id . "'" );
		else:
			$sql = "SELECT * FROM $this->table";
			return $wpdb->get_results($sql);
		endif;
	}
	
	public function admin_controller()
	{
		if(isset($this->vars['page']) && $view = $this->vars['page']):
		if(isset($view)):
			switch($view)
			{
				case 'calendar':
					$this -> events = $this -> Find();
				break;
				case 'create-event':
					if(isset($_POST['data'])):
						$id = $this -> Insert();
						wp_redirect(admin_url('admin.php?page=edit-event&id=' . $id));
					endif;
					$this -> _New();
				break;
				case 'edit-event':
					$id = $_GET['id'];
					if(isset($_POST['data'])):
						$result = $this -> Update($id);
					endif;
					$event = $this -> Find($id);
					$event->datebegin = date('m/d/Y', strtotime($event -> begin));
					$event->timebegin = date('h:ia',  strtotime($event -> begin));
					$event->dateend   = date('m/d/Y', strtotime($event -> end));
					$event->timeend   = date('h:ia',  strtotime($event -> end));
					$this -> event = $event;
				break;
			}
		endif;
		endif;
	}
	
	public function admin_view()
	{
                add_action('admin_print_scripts', array(&$this, 'admin_scripts'   ));
                add_action('admin_print_styles',  array(&$this, 'admin_styles'    ));
		$view = $this->vars['page'];
		$path = 'template/admin/';
		if(file_exists($this->PLUGINPATH ."/". $path . $view . '.php')):include($path . $view . '.php');
		else:echo "<h3>Page Template Not Found</h3><p>Please create file: " . $this->PLUGINPATH . $path ."<b>" . $view . ".php</b>";endif;
	}
	
	public function admin_menu()
	{
	  add_menu_page('Calendar', 'Calendar', 10 , 'calendar', array(&$this, 'admin_view'));
		add_submenu_page( 'calendar', 'Add Event', 'Add Event', 10 , 'create-event', array(&$this, 'admin_view'));	
		add_submenu_page( 'calendar', '', '', 10 , 'edit-event', array(&$this, 'admin_view'));
	}
	
	public function admin_styles()
	{
		wp_register_style('jquery-ui',  $this->PLUGINURL . '/public/plugins/datepicker/ui.all.css');
		wp_enqueue_style( 'jquery-ui');
		
		wp_register_style('jquery-ui-custom',  $this->PLUGINURL . '/public/plugins/timepicker/css/ui-lightness/jquery-ui-1.7.2.custom.css');
		wp_enqueue_style( 'jquery-ui-custom');
		
		wp_register_style('jquery-timepicker',  $this->PLUGINURL . '/public/plugins/timepicker/css/km.timepicker.css');
		wp_enqueue_style( 'jquery-timepicker');
	}
	
	public function admin_scripts()
	{
	//	wp_deregister_script( 'jquery' ); //Deregister WP Jquery, Its Old
		wp_register_script(   'jquery', 'http://code.jquery.com/jquery-1.4.2.min.js', false, '' );
		wp_register_script(   'jquery-ui-core', $this->PLUGINURL . '/public/plugins/datepicker/jquery.ui.core.js', false, '' );
		wp_register_script(   'jquery-ui-widget', $this->PLUGINURL . '/public/plugins/datepicker/jquery.ui.widget.js', false, '' );
		wp_register_script(   'jquery-datepicker', $this->PLUGINURL . '/public/plugins/datepicker/jquery.ui.datepicker.js', false, '' );
		wp_register_script(   'jquery-slider', $this->PLUGINURL . '/public/plugins/timepicker/js/jquery-ui-1.7.2.core.slider.js', false, '' );
		wp_register_script(   'jquery-timepicker', $this->PLUGINURL . '/public/plugins/timepicker/js/km.timepicker.js', false, '' );
		wp_enqueue_script(    'jquery');
		wp_enqueue_script(    'jquery-ui-core');
		wp_enqueue_script(    'jquery-ui-widget');
		wp_enqueue_script(    'jquery-datepicker');
		wp_enqueue_script(    'jquery-slider');
		wp_enqueue_script(    'jquery-timepicker');
	}
	
	function a2o($array) {
		$object = new stdClass();
		if (is_array($array) && count($array) > 0) {
		  foreach ($array as $name=>$value) {
			 $name = strtolower(trim($name));
			 if (!empty($name)) {
				$object->$name = $value;
			 }
		  }
		}
		return $object;
	}
	
	public function public_styles()
	{
		wp_register_style('calendar', $this->PLUGINURL . '/public/plugins/fullcalendar/fullcalendar.css');
		wp_enqueue_style( 'calendar');
	}
	
	public function public_scripts()
	{
		wp_deregister_script( 'jquery' ); 
		wp_register_script(   'jquery'            , 'http://code.jquery.com/jquery-1.4.2.min.js', false, '' );
		wp_register_script(   'jquery-ui'         , 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js', false, '' );
		wp_enqueue_script(    'jquery' );
		wp_enqueue_script(    'jquery-ui' );
	}
	
	public function shortcode( $atts = array(), $content = null, $code = null)
	{
		$template = 'calendar';
		if(is_array($atts))
		{
                  extract($atts);
		}
                if(file_exists(sprintf(dirname(__FILE__).'/template/%s.php',$template)))
                {
                  include(sprintf(dirname(__FILE__).'/template/%s.php',$template));
                }
		
	}
}

$Calendar = new Calendar();
?>