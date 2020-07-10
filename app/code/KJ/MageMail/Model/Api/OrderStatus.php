<?php
namespace KJ\MageMail\Model\Api;


class OrderStatus extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $collection->getSelect()->from(
            array('main_table' => $this->_table('sales_order_status_history')), array(
            'external_order_status_id'  => 'main_table.entity_id',
            'external_order_id'         => 'main_table.parent_id',
            'status'                    => 'main_table.status',
            'entity_name'               => 'main_table.entity_name',
            'external_created_at'       => 'main_table.created_at',
        ));

        $this->_filterIncremental($params, $collection);

        return $collection;
    }
}