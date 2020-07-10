<?php
namespace KJ\MageMail\Model\Api;

class PassbookLink extends ApiAbstract
{
    protected $_primaryKeyField = 'id';

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;
        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $collection->getSelect()
            ->from(array('passbook' => $this->_table('i4_passbook')), array(
                'external_passbook_id'   => 'passbook.id',
                'external_updated_at'   => 'passbook.updated',
                'token'                 => 'token',
                'external_item_id'      => 'order_item_id',
                'external_order_id'      => 'item.order_id',
                'external_parent_item_id'    => 'item.parent_item_id',
                'sku'                    => 'item.sku',
                'email'                   => 'passbook.email',
            ));

        $collection->getSelect()->join(
            array('item' => $this->_table('sales_order_item')),
            'item.item_id = passbook.order_item_id',
            array()
        );

        $this->_filterIncremental($params, $collection);

        return $collection;
    }
}