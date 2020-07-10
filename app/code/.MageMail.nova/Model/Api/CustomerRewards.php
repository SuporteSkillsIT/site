<?php
namespace KJ\MageMail\Model\Api;


class CustomerRewards extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $collection->getSelect()->from(
            array('rewards' => $this->_table('rewards_customer_index_points')), array(
            'customer_points_usable'          => 'rewards.customer_points_usable',
            'external_customer_id'            => 'rewards.customer_id',
            'external_updated_at'             => 'max(transfer.last_update_ts)',
            'external_rewards_customer_id'             => 'customer_rewards.rewards_customer_id',
        ))->join(
            array('customer_rewards' => $this->_table('rewards_customer')),
            'rewards.customer_id = customer_rewards.customer_entity_id',
            array()
        )->joinLeft(
            array('transfer' => $this->_table('rewards_transfer')),
            'transfer.customer_id = rewards.customer_id',
            array()
        )->group('rewards.customer_id');

        $this->_filterIncremental($params, $collection);

        return $collection;
    }

    public function query($params)
    {
        if (!($this->_isTableExists('rewards_customer_index_points'))) {
            return array('count' => 0, 'items' => array());
        }

        return parent::query($params);
    }
}