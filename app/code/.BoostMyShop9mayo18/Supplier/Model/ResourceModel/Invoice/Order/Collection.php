<?php

namespace BoostMyShop\Supplier\Model\ResourceModel\Invoice\Order;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('BoostMyShop\Supplier\Model\Invoice\Order', 'BoostMyShop\Supplier\Model\ResourceModel\Invoice\Order');
    }

    public function addInvoiceFilter($invoiceId)
    {
        $this->getSelect()->where("bsio_invoice_id = ".$invoiceId);
        return $this;
    }

    public function addOrderFilter($orderId)
    {
        $this->getSelect()
            ->where("bsio_order_id = ".$orderId)
            ->join($this->getTable('bms_supplier_invoice'), 'bsi_id = bsio_invoice_id');
        return $this;
    }

    public function addInvoiceFilterForPo($invId)
    {
        $this->getSelect()
            ->where("bsio_invoice_id = ".$invId)
            ->join($this->getTable('bms_purchase_order'), 'po_id = bsio_order_id');
        return $this;
    }

    public function getAlreadyLinkedOrders($invId)
    {
        $this->getSelect()->reset()->from($this->getMainTable(), ['bsio_order_id'])->where("bsio_invoice_id = ".$invId);
        $ids = $this->getConnection()->fetchCol($this->getSelect());
        return $ids;
    }

    
}
