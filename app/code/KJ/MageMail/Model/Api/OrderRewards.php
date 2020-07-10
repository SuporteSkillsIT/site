<?php
namespace KJ\MageMail\Model\Api;


class OrderRewards extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'transfer.rewards_transfer_id';
    protected $_updatedAtField = 'transfer.last_update_ts';

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $collection->getSelect()->from(
            array('reference' => $this->_table('rewards_transfer_reference')), array(
            'external_rewards_transfer_id'    => 'transfer.rewards_transfer_id',
            'quantity'                        => 'transfer.quantity',
            'comments'                        => 'transfer.comments',
            'external_order_id'               => 'reference.reference_id',
            'external_updated_at'             => 'transfer.last_update_ts'
        ))->joinLeft(
            array('transfer' => $this->_table('rewards_transfer')),
            'transfer.rewards_transfer_id = reference.rewards_transfer_id',
            array()
        );

        $this->_filterIncremental($params, $collection);

        return $collection;
    }

    public function query($params)
    {
        if (!($this->_isTableExists('rewards_transfer_reference'))) {
            return array('count' => 0, 'items' => array());
        }

        return parent::query($params);
    }
}