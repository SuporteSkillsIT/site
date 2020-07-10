<?php

namespace BoostMyShop\Rma\Block\Rma\Edit\Tab\Renderer;

use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class Quantity extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $_storeManager;
    protected $_imageHelper;
    protected $_registry;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Context $context,
                                \Magento\Catalog\Helper\Image $imageHelper,
                                StoreManagerInterface $storemanager,
                                \Magento\Framework\Registry $registry,
                                array $data = [])
    {
        $this->_storeManager = $storemanager;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
        $this->_imageHelper = $imageHelper;
        $this->_registry = $registry;
    }

    public function render(DataObject $row)
    {
        $name = 'items['.$row->getId().'][ri_qty]';
        $html = '<input type="text" size="2" name="'.$name.'" value="'.$row->getri_qty().'"><br>';

        $html .= '<br>'.('Qty ordered').' : '.((int)$row->getOrderItem()->getqty_ordered());
        if ($row->getOrderItem()->getqty_shipped() > 0)
            $html .= '<br>'.('Qty shipped').' : '.((int)$row->getOrderItem()->getqty_shipped());
        if ($row->getOrderItem()->getqty_refunded() > 0)
            $html .= '<br><font color="red">'.('Qty refunded').' : '.((int)$row->getOrderItem()->getqty_refunded()).'</font>';

        return $html;
    }
}