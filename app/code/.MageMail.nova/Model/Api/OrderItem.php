<?php
namespace KJ\MageMail\Model\Api;

class OrderItem extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'item_id';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory
     */
    protected $salesResourceModelOrderItemCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $salesResourceModelOrderItemCollectionFactory
    ) {
        $this->salesResourceModelOrderItemCollectionFactory = $salesResourceModelOrderItemCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);

    }
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $orderItems = $this->salesResourceModelOrderItemCollectionFactory->create();
        $orderItems->setPageSize($size);

        if (array_key_exists('include_ticket_information', $params) && $params['include_ticket_information'] == 'yes') {
            $this->includeTicketInformation($orderItems);
        }

        $this->_filterIncremental($params, $orderItems);

        $orderItems->addAttributeToFilter('parent_item_id', array('null' => true));

        return $orderItems;
    }

    protected function _processItem($item, $params)
    {
        if ($item['product_type'] == 'grouped') {
            if (is_array($item['product_options'])) {
                $options = $item['product_options'];
            } else {
                $options = unserialize($item['product_options']);
            }
            if (array_key_exists('super_product_config', $options)
                && array_key_exists('product_id', $options['super_product_config'])) {
                $item['group_id'] = $options['super_product_config']['product_id'];
            }
        }

        return parent::_processItem($item, $params);
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'qty_ordered' => 'qty_ordered',
            'sku' => 'sku',
            'store_id' =>'external_store_id',
            'order_id' => 'external_order_id',
            'item_id' => 'external_item_id',
            'group_id' => 'group_id',
            'product_id' => 'external_product_id',
            'created_at' => 'external_created_at',
            'updated_at' => 'external_updated_at',
            'original_product_id' => 'associated_product_id',
        );
    }

    private function includeTicketInformation($orderItems)
    {
        $orderItems->getSelect()
            ->joinLeft(
                array('ticket' => $this->_table('ticketing_seat_queue')),
                "ticket.id = main_table.seat_queue_id",
                array('original_product_id' => 'ticket.event_id')
            );
    }
}