/**
 * WPlize [class]
 *
 * update, set, get and delete options in WordPress
 *
 * WPlize regroups and manages all options of a plugin or 
 * theme in one option field. The amount of database queries
 * can be reduced and therefore the loading time of blogs
 * can be improved enormously. WPlize is designed for
 * developers of WordPress themes or plugins.
 *
 * @package  WPlize.php
 * @author   Sergej M&uuml;ller and Frank B&uuml;ltge
 * @since    26.09.2008
 * @change   26.09.2008
 * @access   public
 */


/*****************************************************************/
/*               EMBEDDING                                       */
/*****************************************************************/

if ( !class_exists('WPlize') ) {
	require_once('WPlize.php');
}


/*****************************************************************/
/*               EXAMPLE                                         */
/*****************************************************************/


/**
 * init multi-option
 *
 * @param string  Title of the multi-option
 * @param array   Array with startvalues [optional]
 */
 
$WPlize = new WPlize(
										 'my_plugin',
										 array(
										      'my_key'   => 'my_value',
										      'your_key' => 'your_value'
										      )
										);


/**
 * update option [alternative 1]
 *
 * @param string  Title of the option
 * @param string  Value of the option
 */

$WPlize->update_option('my_key', 'simple_value');


/**
 * update option [alternative 2]
 *
 * @param array  Array with optionscouple
 */

$WPlize->update_option(
											 array(
											       'my_key'   => 'my_value',
											       'your_key' => 'simple_value'
											      )
											);


/**
 * read option
 *
 * @param   string  Name of the option
 * @return  mixed   Value of the option [false on error]
 */

$WPlize->get_option('your_key');


/**
 * delete multi-option
 */

$WPlize->delete_option();