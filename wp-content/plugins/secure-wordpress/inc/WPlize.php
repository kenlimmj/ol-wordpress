<?php
/**
 * WPlize [Klasse]
 *
 * DE: Updaten, Setzen, Holen und L&ouml;schen von Optionen in WordPress
 * EN: update, set, get and delete options in WordPress
 *
 * DE:
 * WPlize gruppiert und verwaltet alle Optionen eines Plugins bzw.
 * Themes in einem einzigen Optionsfeld. Die Anzahl der
 * Datenbankabfragen und somit die Ladezeit des Blogs k&ouml;nnen sich
 * sich enorm verringern. WPlize richtet sich an die Entwickler
 * von WordPress-Plugins und -Themes.
 *
 * EN:
 * WPlize regroups and manages all options of a plugin or 
 * theme in one option field. The amount of database queries
 * can be reduced and therefore the loading time of blogs
 * can be improved enormously. WPlize is designed for
 * developers of WordPress themes or plugins.
 *
 * @package  WPlize.php
 * @author   Sergej M&uuml;ller and Frank B&uuml;ltge
 * @since    26.09.2008
 * @change   09.12.2008
 * @access   public
 */


class WPlize {
	
	
	/**
	 * WPlize [Konstruktor]
	 *
	 * DE: Setzt Eigenschafen fest und startet die Initialisierung
	 * EN: set properties and start init
	 *
	 * @package  WPlize.php
	 * @author   Sergej M&uuml;ller
	 * @since    26.09.2008
	 * @change   03.12.2008
	 * @access   public
	 * @param    array  $option  Title of the multi-option in the DB [optional]
	 * @param    array  $data    Array with startvalue [optional]
	 */
	function WPlize($option = '', $data = array()) {
		if (empty($option) === true) {
			$this->multi_option = 'WPlize_'. md5(get_bloginfo('home'));
		} else {
			$this->multi_option = $option;
		}
		
		if ($data) {
			$this->init_option($data);
		}
	}
	
	
	/**
	 * init_option
	 *
	 * DE: Initialisiert die Multi-Option in der DB
	 * EN: init mulit-option in the db
	 *
	 * @package  WPlize.php
	 * @author   Sergej M&uuml;ller
	 * @since    26.09.2008
	 * @change   26.09.2008
	 * @access   public
	 * @param    array  $data  Array with startvalues [optional]
	 */
	function init_option($data = array()) {
		add_option($this->multi_option, $data);	
	}
	
	
	/**
	 * delete_option
	 *
	 * DE: Entfernt die Multi-Option aus der DB
	 * EN: delete the multi-option of the db
	 *
	 * @package  WPlize.php
	 * @author   Sergej M&uuml;ller
	 * @since    26.09.2008
	 * @change   26.09.2008
	 * @access   public
	 */
	function delete_option() {
		delete_option($this->multi_option);
	}
	
	
	/**
	 * get_option
	 *
	 * DE: Liefert den Wert einer Option
	 * EN: get the value to option
	 *
	 * @package  WPlize.php
	 * @author   Sergej M&uuml;ller
	 * @since    26.09.2008
	 * @change   26.09.2008
	 * @access   public
	 * @param    string  $key  Title of the option
	 * @return   mixed         Value of the option [false on error]
	 */
	function get_option($key) {
		if (empty($key) === true) {
			return false;
		}
		
		$data = get_option($this->multi_option);
		
		return @$data[$key];
	}
	
	
	/**
	 * update_option
	 *
	 * DE: Weist den Optionen neue Werte zu
	 * EN: Set new options to value
	 *
	 * @package  WPlize.php
	 * @author   Sergej M&uuml;ller
	 * @since    26.09.2008
	 * @change   07.12.2008
	 * @access   public
	 * @param    mixed    $key    Title of the option [alternativ Array with optionen]
	 * @param    string   $value  Value of the option [optional]
	 * @return   boolean          False on error
	 */
	function update_option($key, $value = '') {
		if (empty($key) === true) {
			return false;
		}
		
		if (is_array($key) === true) {
			$data = $key;
		} else {
			$data = array($key => $value);
		}
		
		if (is_array(get_option($this->multi_option)) === true) {
			$update = array_merge(
														get_option($this->multi_option),
														$data
													 );
		} else {
			$update = $data;
		}
		
		update_option(
									$this->multi_option,
									$update
								 );
	}
}
?>