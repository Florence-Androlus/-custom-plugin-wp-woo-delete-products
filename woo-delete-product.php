<?php 
/* 
* @wordpress-plugin
* Plugin Name: Woo Delete Products
* Description:       Automatisation de mise en status brouillon des produits dans WooCommerce et redirection de ceux-ci dans leur catégorie parent dans le plugin Redirection
* Version:           1.0.2
* Author:            Fan-Develop
* Author URI:        https://fan-develop.fr
* Requires at least: 5.9
* Requires PHP: 7.4.0
*/

namespace wdp;

// on utilise l'autoload PSR4 de composer
require __DIR__ . '/vendor/autoload.php';

/* Chemin vers ce fichier dans une constante
* => sera utile pour les hook d'activation et désactivation
*/
define('WDP_MAIN_FILE', __FILE__);
define( 'WDP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WDP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/* If this file is called directly, abort.*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once WDP_PLUGIN_DIR . 'inc/scripts.php';
require_once WDP_PLUGIN_DIR . 'plugin.php';

new WDPSettingsPage;