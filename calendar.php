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

class GoogleCalendar
{
	protected $options, $attributes;
	
	const JQUERY = 'http://code.jquery.com/jquery-1.4.2.min.js';
	const JQUERYUI = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js';

  public function __construct($options = array(), $attributes = array())
  {
		$defaults = array('template' => 'calendar', 'limit' => '5');	
		$this->options = array_merge($defaults, $options);
		$this->attributes = $attributes;
    $this->init();
  }

	public function __toString()
	{
		return $this->render($this->getOptions(), $this->getAttributes());
	}
	
	public function getOptions()
	{
		return (array) $this->options;
	}
	
	public function getAttributes()
	{
		return (array) $this->attributes;
	}

	public function getBasePath()
	{
		return trailingslashit(WP_PLUGIN_DIR .'/'. dirname(plugin_basename(__FILE__)));;
	}
	
	public function getPath()
	{
		return '/' . PLUGINDIR .'/'. dirname(plugin_basename(__FILE__));
	}
	
	public function getUrl()
	{
		return bloginfo('siteurl').$this->getPath();
	}

  public function init()
  {
    if($this->isValid() && !is_admin())
    {
       add_action('wp_print_scripts', array(&$this, 'addJavascripts'));
       add_action('wp_print_styles', array(&$this, 'addStylesheets'));
       add_shortcode('calendar', array(&$this, 'render'));
    }
  }
	
  public function isValid($version = '2.5')
  {
    global $wp_version;
    if(version_compare($wp_version, $version, "<"))
    {
      exit($this->EXTMSG);
    }
    return true;
  }
	
	public function addStylesheets()
	{
		wp_register_style('calendar', sprintf('%s/public/plugins/fullcalendar/fullcalendar.css', $this->PLUGINURL));
		wp_enqueue_style('calendar');
	}
	
	public function addJavascripts()
	{
		wp_deregister_script('jquery'); 
		wp_register_script('jquery', self::JQUERY, false, '' );
		wp_register_script('jquery-ui', self::JQUERYUI, false, '' );
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui');
	}
	
	public function render( array $options = array(), array $attributes = array(), $path = '%s/template/%s.php' )
	{
		extract(array_merge($this->getOptions(), $options));
		$file = sprintf($path, dirname(__FILE__), $template);
    if(file_exists($file))
      include($file);
	}
}

$calendar = new GoogleCalendar();?>