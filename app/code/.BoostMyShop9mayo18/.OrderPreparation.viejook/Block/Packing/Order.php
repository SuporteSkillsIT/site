<?php
namespace BoostMyShop\OrderPreparation\Block\Packing;

class Order extends AbstractBlock
{
    protected $_template = 'OrderPreparation/Packing/Order.phtml';

    public function canDisplay()
    {
        return ($this->hasOrderSelect());
    }

    public function getOrderViewUrl()
    {
        return $this->getUrl('sales/order/view', ['order_id' => $this->currentOrderInProgress()->getip_order_id()]);
    }

    public function getWarehouseName()
    {
        $warehouseId = $this->_preparationRegistry->getCurrentWarehouseId();
        $warehouses = $this->_warehouses->toOptionArray();

        return (isset($warehouses[$warehouseId]) ? $warehouses[$warehouseId] : "Default");
    }
}