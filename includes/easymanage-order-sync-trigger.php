<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easymanage_Order_Sync_Trigger' ) ) {
  class Easymanage_Order_Sync_Trigger {

    protected $_trigger_order = 100;
    protected $_data = [];

    protected $_widthHeaders = [
      'order_items' => 400
    ];

    protected $_keysMainOne = [
      'status' => 'Status',
      'currency' => 'Currency',
      'discount_total' => 'Discount',
      'total' => 'Total',
      'total_tax' => 'Tax',
			'payment_method_title' => 'Payment',
			'shipping_method' => 'Shipping method',
			'shipping_total' => 'Shipping Total',

    ];

    protected $_keysAdditional = [
      'shipping' => 'Shipping',
      'billing'  => 'Billing',
      'order_id' => 'Order id',
      'order_items' => 'Order Items'
    ];

    protected $_keysAddress = [
      'first_name' => 'First Name',
      'last_name'  => 'Last Name',
      'company'    => 'Company',
      'address_1'  => 'Addres 1',
      'address_2'  => 'Addres 2',
      'city'       => 'City',
      'state'      => 'State',
      'postcode'   => 'Postcode',
      'country'    => 'Country code',
      'email'      => 'Email',
      'phone'      => 'phone'
    ];

    public function __init() {
      add_action( 'init', array( $this, 'includes' ), $this->_trigger_order);
    }

    public function includes() {
       if ( ! class_exists( 'WP_REST_Server' ) ) {
         return;
       }

       if ( ! class_exists( 'WooCommerce' ) ) {
         return;
       }
       add_action('woocommerce_checkout_order_processed', array( $this, 'register_new_order_trigger' ), $this->_trigger_order);
    }

    public function getTableHeader() {
      $tableHeaders = [];

      $tableHeaders[] = [
        'name' => 'order_id',
        'label' => $this->getDataHeaderTitle($this->_keysAdditional, 'order_id')
      ];

      foreach($this->_keysMainOne as $key => $title) {
        $tableHeaderData = [
          'name' => $key,
          'label' => $this->getDataHeaderTitle($this->_keysMainOne, $key)
        ];

        if(!empty($this->_widthHeaders[$key])) {
          $tableHeaderData['width'] = $this->_widthHeaders;
        }

        $tableHeaders[] = $tableHeaderData;
      }

      $tableHeaders[] = [
        'name' => 'order_items',
        'label' => $this->getDataHeaderTitle($this->_keysAdditional, 'order_items'),
        'width' => 450
      ];


      foreach($this->_keysAddress as $key => $title) {
        $key_prefix = 'billing';
        $tableHeaders[] = [
          'name'  => $key . $key_prefix,
          'label' => $this->getDataHeaderTitle($this->_keysAddress, $key, $key_prefix),
        ];
      }


      foreach($this->_keysAddress as $key => $title) {
        $key_prefix = 'shipping';
        $tableHeaders[] = [
          'name'  => $key . $key_prefix,
          'label' => $this->getDataHeaderTitle($this->_keysAddress, $key, $key_prefix),
        ];
      }
      return $tableHeaders;
    }

    public function register_new_order_trigger($order_id) {
      $order = new WC_Order( $order_id );
      if(!$order) {
        return;
      }
      $this->_data['order_id']    = $order_id;
      $this->prepareDataByConfArray($this->_keysMainOne, $order);
      $this->prepareOrderItems($order);

      $dataBilling  = $order->get_address('billing');
      $this->prepareDataByConfArray($this->_keysAddress, $dataBilling, 'billing');
      $dataShipping = $order->get_address('shipping');
      $this->prepareDataByConfArray($this->_keysAddress, $dataBilling, 'shipping');

      $triggerMainClass = new Easymanage_Trigger();
      $triggerMainClass->createTriggerInsertRow('orders_sync', $this->_data);
    }

    protected function prepareOrderItems($order) {
      $items = $order->get_items();
      $dataProducts = '';
      foreach($items   as $orderItem) {
        $dataProducts .= ($dataProducts != '' ? "\n" : "");
        $dataProducts .= $orderItem->get_name() . ' x ';
        $dataProducts .= $orderItem->get_quantity() . ' ';
        $dataProducts .= number_format($orderItem->get_total(), 2, '.', '') . $order->get_currency() . "";
      }
      $this->_data[ 'order_items' ] = $dataProducts;
    }

    protected function prepareDataByConfArray($confArr, $dataObj, $key_name_addon = '') {
      foreach($confArr as $key => $title) {
        try{
          if(is_array($dataObj)) {
            $value = !empty($dataObj[$key]) ? $dataObj[$key] : '';
          }else{
            $funcName = 'get_' . $key;
            $value = $dataObj->$funcName();
          }
          $this->_data[ $key . $key_name_addon ] = $value;
        }catch(Exception $e) {
          $this->_data[ $key . $key_name_addon ] = '';
        }
      }
    }

    protected function getDataHeaderTitle($keysArray = [], $_key, $key_prefix = '') {
      $headerName = (string)__($keysArray[$_key], 'easymanage-order-sync');
      return ($key_prefix !== '' ? (string)__($this->_keysAdditional[$key_prefix] , 'easymanage-order-sync') . ' ' : '') . $headerName;
    }

  }
}
