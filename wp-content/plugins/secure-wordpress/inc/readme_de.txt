/**
 * WPlize [Klasse]
 *
 * Updaten, Setzen, Holen und Löschen von Optionen in WordPress
 *
 * WPlize gruppiert und verwaltet alle Optionen eines Plugins bzw.
 * Themes in einem einzigen Optionsfeld. Die Anzahl der
 * Datenbankabfragen und somit die Ladezeit des Blogs können sich
 * sich enorm verringern. WPlize richtet sich an die Entwickler
 * von WordPress-Plugins und -Themes.
 *
 * @package  WPlize.php
 * @author   Sergej Müller und Frank Bültge
 * @since    26.09.2008
 * @change   10.11.2008 15:41:36
 * @access   public
 */


/*****************************************************************/
/*               EINBINDEN                                       */
/*****************************************************************/

if ( !class_exists('WPlize') ) {
	require_once('WPlize.php');
}


/*****************************************************************/
/*               BEISPIELE                                       */
/*****************************************************************/


/**
 * Multi-Option initialisieren
 *
 * @param	string  Name der Multi-Option
 * @param	array   Array mit Anfangswerten [optional]
 */
 
$WPlize = new WPlize(
										 'my_plugin',
										 array(
										      'my_key'   => 'my_value',
										      'your_key' => 'your_value'
										      )
										);


/**
 * Option updaten [Variante 1]
 *
 * @param	string  Name der Option
 * @param	string	Wert der Option
 */

$WPlize->update_option('my_key', 'simple_value');


/**
 * Option updaten [Variante 2]
 *
 * @param	array  Array mit Optionspaaren
 */

$WPlize->update_option(
											 array(
											       'my_key'   => 'my_value',
											       'your_key' => 'simple_value'
											      )
											);


/**
 * Option auslesen
 *
 * @param   string  Name der Option
 * @return  mixed   Wert der Option [false im Fehlerfall]
 */

$WPlize->get_option('your_key');


/**
 * Multi-Option entfernen
 */

$WPlize->delete_option();