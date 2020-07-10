<?php

namespace BoostMyShop\Supplier\Model\ResourceModel\Invoice\Payments;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('BoostMyShop\Supplier\Model\Invoice\Payments', 'BoostMyShop\Supplier\Model\ResourceModel\Invoice\Payments');
    }

    public function addInvoiceFilter($invoiceId)
    {
        $this->getSelect()->where("bsip_invoice_id = ".$invoiceId);
        return $this;
    }

}
