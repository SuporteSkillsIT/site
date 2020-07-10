<?php

namespace BoostMyShop\Rma\Model\ResourceModel;


class Order extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('', '');
    }

    public function getOrderIdsFromProductName($searchString)
    {
        $searchString = str_replace('"', '', $searchString);

        $select = $this->getConnection()
            ->select()
            ->from($this->getTable('sales_order_item'), array(new \Zend_Db_Expr('distinct order_id')))
            ->where('sku like "%'.$searchString.'%" or name like "%'.$searchString.'%"');
        ;
        $result = $this->getConnection()->fetchCol($select);

        return $result;
    }

}