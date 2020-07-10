<?php
namespace KJ\MageMail\Model\Api;


class Coupon extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'coupon_id';

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $collection->getSelect()
            ->from(array('main_table' => $this->_table('salesrule_coupon')), array(
                'external_coupon_id'                => 'main_table.coupon_id',
                'external_sales_rule_id'            => 'main_table.rule_id',
                'coupon_code'                       => 'main_table.code',
            ))
            ->order('main_table.coupon_id');

        $this->_filterIncremental($params, $collection);

        return $collection;
    }

    protected function _filterIncremental($params, $collection)
    {
        if ($params['last_external_entity_id']) {
            $collection->addFieldToFilter($this->_primaryKeyField, array('gt' => $params['last_external_entity_id']))
                ->setOrder($this->_primaryKeyField, 'ASC');

            if ($this->getBatchValue()) {
                $collection->getSelect()->where($this->_primaryKeyField . ' % 3 = ?', $this->getBatchValue() -1);
            }
        }
    }
}