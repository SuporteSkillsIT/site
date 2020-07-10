<?php

namespace BoostMyShop\Rma\Block\Rma\Filter\SelectOrder;

use Magento\Framework\DataObject;

class Products extends \Magento\Backend\Block\Widget\Grid\Column\Filter\Text
{
    protected $_order;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\DB\Helper $resourceHelper,
        \BoostMyShop\Rma\Model\ResourceModel\Order $order,
        array $data = []
    ) {
        parent::__construct($context, $resourceHelper, $data);

        $this->_order = $order;
    }

    public function getCondition()
    {
        if ($this->getValue())
        {
            $orderIds = $this->_order->getOrderIdsFromProductName($this->getValue());
            return ['in' => $orderIds];
        }
    }

}