<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easymanage_Order_Sync_Addon' ) ) {

   class Easymanage_Order_Sync_Addon {

    const TABLE_INDEX = 'orders_sync';

    const PARENT_UI_COMPONENET = 'menu_addons';
    const ICON_NAME = 'icons_addons';

    protected $_easymanage_order_after = 20;

     public function __construct() {
       add_action( 'init', array( $this, 'includes' ), $this->_easymanage_order_after );
     }

     public function includes() {
        if ( ! class_exists( 'WP_REST_Server' ) ) {
        	return;
        }

        if ( ! class_exists( 'WooCommerce' ) ) {
        	return;
        }

        add_action('easymanage_addons', array( $this, 'register_addon' ), $this->_easymanage_order_after);
     }

     public function register_addon($_addonClass) {
         $config = $_addonClass->getAddons();

         if(empty($config[Easymanage_Addon::PARENT_VAR][self::PARENT_UI_COMPONENET])) {
           $config[Easymanage_Addon::PARENT_VAR][self::PARENT_UI_COMPONENET] = [];
         }

         $config[Easymanage_Addon::PARENT_VAR][self::PARENT_UI_COMPONENET][] = $this->get_panel_upgrade();
         $config[Easymanage_Addon::TABLES_VAR][self::TABLE_INDEX] = $this->getTables();

         $_addonClass->setAddons($config);
     }

     protected function getTables() {
			 $triggerClass = new Easymanage_Order_Sync_Trigger();
			 $tableHeaders = $triggerClass->getTableHeader();

       return [
           'index' => self::TABLE_INDEX,
           'header' => $tableHeaders,
           'extra' => [
             'not_highlight' => true
           ],
           'title' => __('Orders', 'easymanage')
       ];

     }

     protected function get_panel_upgrade() {
       return [
         'class' => 'uicomponent-secondary',
         'static_icon' => self::ICON_NAME,
         'label' => __('Orders', 'easymanage-order-sync'),
         'childs' => [
           $this->getTitleSidebar(),
					 $this->getMenu(),
           $this->getNoteContent(),
					 $this->getRefreshLink()
         ],
         'active_table' => self::TABLE_INDEX,
       ];
     }

		 protected function getRefreshLink() {
			 return [
				 'name'   => Easymanage_Addon::UI_TYPE_LINK,
				 'params' => [
					 'href' => 'javascript:void(0)',
					 'container_style' => 'padding:5px 0; text-align:center',
					 'title' => __('Refresh orders', 'easymanage-order-sync'),
					 'onclick' => 'e.stopPropagation(); Sidebar.showLoader(); google.script.run.withSuccessHandler(Sidebar.hideLoader).withFailureHandler(Sidebar.defaultErrorProcessor.bind(Sidebar)).coreTriggerEmulateRunHome();'
					]
				];
		 }

		 protected function getMenu() {
			 return [
				 'name'   => Easymanage_Addon::UI_TYPE_MENU,
				 'params' => [
					 'childs' => [
						 [
							 'class' => Easymanage_Addon::UI_MENU_CLASS_COLUMN_MANAGER,
			         'static_icon' => Easymanage_Addon::UI_ICON_COLUMNS,
			         'label' => 'Manage columns',
			         'translate_label' => true
						 ]
					 ]
				 ]
			 ];
		 }

     protected function getNoteContent() {
       return [
         'name'   => Easymanage_Addon::UI_TYPE_HTML,
         'params' => [
           'html_content'  => '<span style="margin:10px; padding:5px; border:1px solid silver; background:#ccc; font-size:95%; display:block;">'
           . __('All new orders will be displayed in the Spreadsheet tab "Orders". Time for an update with new order about 1 min', 'easymanage-order-sync')
           . '</span>'
         ]
       ];
     }

     protected function getTitleSidebar() {
       return [
         'name'   => Easymanage_Addon::UI_TYPE_TITLE,
         'params' => [
           'label' => __('Orders synchronization', 'easymanage-order-sync'),
           'static_icon'  => self::ICON_NAME
         ]
       ];
     }
   }
}

return new Easymanage_Order_Sync_Addon();
