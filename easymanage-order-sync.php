<?php
/*
 * Plugin Name: Easymanage Orders sync
 * Plugin URI:  https://easymanage.biz
 * Description: Export orders from Woocommerce to Google Spreadsheet automatically. Requires Easymange plugin 1.0.3
 * Author:      Easymanage Team
 * Version:     1.0.1
 * Text Domain: easymanage-order-sync
 * Domain Path: /languages/
 *
 * WC requires at least: 	3.6.0
 * WC tested up to: 3.7.1
 *
 * Copyright: © 2019 easymanage, (easymanage.biz@gmail.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

 if ( ! class_exists( 'Easymanage_Order_Sync' ) ) {

  class Easymanage_Order_Sync {

    protected static $_instance = null;

    protected $_easymanage_plugin_order = 15; //!important run the plugin after easymanage main module includes

    protected $_easymanage_min_version = '1.0.3';

    public static $version = '1.0.1';

    public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

    public function __construct() {
      $this->setup_constants();
      add_action( 'init', array( $this, 'includes' ), $this->_easymanage_plugin_order );
		}

    public function setup_constants() {
      $this->define('EASYMANAGE_ORDER_SYNC_VERSION', self::$version);
			$this->define('EASYMANAGE_ORDER_SYNC_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
    }

    public function includes() {
      if(!defined('EASYMANAGE_VERSION')) {
        add_action( 'admin_notices', array( $this, 'child_plugin_notice' ) );
        return;
      }

      $current_easymanage_version = EASYMANAGE_VERSION;
      if(!version_compare ($this->_easymanage_min_version, $current_easymanage_version, '<=' )) {
        add_action( 'admin_notices', array( $this, 'child_plugin_version_notice' ) );
        return;
      }

      $this->register_trigger();
      $this->register_addon();
    }

    protected function register_addon() {
      require_once EASYMANAGE_ORDER_SYNC_PATH . '/includes/easymanage-order-sync-addon.php';
    }

    protected function register_trigger() {
      require_once EASYMANAGE_ORDER_SYNC_PATH . '/includes/easymanage-order-sync-trigger.php';
      $triggerClass = new Easymanage_Order_Sync_Trigger();
      $triggerClass->__init();
    }

    private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

    public function child_plugin_version_notice() {
      ?>
      <div class="error"><p> Sorry, but "Easymanage Orders sync" requires at least <a href="https://wordpress.org/plugins/easymanage/" target="blank">"Easymanage"</a> version 1.0.3
        </p></div>';
      <?php
    }

    public function child_plugin_notice() {
      ?>
      <div class="error"><p> Sorry, but "Easymanage Orders sync" requires the <a href="https://wordpress.org/plugins/easymanage/" target="blank">"Easymanage"</a> plugin to be installed and active
        </p></div>;
      <?php
    }
  }

}


return Easymanage_Order_Sync::instance();
