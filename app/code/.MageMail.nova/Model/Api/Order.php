<?php
namespace KJ\MageMail\Model\Api;


class Order extends \KJ\MageMail\Model\Api\ApiAbstract
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $salesResourceModelOrderCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesResourceModelOrderCollectionFactory
    ) {
        $this->salesResourceModelOrderCollectionFactory = $salesResourceModelOrderCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);
    }
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $orders = $this->salesResourceModelOrderCollectionFactory->create();
        $orders->setPageSize($size);
        $orders->addFieldToFilter('customer_email', array('notnull' => true));

        $this->_filterIncremental($params, $orders);

        $orders->getSelect()->joinLeft(
            array('billing' => $this->_table('sales_order_address')),
            "billing.parent_id = main_table.entity_id AND billing.address_type = 'billing'",
            array('billing_firstname' => 'firstname', 'billing_lastname' => 'lastname',
                'billing_street' => 'street', 'billing_city' => 'city', 'billing_state' => 'region',
                'billing_postal_code' => 'postcode', 'billing_country' => 'country_id', 'billing_phone' => 'telephone')
        )->joinLeft(
            array('shipping' => $this->_table('sales_order_address')),
            "shipping.parent_id = main_table.entity_id AND shipping.address_type = 'shipping'",
            array('shipping_firstname' => 'firstname', 'shipping_lastname' => 'lastname',
                'shipping_street' => 'street', 'shipping_city' => 'city', 'shipping_state' => 'region',
                'shipping_postal_code' => 'postcode', 'shipping_country' => 'country_id', 'shipping_phone' => 'telephone')
        )->joinLeft(
            array('payment' => $this->_table('sales_order_payment')),
            'payment.parent_id = main_table.entity_id',
            array('payment_method' => 'method')
        );

        //$orders->getSelect()->group('entity_id');

        return $orders;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'increment_id'                  => 'increment_id',
            'state'                         => 'state',
            'customer_email'                => 'email',
            'customer_firstname'            => 'first_name',
            'customer_lastname'             => 'last_name',
            'subtotal'                      => 'subtotal',
            'subtotal_incl_tax'             => 'subtotal_incl_tax',
            'shipping_amount'               => 'shipping_amount',
            'tax_amount'                    => 'tax_amount',
            'discount_amount'               => 'discount_amount',
            'grand_total'                   => 'grand_total',
            'order_currency_code'           => 'currency',
            'coupon_code'                   => 'coupon_code',
            'entity_id'                     => 'external_order_id',
            'customer_group_id'             => 'external_customer_group_id',
            'created_at'                    => 'external_order_created_at',
            'updated_at'                    => 'external_order_updated_at',
            'store_id'                      => 'external_store_id',
            'shipping_description'          => 'shipping_description',
            'shipping_firstname'            => 'shipping_firstname',
            'shipping_lastname'             => 'shipping_lastname',
            'shipping_street'               => 'shipping_street',
            'shipping_city'                 => 'shipping_city',
            'shipping_state'                => 'shipping_state',
            'shipping_postal_code'          => 'shipping_postal_code',
            'shipping_country'              => 'shipping_country',
            'shipping_phone'                => 'shipping_phone',
            'billing_firstname'             => 'billing_firstname',
            'billing_lastname'              => 'billing_lastname',
            'billing_street'                => 'billing_street',
            'billing_city'                  => 'billing_city',
            'billing_state'                 => 'billing_state',
            'billing_postal_code'           => 'billing_postal_code',
            'billing_country'               => 'billing_country',
            'billing_phone'                 => 'billing_phone',
            'remote_ip'                     => 'remote_ip',
            'payment_method'                => 'payment_method',
            //Custom Fields: Sent only if they exist
            'utmz_medium'                   => 'utmz_medium',
            'file_uploaded'                 => 'file_uploaded'
        );
    }


}